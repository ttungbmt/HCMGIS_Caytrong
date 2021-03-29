<?php

namespace Larabase\NovaPage\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Nova\ResolvesFields;
use Illuminate\Routing\Controller;
use Laravel\Nova\Contracts\Resolvable;
use Laravel\Nova\Fields\FieldCollection;
use Illuminate\Support\Facades\Validator;
use Laravel\Nova\Http\Requests\NovaRequest;
use Larabase\NovaPage\NovaPage;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Laravel\Nova\Panel;

class PageController extends Controller
{
    use ResolvesFields, ConditionallyLoadsAttributes;

    public function get(Request $request)
    {
        $fields = $this->assignToPanels(__('app.navigationItemTitle'), $this->availableFields($request->get('path')));
        $panels = $this->panelsWithDefaultLabel(__('app.navigationItemTitle'), app(NovaRequest::class));

        $addResolveCallback = function (&$field) {
//            if (!empty($field->attribute)) {
//                $page = NovaPage::getSettingsModel()::findOrNew($field->attribute);
//                $field->resolve([$field->attribute => isset($page) ? $page->value : '']);
//            }
//
//            if (!empty($field->meta['fields'])) {
//                foreach ($field->meta['fields'] as $_field) {
//                    $page = NovaPage::getSettingsModel()::where('key', $_field->attribute)->first();
//                    $_field->resolve([$_field->attribute => isset($page) ? $page->value : '']);
//                }
//            }
        };

        $fields->each(function (&$field) use ($addResolveCallback) {
            $addResolveCallback($field);
        });


        return response()->json([
            'panels' => $panels,
            'fields' => $fields,
        ], 200);
    }

    public function post(NovaRequest $request)
    {
        $path = $request->post('path');
        $fields = $this->availableFields($request->get('path', 'general'));
        $layoutClass = NovaPage::getLayout($path)['class'];

        // NovaDependencyContainer support
        $fields = $fields->map(function ($field) {
            if (!empty($field->attribute)) return $field;
            if (!empty($field->meta['fields'])) return $field->meta['fields'];
            return null;
        })->filter()->flatten();

        $rules = [];
        foreach ($fields as $field) {
            $rules = array_merge($rules, $field->getUpdateRules($request));
        }

        Validator::make($request->all(), $rules)->validate();

        $fields = $fields->whereInstanceOf(Resolvable::class);

        $tempResource =  new \stdClass;
        $data = $fields->each(function (&$field) use ($request, $tempResource) {
            $attribute = $field->attribute;

            if (empty($attribute)) return;
            if ($field->isReadonly(app(NovaRequest::class))) return;
            $field->resolve([$attribute => $request->input($attribute)]);

            $field->fill($request, $tempResource);

            if (!property_exists($tempResource, $field->attribute)) return;

            return $tempResource;
        });

        return (new $layoutClass)->asController($request, $fields, $tempResource);

//        if (config('nova-page.reload_page_on_save', false) === true) {
//            return response()->json(['reload' => true]);
//        }
//
//        return response('', 204);
    }

    protected function availableFields($path)
    {
        return (new FieldCollection(($this->filter(NovaPage::getFields($path)))))->authorized(request());
    }

    protected function fields(Request $request, $path = 'general')
    {
        return NovaPage::getFields($path);
    }


    /**
     * Return the panels for this request with the default label.
     *
     * @param  string  $label
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    protected function panelsWithDefaultLabel($label, NovaRequest $request)
    {
        $method = $this->fieldsMethod($request);
        $path = $request->get('path');

        return with(
            collect(array_values($this->{$method}($request, $path)))->whereInstanceOf(Panel::class)->unique('name')->values(),
            function ($panels) use ($label) {
                return $panels->when($panels->where('name', $label)->isEmpty(), function ($panels) use ($label) {
                    return $panels->prepend((new Panel($label))->withToolbar());
                })->all();
            }
        );
    }
}
