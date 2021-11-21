<?php

namespace App\Providers;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Builder::macro('search', function ($field, $string){
            return $string ? $this->where($field, 'LIKE', '%'. $string . '%') : $this;
        });

        Schema::defaultStringLength(191);

        Blade::if('permission', function ($permission) {
            $permission = explode('.', $permission);
            return permission_check($permission[0], $permission[1]);
        });
    }
}
