<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $models = [
            'Auth',
            'User',
            'Validation',
            'SendEmail',
            'Car',
            'Booking'
        ];

        foreach ($models as $model) {
            if (class_exists("DDD\Infrastructure\Repositories\EloquentRepositories\\{$model}\\{$model}Repository")) {
                $this->app->bind(
                    "DDD\Domain\Repository\\{$model}RepositoryInterface",
                    "DDD\Infrastructure\Repositories\EloquentRepositories\\{$model}\\{$model}Repository"
                );
            } elseif (class_exists("DDD\Infrastructure\Repositories\SQLServerRepositories\\{$model}\\{$model}Repository")) {
                $this->app->bind(
                    "DDD\Domain\Repository\\{$model}RepositoryInterface",
                    "DDD\Infrastructure\Repositories\SQLServerRepositories\\{$model}\\{$model}Repository"
                );
            } elseif (class_exists("DDD\Infrastructure\Services\\{$model}Service")) {
                $this->app->bind(
                    "DDD\Domain\Services\\{$model}ServiceInterface",
                    "DDD\Infrastructure\Services\\{$model}Service"
                );
            }
        }
    }
}
