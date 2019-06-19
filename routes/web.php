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

Route::get('users/profile', 'UserController@profile')->name('users.profile')->middleware('auth');
Route::get('login/{provider}', 'Auth\SocialLoginController@redirectToProvider')->name('user.login.provider');
Route::get('login/{provider}/callback', 'Auth\SocialLoginController@handleProviderCallback');

Route::namespace("\App\Demand")
        ->middleware(['auth'])
        ->name('demands.')
        ->group(function () {
            Route::post('/demands', 'DemandController@store')->name('post');
            Route::post('/demands/{demand}/apply', 'DemandController@apply')->name('apply');
        });

Route::namespace("\App\Plan")
        ->middleware(['auth'])
        ->name('plans.')
        ->group(function () {
            Route::get('plans', 'PlanController@index')->name('index');
            Route::get('plans/{slug}', 'PlanController@show')->name('show');
            Route::post('/plans/subscribe', 'PlanController@subscribe')->name('subscribe');
        });

// Route::post('stripe/webhook', 'WebHookController@handleWebhook');

Route::get('/home', 'HomeController@index')->name('home');
