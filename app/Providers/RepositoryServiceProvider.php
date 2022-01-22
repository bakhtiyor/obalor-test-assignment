<?php

namespace App\Providers;

use App\Repository\Eloquent\MigrateCustomerRepository;
use App\Repository\MigrateCustomerRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MigrateCustomerRepositoryInterface::class, MigrateCustomerRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
