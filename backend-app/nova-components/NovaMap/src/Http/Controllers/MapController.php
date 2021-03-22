<?php
namespace Larabase\Nova\Map\Http\Controllers;

use Larabase\Nova\Map\NovaMap;

class MapController
{
    public function __invoke()
    {
        $config = NovaMap::getConfig();

        return response()->json($config);
    }
}
