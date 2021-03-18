<?php
namespace Larabase\Nova;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource as NovaResource;

abstract class Resource extends NovaResource
{
    public static $tableStyle = 'default'; // tight

    public static $perPageOptions = [10, 25, 50, 100, 150, 200];

    public static $defaultSort = [];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        return $query->when(static::$defaultSort && empty($request->get('orderBy')), function(Builder $query) {
            $query->getQuery()->orders = [];

            return $query->orderBy(key(static::$defaultSort), reset(static::$defaultSort));
        });
    }


    /**
     * Return the location to redirect the user after creation.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterCreate(NovaRequest $request, $resource)
    {
        if($request->has('viaResource')) return '/resources/'.$request->get('viaResource').'/'.$request->get('viaResourceId');

        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }

    /**
     * Return the location to redirect the user after update.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Laravel\Nova\Resource  $resource
     * @return string
     */
    public static function redirectAfterUpdate(NovaRequest $request, $resource)
    {
        if($request->has('viaResource')) return '/resources/'.$request->get('viaResource').'/'.$request->get('viaResourceId');

        return '/resources/'.static::uriKey().'/'.$resource->getKey();
    }
}