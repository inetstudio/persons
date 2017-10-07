<?php

namespace InetStudio\Experts;

use Illuminate\Support\ServiceProvider;

class ExpertsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin.module.experts');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->publishes([
            __DIR__.'/../config/experts.php' => config_path('experts.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/../config/filesystems.php', 'filesystems.disks'
        );

        if ($this->app->runningInConsole()) {
            if (! class_exists('CreateExpertsTables')) {
                $timestamp = date('Y_m_d_His', time());
                $this->publishes([
                    __DIR__.'/../database/migrations/create_experts_tables.php.stub' => database_path('migrations/'.$timestamp.'_create_experts_tables.php'),
                ], 'migrations');
            }

            $this->commands([
                Commands\SetupCommand::class,
                Commands\CreateFoldersCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
