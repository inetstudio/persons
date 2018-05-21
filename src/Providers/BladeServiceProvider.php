<?php

namespace InetStudio\Persons\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        Blade::directive('person', function ($expression) {
            $params = explode(',', $expression, 2);
            $params = array_map('trim', $params, array_fill(0, count($params),"' \t\n\r\0\x0B"));

            $view = 'admin.module.persons::back.partials.content.'.$params[0];

            if (view()->exists($view)) {
                return view($view, json_decode(str_replace("\r\n", '', $params[1]), true));
            }

            return '';
        });
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
