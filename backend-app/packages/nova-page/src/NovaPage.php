<?php

namespace Larabase\NovaPage;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Illuminate\Support\Str;

class NovaPage extends Tool
{
    protected static $config = [
        'title' => 'Page'
    ];

    protected static $layouts = [];

    protected static $fields = [];

    public static function setConfig($config)
    {
        static::$config = array_merge(static::$config, $config);
    }

    public function boot()
    {
        Nova::script('nova-page', __DIR__ . '/../dist/js/tool.js');
    }

    public function renderNavigation()
    {
        return view('nova-page::navigation', [
            'config' => static::$config,
            'layouts' =>  static::$layouts,
            'fields' => static::$fields
        ]);
    }

    public static function addLayout($layout)
    {
        $path = Str::lower(Str::of($layout::$path)->replace('/', '-')->slug());
        static::$layouts[$path] = [
            'class' => $layout,
            'title' => $layout::title(),
        ];
    }

    public static function getLayout($path){
        return static::$layouts[$path];
    }

    /**
     * Define page fields and an optional casts.
     *
     * @param array|callable $fields Array of fields/panels to be displayed or callable that returns an array.
     * @param array $casts Associative array same as Laravel's $casts on models.
     **/
    public static function addPageFields($path, $fields = [])
    {
        $path = Str::lower(Str::of($path)->replace('/', '-')->slug());

        static::$fields[$path] = static::$fields[$path] ?? [];
        if (is_callable($fields)) $fields = [$fields];
        static::$fields[$path] = array_merge(static::$fields[$path], $fields ?? []);
    }

    public static function getFields($path)
    {
        $layoutClass = static::$layouts[$path]['class'];

        return with(new $layoutClass)->fields(request());
    }

    public static function clearFields()
    {
        static::$fields = [];
    }


    public static function doesPathExist($path)
    {
        return array_key_exists($path, static::$layouts);
    }
}
