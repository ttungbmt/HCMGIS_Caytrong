<?php
namespace Larabase\Nova\Actions;

use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DownloadExcel extends \Maatwebsite\LaravelNovaExcel\Actions\DownloadExcel implements WithHeadings, ShouldAutoSize, WithColumnWidths, WithStyles
{
    public function __construct()
    {
        $this
            ->withHeadings()
            ->withChunkCount(300)
            ->askForFilename()
            ->askForWriterType();
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true], 'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FEFE00']]],
        ];
    }

    public function columnWidths(): array
    {
       return [];
    }
}
