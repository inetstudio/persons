<?php

namespace InetStudio\Persons\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class PersonsServiceProvider.
 */
class PersonsServiceProvider extends ServiceProvider
{
    /**
     * Загрузка сервиса.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerConsoleCommands();
        $this->registerPublishes();
        $this->registerRoutes();
        $this->registerViews();
        $this->registerObservers();
    }

    /**
     * Регистрация привязки в контейнере.
     *
     * @return void
     */
    public function register(): void
    {
        $this->registerBindings();
    }

    /**
     * Регистрация команд.
     *
     * @return void
     */
    protected function registerConsoleCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                'InetStudio\Persons\Console\Commands\SetupCommand',
                'InetStudio\Persons\Console\Commands\CreateFoldersCommand',
            ]);
        }
    }

    /**
     * Регистрация ресурсов.
     *
     * @return void
     */
    protected function registerPublishes(): void
    {
        $this->publishes([
            __DIR__.'/../../config/persons.php' => config_path('persons.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreatePersonsTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_persons_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_persons_tables.php'),
                ], 'migrations');
            }
        }
    }

    /**
     * Регистрация путей.
     *
     * @return void
     */
    protected function registerRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
    }

    /**
     * Регистрация представлений.
     *
     * @return void
     */
    protected function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.persons');
    }

    /**
     * Регистрация наблюдателей.
     *
     * @return void
     */
    protected function registerObservers(): void
    {
        $this->app->make('InetStudio\Persons\Contracts\Models\PersonModelContract')::observe($this->app->make('InetStudio\Persons\Contracts\Observers\PersonObserverContract'));
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        // Controllers
        $this->app->bind('InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsControllerContract', 'InetStudio\Persons\Http\Controllers\Back\PersonsController');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsDataControllerContract', 'InetStudio\Persons\Http\Controllers\Back\PersonsDataController');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsUtilityControllerContract', 'InetStudio\Persons\Http\Controllers\Back\PersonsUtilityController');

        // Events
        $this->app->bind('InetStudio\Persons\Contracts\Events\Back\ModifyPersonEventContract', 'InetStudio\Persons\Events\Back\ModifyPersonEvent');

        // Models
        $this->app->bind('InetStudio\Persons\Contracts\Models\PersonModelContract', 'InetStudio\Persons\Models\PersonModel');

        // Observers
        $this->app->bind('InetStudio\Persons\Contracts\Observers\PersonObserverContract', 'InetStudio\Persons\Observers\PersonObserver');

        // Repositories
        $this->app->bind('InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract', 'InetStudio\Persons\Repositories\PersonsRepository');

        // Requests
        $this->app->bind('InetStudio\Persons\Contracts\Http\Requests\Back\SavePersonRequestContract', 'InetStudio\Persons\Http\Requests\Back\SavePersonRequest');

        // Responses
        $this->app->bind('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\DestroyResponseContract', 'InetStudio\Persons\Http\Responses\Back\Persons\DestroyResponse');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\FormResponseContract', 'InetStudio\Persons\Http\Responses\Back\Persons\FormResponse');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\IndexResponseContract', 'InetStudio\Persons\Http\Responses\Back\Persons\IndexResponse');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\SaveResponseContract', 'InetStudio\Persons\Http\Responses\Back\Persons\SaveResponse');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SlugResponseContract', 'InetStudio\Persons\Http\Responses\Back\Utility\SlugResponse');
        $this->app->bind('InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', 'InetStudio\Persons\Http\Responses\Back\Utility\SuggestionsResponse');

        // Services
        $this->app->bind('InetStudio\Persons\Contracts\Services\Back\PersonsDataTableServiceContract', 'InetStudio\Persons\Services\Back\PersonsDataTableService');
        $this->app->bind('InetStudio\Persons\Contracts\Services\Back\PersonsObserverServiceContract', 'InetStudio\Persons\Services\Back\PersonsObserverService');
        $this->app->bind('InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract', 'InetStudio\Persons\Services\Back\PersonsService');

        // Transformers
        $this->app->bind('InetStudio\Persons\Contracts\Transformers\Back\PersonTransformerContract', 'InetStudio\Persons\Transformers\Back\PersonTransformer');
        $this->app->bind('InetStudio\Persons\Contracts\Transformers\Back\SuggestionTransformerContract', 'InetStudio\Persons\Transformers\Back\SuggestionTransformer');
    }
}
