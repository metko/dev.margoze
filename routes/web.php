<?php

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
    return view('welcome');
});

Auth::routes(['verify' => true]);
Route::get('users/profile', 'UserController@profile')->name('users.profile');
Route::get('login/{provider}', 'Auth\SocialLoginController@redirectToProvider')->name('user.login.provider');
Route::get('login/{provider}/callback', 'Auth\SocialLoginController@handleProviderCallback');

Route::get('plans', 'PlanController@index')->name('plans.index');
Route::get('plans/{slug}', 'PlanController@show')->name('plans.show');
Route::post('/plans/subscribe', 'PlanController@subscribe')->name('plans.subscribe');
Route::post('stripe/webhook', 'WebHookController@handleWebhook');

Route::get('/home', 'HomeController@index')->name('home');
