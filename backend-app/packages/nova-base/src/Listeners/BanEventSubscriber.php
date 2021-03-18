<?php
namespace Larabase\Nova\Listeners;

class BanEventSubscriber
{
    protected $logName = 'user';

    public function handleUserBanned($event)
    {
        $model = $event->model;

        activity($this->logName)
            ->performedOn($model)
            ->withProperties([
                'user_id' => $model->getAuthIdentifier(),
                'user_email' => $model->email,
            ])
            ->log('banned');

        $this->setStatus($model, 'banned');
    }

    public function handleUserUnbanned($event)
    {
        $model = $event->model;

        activity($this->logName)
            ->performedOn($model)
            ->withProperties([
                'user_id' => $model->getAuthIdentifier(),
                'user_email' => $model->email,
            ])
            ->log('unbanned');

        $model->status === 'banned' && $this->setStatus($model, 'active');
    }

    protected function setStatus($model, $status){
        method_exists($model, 'setStatus') &&  $model->setStatus($status);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'Cog\Laravel\Ban\Events\ModelWasBanned',
            [$this, 'handleUserBanned']
        );

        $events->listen(
            'Cog\Laravel\Ban\Events\ModelWasUnbanned',
            [$this, 'handleUserUnbanned']
        );
    }
}