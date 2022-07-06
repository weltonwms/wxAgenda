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

//Auth::routes(['register' => false]);



Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('modules', '\App\Http\Controllers\ModuleController');
Route::delete('/modules_bath','\App\Http\Controllers\ModuleController@destroyBath' )->name('modules_bath.destroy');

Route::resource('disciplinas', '\App\Http\Controllers\DisciplinaController');
Route::delete('/disciplinas_bath','\App\Http\Controllers\DisciplinaController@destroyBath' )->name('disciplinas_bath.destroy');
                            
Route::resource('restrictions', '\App\Http\Controllers\RestrictionController'); 
Route::delete('/restrictions_bath','\App\Http\Controllers\RestrictionController@destroyBath' )->name('restrictions_bath.destroy');

Route::resource('aulas', '\App\Http\Controllers\AulaController'); 
Route::delete('/aulas_bath','\App\Http\Controllers\AulaController@destroyBath' )->name('aulas_bath.destroy');   

Route::resource('teachers', '\App\Http\Controllers\TeacherController'); 
Route::delete('/teachers_bath','\App\Http\Controllers\TeacherController@destroyBath' )->name('teachers_bath.destroy');

Route::resource('students', '\App\Http\Controllers\StudentController'); 
Route::delete('/students_bath','\App\Http\Controllers\StudentController@destroyBath' )->name('students_bath.destroy');

Route::resource('administrators', '\App\Http\Controllers\AdministratorController'); 
Route::delete('/administrators_bath','\App\Http\Controllers\AdministratorController@destroyBath' )->name('administrators_bath.destroy');

Route::resource('horarios', '\App\Http\Controllers\HorarioController'); 
Route::delete('/horarios_bath','\App\Http\Controllers\HorarioController@destroyBath' )->name('horarios_bath.destroy');

Route::get('credits/{student_id}','\App\Http\Controllers\CreditController@getCredits');

Route::post('credits','\App\Http\Controllers\CreditController@store')->name('credits.store');