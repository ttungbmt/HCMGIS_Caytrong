<?php

namespace Larabase\NovaPage\Http\Middleware;


use Larabase\NovaPage\NovaPage;

class PagePathExists
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        $path = $request->get('path');
        $path = !empty($path) ? trim($path) : 'general';
        return NovaPage::doesPathExist($path) ? $next($request) : abort(404);
    }
}
