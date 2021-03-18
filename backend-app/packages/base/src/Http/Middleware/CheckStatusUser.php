<?php

namespace Larabase\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CheckStatusUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $ttl = 60*60*12;

        if (auth()->check() && $status = auth()->user()->status) {
            $user = auth()->user();

            switch ($status){
                case 'inactive':
                    auth()->logout();
                    return abort(403, 'Your account has been deactivated. Please contact administrator.');
                case 'banned':
                    if($user->isBanned()){
                        $banned = $user->bans()->latest()->limit(1)->first();
                        $message = 'Your account has been blocked. Please contact administrator.';

                        auth()->logout();

                        if($banned->expired_at){
                            if(now()->lessThan($banned->expired_at)){
                                $banned_days = now()->diffInDays($banned->expired_at);

                                if($banned_days === 0) {
                                    $banned_hours = now()->diffInHours($banned->expired_at);
                                    $message = 'Your account has been blocked for ' . $banned_hours . ' ' . Str::plural('hour', $banned_hours) . '. Please contact administrator.';
                                } else {
                                    $message = 'Your account has been blocked for ' . $banned_days . ' ' . Str::plural('day', $banned_days) . '. Please contact administrator.';
                                }

                                return $this->redirectToBanned($message);
                            } else {
                                $user->unban();
                                $request->session()->flash('errors', 'Your account has been unblocked. Please try login again!');

                                return redirect('/');
                            }
                        } else {
                            return $this->redirectToBanned($message);
                        }
                    }
            }
        }

        return $next($request);
    }

    protected function redirectToBanned($message){
        $redirectUrl = config('ban.redirect_url', null);
        $errors = ['login' => $message];

        if ($redirectUrl) return redirect($redirectUrl)->withInput()->withErrors($errors);

        return abort(403, $message);
    }
}