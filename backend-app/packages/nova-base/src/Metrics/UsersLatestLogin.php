<?php
namespace Larabase\Nova\Metrics;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;
use ThijsSimonis\NovaListCard\NovaListCard;

class UsersLatestLogin extends NovaListCard
{
    public $width = '1/3';

    public function __construct()
    {
        parent::__construct();

        $this->title('Đăng nhập gần đây');

        $rows = Activity::where('description', 'login')
            ->orderBy('created_at', 'DESC')->limit(5)->get()
            ->map(function ($row, $key) {
                return [
                    'id' => $key+1,
                    'name' => $row->causer->name,
                    'created_at' => $row->created_at->diffForHumans(Carbon::now()),
                    'view' => config('nova.path') . '/resources/users/' . $row['subject_id']
                ];
            })
        ;

        $this->rows($rows);
    }

    public function uriKey(): string
    {
        return 'latest-users';
    }
}
