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

if (! function_exists('config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return mixed|\Illuminate\Config\Repository
     */
    function config($key = null, $default = null)
    {
        $factory = \Illuminate\Support\Facades\App::make(\Illuminate\Contracts\Config\Repository::class);

        if (is_null($key)) {
            return $factory;
        }

        if (is_array($key)) {
            return $factory->set($key);
        }

        return $factory->get($key, $default);
    }
}

if (! function_exists('__')) {
    /**
     * Translate the given message.
     *
     * @return \Illuminate\Contracts\Translation\Translator|string|array|null
     */
    function __(?string $key = null, array $replace = [], ?string $locale = null)
    {
        $factory = \Illuminate\Support\Facades\App::make(\Illuminate\Contracts\Translation\Translator::class);

        if (is_null($key)) {
            return $factory;
        }

        return $factory->get($key, $replace, $locale);
    }
}

if (! function_exists('request')) {
    /**
     * Get an instance of the current request or an input item from the request.
     *
     * @param  array|string|null  $key
     * @param  mixed  $default
     * @return \Illuminate\Http\Request|string|array|null
     */
    function request($key = null, $default = null)
    {
        $factory = \Illuminate\Support\Facades\App::make(\Illuminate\Http\Request::class);

        if (is_null($key)) {
            return $factory;
        }

        if (is_array($key)) {
            return $factory->only($key);
        }

        return $factory->input($key, $default);
    }
}
