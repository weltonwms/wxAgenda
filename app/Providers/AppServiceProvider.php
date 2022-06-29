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
        \Form::component('bsText', 'components.form.text', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsNumber', 'components.form.number', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsDate', 'components.form.date', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsTime', 'components.form.time', ['name', 'value' => null, 'attributes' => []]);
        \Form::component('bsPassword', 'components.form.password', ['name',  'attributes' => []]);
        \Form::component('bsSelect', 'components.form.select', ['name', 'list'=>[],'value'=>null, 'attributes' => []]);
        \Form::component('bsYesno', 'components.form.yesno',['name','default'=>null] );
    }
}
