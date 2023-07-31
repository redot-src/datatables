<?php

if (! function_exists('view')) {
    /**
     * Get the evaluated view contents for the given view.
     *
     * @param  string|null  $view
     * @param  array  $data
     * @param  array  $mergeData
     * @return \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory
     */
    function view($view = null, $data = [], $mergeData = [])
    {
        $factory = \Illuminate\Support\Facades\App::make(\Illuminate\Contracts\View\Factory::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->make($view, $data, $mergeData);
    }
}

if (! function_exists('route')) {
    /**
     * Generate the URL to a named route.
     *
     * @param  array|string  $name
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string|\Illuminate\Contracts\Routing\UrlGenerator
     */
    function route($name, $parameters = [], $absolute = true)
    {
        $factory = \Illuminate\Support\Facades\App::make(\Illuminate\Contracts\Routing\UrlGenerator::class);

        if (func_num_args() === 0) {
            return $factory;
        }

        return $factory->route($name, $parameters, $absolute);
    }
}