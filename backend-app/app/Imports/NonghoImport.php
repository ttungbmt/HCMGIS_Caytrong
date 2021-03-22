<?php

namespace App\Imports;


use App\Models\Caytrong;
use App\Models\Dichte;
use App\Models\DientichSx;
use App\Models\LoaiGh;
use App\Models\Nongho;
use App\Models\NonghoRt;
use App\Models\Ranhthua;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Row;

abstract class SheetImport implements ToCollection, WithHeadingRow
{
    protected $collection;

    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }
}

class NonghoSheetImport extends SheetImport
{
    public static $name = 'NONG HO';

    public function collection(Collection $rows)
    {
        $this->collection->put(self::$name, $rows);
    }
}

class ThuadatSheetImport extends SheetImport
{
    public static $name = 'THUA DAT';

    public function collection(Collection $rows)
    {
        $this->collection->put(self::$name, $rows);
    }
}

class DientichSheetImport extends SheetImport
{
    public static $name = 'DIEN TICH';

    public function collection(Collection $rows)
    {
        $this->collection->put(self::$name, $rows);
    }
}

class DichteSheetImport extends SheetImport
{
    public static $name = 'DICH TE';

    public function collection(Collection $rows)
    {
        $this->collection->put(self::$name, $rows);

        DB::beginTransaction();

        try {
            foreach ($this->collection->get(NonghoSheetImport::$name) as $r_nh) {
                $rm_empty_col = fn($i) => $i->except('');
                $thuadats = $this->collection->get(ThuadatSheetImport::$name)->where('nongho_id', $r_nh->get('nongho_id'))->map($rm_empty_col);
                $dientichs = $this->collection->get(DientichSheetImport::$name)->where('nongho_id', $r_nh->get('nongho_id'))->map($rm_empty_col);
                $dichtes = $this->collection->get(DichteSheetImport::$name)->where('nongho_id', $r_nh->get('nongho_id'))->map($rm_empty_col);

                $nongho = Nongho::create($r_nh->except(['nongho_id', 'stt'])->all());

                // THUA DAT -------------------------------------------
                foreach ($thuadats as $i){
                    $rt = Ranhthua::where($rt_data = [
                        'maphuong' => $i['soto'],
                        'sh_bando' => $i['soto'],
                        'sh_thua' => $i['sothua'],
                    ])->first();

                    if(!$rt) $rt= Ranhthua::create($rt_data);
                }

                $nh_rt = NonghoRt::create([
                    'nongho_id' => $nongho->id,
                    'ranhthua_id' => $rt->id,
                    'dt' => (float)str_replace( ',', '.', $i['dientich'])
                ]);


                // DIEN TICH -------------------------------------------
                foreach ($dientichs as $i){
                    if($i['loai_ctr']){
                        $ctr = Caytrong::where($ctr_data = ['ten' => $i['loai_ctr']])->first();
                        if(!$ctr) $ctr= Caytrong::create($ctr_data);

                        $dt = DientichSx::create($i->merge([
                            'nongho_id' => $nongho->id,
                            'loai_ctr_id' => $ctr->id,
                            'dt_gt' => (float)str_replace( ',', '.', $i['dt_gt']),
                            'dt_vg' => (float)str_replace( ',', '.', $i['dt_vg']),
                        ])->all());
                    }
                }

                // DICH TE  -------------------------------------------
                foreach ($dichtes as $i){
                    if($i['loai_gh']){
                        $gh = LoaiGh::where($gh_data = ['ten' => $i['loai_gh']])->first();
                        if(!$gh) $gh= LoaiGh::create($gh_data);

                        $dt = Dichte::create($i->merge([
                            'nongho_id' => $nongho->id,
                            'loai_ctr_id' => $gh->id,
                        ])->all());
                    }
                }

            }
        } catch (ValidationException $e) {
            DB::rollback();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();


//        $rows = $this->getFormatedRows($rows);
//        foreach ($rows as $r) {
//            $loai_gh = LoaiGh::where(['ten' => $r['loai_gh']])->first();
//            $loai_ctr = optional(Caytrong::where('ten', $r['loai_ctr'])->first());
//
//            if ($loai_gh) {
//                $data = $r->merge([
//                    'loai_gh_id' => $loai_gh->id,
//                    'loai_ctr_id' => $loai_ctr->id,
//                ])->all();
//
//                Dichte::create($data)->all();
//            }
//        }

    }
}


class NonghoImport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $collection = new Collection();

        return [
            NonghoSheetImport::$name => new NonghoSheetImport($collection),
            ThuadatSheetImport::$name => new ThuadatSheetImport($collection),
            DientichSheetImport::$name => new DientichSheetImport($collection),
            DichteSheetImport::$name => new DichteSheetImport($collection),
        ];
    }
}
