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

Route::get('/','Site\HomeController@index');// parte frontal do meu sistema

Route::prefix('painel')->group(function(){ // parte traseira do meu sistema
    Route::get('/','Admin\HomeController@index')->name('admin');

    Route::get('login','Admin\Auth\LoginController@index')->name('login');
    Route::post('login','Admin\Auth\LoginController@authenticate');// vai ser a action que vai receber os dados do post do login e vai fazer a autentificcao

    Route::get('register','Admin\Auth\RegisterController@index')->name('register');
    Route::post('register','Admin\Auth\RegisterController@register');// vai receber o post do registro

    Route::post('logout','Admin\Auth\LoginController@logout')->name('logout');


    Route::resource('users','Admin\UsersController');// criei o crud de usuarios
    Route::resource('pages','Admin\PageController');// cria um crud

    Route::get('profile','Admin\ProfileController@index')->name('profile');// nome da rota direcionar e metodo
    Route::put('profilesave','Admin\ProfileController@save')->name('profile.save');

    Route::get('settings','Admin\SettingController@index')->name('settings');
    Route::put('settingssave','Admin\SettingController@save')->name('settings.save');




});
