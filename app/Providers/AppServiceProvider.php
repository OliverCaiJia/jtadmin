<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\Logger\SLogger;
use DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 定义正式环境
        define("PRODUCTION_ENV", $this->app->environment('production'));
        // 执行SQL
        DB::listen(function ($query) {
            if (!$this->app->environment('production')) {
                $sql = str_replace("?", "'%s'", $query->sql);
                $log = vsprintf($sql, $query->bindings);
                SLogger::getStream()->info($log);
                SLogger::getStream()->info($query->time);
            }
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (!$this->app->environment('production')) {
            $this->app->register(\Asvae\ApiTester\ServiceProvider::class);
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
