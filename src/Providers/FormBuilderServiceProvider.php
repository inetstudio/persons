<?php

namespace InetStudio\Persons\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\ServiceProvider;

/**
 * Class FormBuilderServiceProvider.
 */
class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        FormBuilder::component('persons', 'admin.module.persons::back.forms.fields.persons', ['name' => null, 'value' => null, 'attributes' => null]);
    }
}
