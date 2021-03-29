<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Infinety\Filemanager\FilemanagerField;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Text;

class Caytrong extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Caytrong::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'ten';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'ten',
    ];

    public static $globallySearchable = false;

    public static function group()
    {
        return __('Directory');
    }

    public static function label()
    {
        return __('app.caytrong');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $nameFn = function (Request $request) {
            $file = $request->image_src;
            return pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'-'.time().'.'.$file->extension();
        };

        return [
            ID::make(__('ID'), 'id')->sortable(),
            Image::make(__('Image'), 'image_src')->squared()->path('Image')->prunable()->storeAs($nameFn),
            Text::make(__('app.ten'), 'ten')->sortable()->rules('required')->creationRules('unique:'.self::$model),
            DateTime::make(__('Created at'), 'created_at')->exceptOnForms()->fillInto(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
