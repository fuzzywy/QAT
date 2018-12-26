<?php

namespace App\Providers;

use App\Http\Controllers\GsmQueryHandler;
use App\Http\Controllers\LteFddQueryHandler;
use App\Http\Controllers\LteTddQueryHandler;
use App\Http\Controllers\NbiotQueryHandler;
use App\Http\Controllers\NbmQueryHandler;
use Illuminate\Support\ServiceProvider;

class QueryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('query_tdd', new LteTddQueryHandler());
        $this->app->bind('query_fdd', new LteFddQueryHandler());
        $this->app->bind('query_nbiot', new NbiotQueryHandler());
        $this->app->bind('query_gsm', new GsmQueryHandler());
        $this->app->bind('query_nbm', new NbmQueryHandler());
    }
}
