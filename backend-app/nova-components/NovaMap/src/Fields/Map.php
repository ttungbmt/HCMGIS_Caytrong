<?php

namespace Larabase\Nova\Map\Fields;


use App\Support\Helper;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;
use MStaack\LaravelPostgis\Geometries\Point;

class Map extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'map';

    protected $dirtyDrawEditor = false;

    public function __construct($name, $attribute = null, callable $resolveCallback = null)
    {
        parent::__construct($name, $attribute, $resolveCallback);

        $request = app(NovaRequest::class);


        $this
            ->searchable()
            ->controls([
                'layers' => [

                ],
                'locate' => [
                    'icon' => 'fal fa-location'
                ],
                'geoman' => [
                    'position' => 'topleft',
                    'drawMultiple' => false,
                    'drawMarker' => true,
                    'drawCircleMarker' => true,
                    'drawPolyline' => true,
                    'drawRectangle' => true,
                    'drawPolygon' => true,
                    'drawCircle' => true,
                    'cutPolygon' => false,
                    'editMode' => true,
                    'dragMode' => true,
                    'oneBlock' => false,

                    'drawControls' => true,
                    'editControls' => true,
                ],
                'fullscreen' => [
                    'position' => 'bottomright',
                ]
            ])
            ->layers([
                [
                    'control' => 'basemap',
                    'type' => 'tile',
                    'title' => 'Google',
                    'options' => ['url' => 'http://mt2.google.com/vt/lyrs=m&x={x}&y={y}&z={z}'],
                ],
                [
                    'control' => 'basemap',
                    'type' => 'tile',
                    'title' => 'Map4D',
                    'options' => ['url' => 'http://rtile.map4d.vn/all/2d/{z}/{x}/{y}.png'],
                    'active' => true,
                ],
            ])
            ->fullWidth()
            ->mapOptions(config('nova-map.config'))
            ->placeholder(__('Search'));

        if($request->isResourceDetailRequest()){
            $this
                ->hideDrawEditor()
                ->searchable(false);
        }
    }

    public function tippyOptions(array $tippyOptions){
        return $this->withMeta([__FUNCTION__ => $tippyOptions]);
    }

    protected function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
    {
        if ($request->exists($requestAttribute)) {
            $results = json_decode($request->{$requestAttribute}, false);

            if (!empty($results)) {
                foreach ($results as $r) {
                    $data = null;

                    if(method_exists($model, 'getPostgisType')){
                        $spatialType = $model->getPostgisType('geom')['type'];

                        if ($spatialType === 'Point') {
                            $data = new Point($r->data[0], $r->data[1]);
                        }
                    } else {
                        $data = $r;
                    }

                    $model->{$r->geom_field} = $this->isNullValue($data) ? null : $data;
                }
            } else {
                $model->{$requestAttribute} = null;
            }
        }
    }

    public function hideDrawEditor(){
        data_set($this->meta, 'controls.geoman', array_merge(data_get($this->meta, 'controls.geoman'), [
            'drawControls' => false,
            'editControls' => false,
        ]));

        return $this;
    }

    public function spatialType(string $type)
    {
        return $this->withMeta([__FUNCTION__ => $type]);
    }

    public function showMap(bool $field = true)
    {
        return $this->withMeta([__FUNCTION__ => $field]);
    }

    public function searchable($searchable = true)
    {
        if($searchable == false) return $this->withMeta([__FUNCTION__ => false]);

        return $this->withMeta([__FUNCTION__ => $searchable === true ? Place::make(__('Search'), 'search')->searchProvider('map4d') : $searchable]);
    }

    protected function resolveAttribute($resource, $attribute)
    {
        if(method_exists($resource, 'getPostgisType')){
            $spatialType = $resource->getPostgisType('geom')['type'];

            $this->withMeta(['geomTypes' => [['type' => $spatialType, 'attribute' => $attribute]]]);

            if ($spatialType === 'Point') {
                !$this->dirtyDrawEditor && $this->setMarkerEditor();

                $latlng = $resource->geom ? [$resource->geom->getLat(), $resource->geom->getLng()] : [];
                if ($this->meta['mapOptions']['zoom'] == config('nova-map.config.zoom') && !empty($latlng)) {
                    $this->center($latlng);
                    $this->zoom(14);
                }

                return [
                    [
                        'type' => 'marker',
                        'data' => $resource->geom ? [$resource->geom->getLat(), $resource->geom->getLng()] : [],
                        'geom_field' => $attribute,
                        'active' => true
                    ]
                ];
            } elseif (in_array($spatialType, ['MultiPolygon', 'Polygon'])) {
                !$this->dirtyDrawEditor && $this->setPolygonEditor();
                if (!isset($this->meta['mapOptions']['bounds'])) $this->bounds($resource->geom);

                return [
                    [
                        'type' => 'geojson',
                        'data' => $resource->geom,
                        'geom_field' => $attribute,
                        'active' => true
                    ]
                ];
            } elseif (in_array($spatialType, ['MultiPolyline', 'Polyline'])) {
                !$this->dirtyDrawEditor && $this->setPolylineEditor();
            }
        }


        return data_get($resource, str_replace('->', '.', $attribute), []);
    }

    public function showSearchBar(bool $searchBar = true)
    {
        return $this->withMeta([__FUNCTION__ => $searchBar]);
    }

    public function showLatLngField(bool $latLngField = true)
    {
        return $this->withMeta([__FUNCTION__ => $latLngField]);
    }

    public function showGeoJSONField(bool $geoJSONField = true)
    {
        return $this->withMeta([__FUNCTION__ => $geoJSONField]);
    }

    public function drawMultiple(bool $field = true)
    {
        $this->meta['controls']['geoman']['drawMultiple'] = $field;
        return $this;
    }

    public function mapOptions(array $mapOptions)
    {
        if (isset($this->meta['mapOptions'])) $mapOptions = array_merge($this->meta['mapOptions'], $mapOptions);

        return $this->withMeta([__FUNCTION__ => $mapOptions]);
    }

    public function mapStyle($mapStyle)
    {
        return $this->withMeta([__FUNCTION__ => $mapStyle]);
    }

    public function fullWidth()
    {
        return $this->withMeta([__FUNCTION__ => true]);
    }

    public function center(array $center)
    {
        $this->meta['mapOptions']['center'] = $center;
        return $this->withMeta([__FUNCTION__ => $center]);
    }

    public function zoom(int $zoom)
    {
        $this->meta['mapOptions']['zoom'] = $zoom;
        return $this;
    }

    public function bounds($bounds)
    {
        if ($bounds) $this->meta['mapOptions']['bounds'] = $bounds;
        return $this;
    }

    public function layers(array $layers)
    {
        return $this->withMeta([__FUNCTION__ => $layers]);
    }

    public function addLayer(array $layer)
    {
        $this->meta['layers'][] = $layer;
        return $this;
    }

    public function controls(array $controls)
    {
        return $this->withMeta([__FUNCTION__ => $controls]);
    }

    public function addControl($name, array $options)
    {
        $this->meta['controls'][$name] = $options;
        return $this;
    }

    public function iconRetinaUrl(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }

    public function iconUrl(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }

    public function shadowUrl(string $url)
    {
        return $this->withMeta([__FUNCTION__ => $url]);
    }

    public function setMarkerEditor()
    {
        $this->meta['controls']['geoman'] = array_merge($this->meta['controls']['geoman'], [
            'drawMarker' => true,
            'drawCircleMarker' => false,
            'drawPolyline' => false,
            'drawRectangle' => false,
            'drawPolygon' => false,
            'drawCircle' => false,
            'oneBlock' => true,
            'editMode' => false,
        ]);

        $this->dirtyDrawEditor = true;

        return $this;
    }

    public function setPolygonEditor()
    {
        $this->meta['controls']['geoman'] = array_merge($this->meta['controls']['geoman'], [
            'drawMarker' => false,
            'drawCircleMarker' => false,
            'drawPolyline' => false,
            'drawRectangle' => true,
            'drawPolygon' => true,
            'drawCircle' => false,
            'oneBlock' => false
        ]);

        $this->showLatLngField(false);
        $this->dirtyDrawEditor = true;

        return $this;
    }

    public function setPolylineEditor()
    {
        $this->meta['controls']['geoman'] = array_merge($this->meta['controls']['geoman'], [
            'drawMarker' => false,
            'drawCircleMarker' => false,
            'drawPolyline' => true,
            'drawRectangle' => false,
            'drawPolygon' => false,
            'drawCircle' => false,
            'oneBlock' => true
        ]);

        $this->showLatLngField(false);
        $this->dirtyDrawEditor = true;

        return $this;
    }

    public function setStatsEditor()
    {
        $this->meta['controls']['geoman'] = array_merge($this->meta['controls']['geoman'], [
            'drawMarker' => false,
            'drawCircleMarker' => false,
            'drawPolyline' => false,
            'drawRectangle' => true,
            'drawPolygon' => true,
            'drawCircle' => true,
            'oneBlock' => false
        ]);

        $this->meta['controls']['fullscreen'] = array_merge($this->meta['controls']['fullscreen'], []);
        $this->withMeta(['geomTypes' => [
            ['type' => 'Polygon', 'attribute' => 'polygon_geom'],
            ['type' => 'Point', 'attribute' => 'point_geom'],
        ]]);

        $this->dirtyDrawEditor = true;

        return $this;
    }

    public function allowMapClick(bool $allowMapClick)
    {
        return $this->withMeta([__FUNCTION__ => $allowMapClick]);
    }

    public function scriptUrlParams(array $scriptUrlParams)
    {
        return $this->withMeta([__FUNCTION__ => $scriptUrlParams]);
    }

    public function autoCompleteOptions(array $autoCompleteOptions)
    {
        return $this->withMeta([__FUNCTION__ => $autoCompleteOptions]);
    }

    public function geocodeOptions(array $geocodeOptions)
    {
        return $this->withMeta([__FUNCTION__ => $geocodeOptions]);
    }

//    public function googleKey() {
//        return $this->withMeta([
//            'googleKey' => config('nova.maps-address-field.key')
//        ]);
//    }
//
//    public function fillAttributeFromRequest(NovaRequest $request, $requestAttribute, $model, $attribute)
//    {
//        $model->setAttribute($attribute, json_decode($request->$attribute, true));
//    }
}
