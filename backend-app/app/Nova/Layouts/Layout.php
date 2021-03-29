<?php

namespace App\Nova\Layouts;

use Illuminate\Http\Request;

abstract class Layout
{
    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function fields(Request $request);
}
