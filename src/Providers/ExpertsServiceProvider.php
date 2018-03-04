<?php

namespace InetStudio\Experts\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class ExpertsServiceProvider.
 */
class ExpertsServiceProvider extends ServiceProvider
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
                'InetStudio\Experts\Console\Commands\SetupCommand',
                'InetStudio\Experts\Console\Commands\CreateFoldersCommand',
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
            __DIR__.'/../../config/experts.php' => config_path('experts.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateExpertsTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../../database/migrations/create_experts_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_experts_tables.php'),
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
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'admin.module.experts');
    }

    /**
     * Регистрация наблюдателей.
     *
     * @return void
     */
    protected function registerObservers(): void
    {
        $this->app->make('InetStudio\Experts\Contracts\Models\ExpertModelContract')::observe($this->app->make('InetStudio\Experts\Contracts\Observers\ExpertObserverContract'));
    }

    /**
     * Регистрация привязок, алиасов и сторонних провайдеров сервисов.
     *
     * @return void
     */
    protected function registerBindings(): void
    {
        // Controllers
        $this->app->bind('InetStudio\Experts\Contracts\Http\Controllers\Back\ExpertsControllerContract', 'InetStudio\Experts\Http\Controllers\Back\ExpertsController');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Controllers\Back\ExpertsDataControllerContract', 'InetStudio\Experts\Http\Controllers\Back\ExpertsDataController');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Controllers\Back\ExpertsUtilityControllerContract', 'InetStudio\Experts\Http\Controllers\Back\ExpertsUtilityController');

        // Events
        $this->app->bind('InetStudio\Experts\Contracts\Events\Back\ModifyExpertEventContract', 'InetStudio\Experts\Events\Back\ModifyExpertEvent');

        // Models
        $this->app->bind('InetStudio\Experts\Contracts\Models\ExpertModelContract', 'InetStudio\Experts\Models\ExpertModel');

        // Observers
        $this->app->bind('InetStudio\Experts\Contracts\Observers\ExpertObserverContract', 'InetStudio\Experts\Observers\ExpertObserver');

        // Repositories
        $this->app->bind('InetStudio\Experts\Contracts\Repositories\ExpertsRepositoryContract', 'InetStudio\Experts\Repositories\ExpertsRepository');

        // Requests
        $this->app->bind('InetStudio\Experts\Contracts\Http\Requests\Back\SaveExpertRequestContract', 'InetStudio\Experts\Http\Requests\Back\SaveExpertRequest');

        // Responses
        $this->app->bind('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\DestroyResponseContract', 'InetStudio\Experts\Http\Responses\Back\Experts\DestroyResponse');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\FormResponseContract', 'InetStudio\Experts\Http\Responses\Back\Experts\FormResponse');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\IndexResponseContract', 'InetStudio\Experts\Http\Responses\Back\Experts\IndexResponse');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\SaveResponseContract', 'InetStudio\Experts\Http\Responses\Back\Experts\SaveResponse');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Responses\Back\Utility\SlugResponseContract', 'InetStudio\Experts\Http\Responses\Back\Utility\SlugResponse');
        $this->app->bind('InetStudio\Experts\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', 'InetStudio\Experts\Http\Responses\Back\Utility\SuggestionsResponse');

        // Services
        $this->app->bind('InetStudio\Experts\Contracts\Services\Back\ExpertsDataTableServiceContract', 'InetStudio\Experts\Services\Back\ExpertsDataTableService');
        $this->app->bind('InetStudio\Experts\Contracts\Services\Back\ExpertsObserverServiceContract', 'InetStudio\Experts\Services\Back\ExpertsObserverService');
        $this->app->bind('InetStudio\Experts\Contracts\Services\Back\ExpertsServiceContract', 'InetStudio\Experts\Services\Back\ExpertsService');

        // Transformers
        $this->app->bind('InetStudio\Experts\Contracts\Transformers\Back\ExpertTransformerContract', 'InetStudio\Experts\Transformers\Back\ExpertTransformer');
        $this->app->bind('InetStudio\Experts\Contracts\Transformers\Back\SuggestionTransformerContract', 'InetStudio\Experts\Transformers\Back\SuggestionTransformer');
    }    
}
