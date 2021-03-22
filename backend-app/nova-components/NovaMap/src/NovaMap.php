<?php
namespace Larabase\Nova\Map;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class NovaMap extends Tool
{
    protected static $config = [];
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {

    }

    public static function setConfig($config){
        static::$config = $config;
    }

    public static function getConfig(){
        $config = static::$config;
        return is_callable($config) ? $config() : $config;
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('nova-map::navigation');
    }
}
