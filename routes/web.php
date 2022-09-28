<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes(['register' => false]);



Route::get('/home', [App\Http\Controllers\HomeController::class , 'index'])
->name('home');


Route::group(['middleware' => ['auth','adm']], function () {
    
    Route::resource('modules', '\App\Http\Controllers\ModuleController');
    Route::delete('/modules_bath', '\App\Http\Controllers\ModuleController@destroyBath')->name('modules_bath.destroy');
    Route::get('getModulesAjax','\App\Http\Controllers\ModuleController@getModulesAjax');
    Route::resource('disciplinas', '\App\Http\Controllers\DisciplinaController');
    Route::delete('/disciplinas_bath', '\App\Http\Controllers\DisciplinaController@destroyBath')->name('disciplinas_bath.destroy');
    Route::get('getDisciplinasAjax','\App\Http\Controllers\DisciplinaController@getDisciplinasAjax');
    Route::resource('restrictions', '\App\Http\Controllers\RestrictionController');
    Route::delete('/restrictions_bath', '\App\Http\Controllers\RestrictionController@destroyBath')->name('restrictions_bath.destroy');
    Route::resource('aulas', '\App\Http\Controllers\AulaController');
    Route::delete('/aulas_bath', '\App\Http\Controllers\AulaController@destroyBath')->name('aulas_bath.destroy');
    Route::get('getAulasAjax','\App\Http\Controllers\AulaController@getAulasAjax');
    Route::resource('teachers', '\App\Http\Controllers\TeacherController');
    Route::delete('/teachers_bath', '\App\Http\Controllers\TeacherController@destroyBath')->name('teachers_bath.destroy');
    Route::resource('students', '\App\Http\Controllers\StudentController');
    Route::get('getStudentsAjax','\App\Http\Controllers\StudentController@getStudentsAjax');
    Route::delete('/students_bath', '\App\Http\Controllers\StudentController@destroyBath')->name('students_bath.destroy');
    Route::resource('administrators', '\App\Http\Controllers\AdministratorController');
    Route::delete('/administrators_bath', '\App\Http\Controllers\AdministratorController@destroyBath')->name('administrators_bath.destroy');
    Route::resource('horarios', '\App\Http\Controllers\HorarioController');
    Route::delete('/horarios_bath', '\App\Http\Controllers\HorarioController@destroyBath')->name('horarios_bath.destroy');
    Route::get('credits/{student_id}', '\App\Http\Controllers\CreditController@getCredits');
    Route::post('credits', '\App\Http\Controllers\CreditController@store')->name('credits.store');
    Route::get('celulas', '\App\Http\Controllers\CelulaController@index')->name('celulas.index');   
    Route::get('getEventsCelula', '\App\Http\Controllers\CelulaController@getEventsCelula');    
    Route::get('celulas/{celula}', '\App\Http\Controllers\CelulaController@show')->name('celulas.show');
    Route::post('celulasBath', '\App\Http\Controllers\CelulaController@celulasBath')->name('celulasBath.store');    
    Route::post('celulas', '\App\Http\Controllers\CelulaController@store')->name('celulas.store');    
    Route::delete('celulas/{celula}', '\App\Http\Controllers\CelulaController@destroy')->name('celulas.destroy');
    Route::post('celulas/storeStudent','\App\Http\Controllers\CelulaController@storeStudent');
    Route::get('configurations',[App\Http\Controllers\ConfigurationController::class , 'index'])->name('configurations.index');
    Route::post('confgurations',[App\Http\Controllers\ConfigurationController::class , 'save'])->name('configurations.save');
});


Route::group(['middleware' => ['auth']], function () {
    Route::get('/agenda', [App\Http\Controllers\AgendaController::class , 'index'])->name('agenda.index');
    Route::get('/getEventsAgenda', [App\Http\Controllers\AgendaController::class , 'getEventsAgenda']);
    Route::get('/getEventsAgendados', [App\Http\Controllers\AgendaController::class , 'getEventsAgendados']);
    Route::get('/getDadosToAgenda', [App\Http\Controllers\AgendaController::class , 'getDadosToAgenda']);
    Route::post('/agenda', [App\Http\Controllers\AgendaController::class , 'store'])->name('agenda.store');
    //Route::get('/teste',[App\Http\Controllers\AgendaController::class , 'teste']);
    Route::get('/agendados', [App\Http\Controllers\AgendadosController::class , 'index'])->name('agendados.index');
    Route::get('/aulasToAgenda', [App\Http\Controllers\AgendaController::class , 'aulasToAgenda']);
    Route::delete('agendados/{celula}/desmarcar', '\App\Http\Controllers\AgendadosController@desmarcar')->name('agendados.desmarcar');
    Route::get('perfil/changePassword','\App\Http\Controllers\PerfilController@showChangePassword')->name('changePassword.show');
    Route::post('perfil/changePassword','\App\Http\Controllers\PerfilController@updatePassword')->name('changePassword.update');
    Route::get('getAuthStudent','\App\Http\Controllers\PerfilController@getAuthStudent');
    Route::get('gradeEscola','\App\Http\Controllers\GradeController@index')->name('gradeEscola.index');
    Route::get('getEventsGrade', '\App\Http\Controllers\GradeController@getEventsCelula');
    Route::get('getCelula/{celula}', '\App\Http\Controllers\GradeController@getCelula');
});