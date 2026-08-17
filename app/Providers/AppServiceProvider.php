<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade; //add para facilitar componente blade

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
        //facilitador do blade
        Blade::aliasComponent('components.breadcrumbs', 'breadcrumbs');
        Blade::aliasComponent('components.toolbar', 'toolbar');
        Blade::aliasComponent('components.datatables', 'datatables');
        Blade::aliasComponent('components.formgroup', 'formgroup');
    }
}
