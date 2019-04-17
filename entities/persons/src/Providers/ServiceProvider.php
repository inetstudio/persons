<?php

namespace InetStudio\PersonsPackage\Persons\Providers;

use Collective\Html\FormBuilder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class ServiceProvider.
 */
class ServiceProvider extends BaseServiceProvider
{
    /**
     * Загрузка сервиса.
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerFormComponents();
    }

    /**
     * Регистрация команд.
     */
    protected function registerConsoleCommands(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->commands(
            [
                'InetStudio\PersonsPackage\Persons\Console\Commands\SetupCommand',
                'InetStudio\PersonsPackage\Persons\Console\Commands\CreateFoldersCommand',
            ]
        );
    }

    /**
     * Регистрация ресурсов.
     */
    protected function registerPublishes(): void
    {
        $this->publishes(
            [
                __DIR__.'/../../config/persons.php' => config_path('persons.php'),
            ],
            'config'
        );

        $this->mergeConfigFrom(__DIR__.'/../../config/filesystems.php', 'filesystems.disks');

        if (! $this->app->runningInConsole()) {
            return;
        }

        if (Schema::hasTable('persons')) {
            return;
        }

        $timestamp = date('Y_m_d_His', time());
        $this->publishes(
            [
                __DIR__.'/../../database/migrations/create_persons_tables.php.stub' => database_path(
                    'migrations/'.$timestamp.'_create_persons_tables.php'
                ),
            ],
            'migrations'
        );
    }

    /**
     * Регистрация путей.
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.persons');
    }

    /**
     * Регистрация компонентов форм.
     */
    protected function registerFormComponents(): void
    {
        FormBuilder::component(
            'persons',
            'admin.module.persons::back.forms.fields.persons',
            ['name' => null, 'value' => null, 'attributes' => null]
        );
    }
}
