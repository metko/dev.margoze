<?php

Route::get('/', function () {
    return view('welcome');
});

Route::get('/eventtest', function () {
    return 'ok';
});

Auth::routes(['verify' => true]);

Route::namespace('Auth')
        ->name('auth.provider.')
        ->group(function () {
            Route::get('login/{provider}', 'SocialLoginController@redirectToProvider')->name('login');
            Route::get('login/{provider}/callback', 'SocialLoginController@handleProviderCallback');
        });

Route::namespace('User')
        ->middleware(['auth'])
        ->name('users.')
        ->group(function () {
            Route::get('users/profile', 'UserController@profile')->name('profile');
        });

Route::namespace('Demand')
        ->middleware(['auth'])
        ->name('demands.')
        ->group(function () {
            Route::post('/demands', 'DemandController@store')->name('post');
            Route::post('/demands/{demand}/apply', 'DemandController@apply')->name('apply');
            Route::post('/demands/{demand}/contracted', 'DemandController@contracted')->name('contracted');
            Route::patch('/demands/{demand}', 'DemandController@update')->name('update');

            Route::delete('/demands/{demand}/delete', 'DemandController@delete')->name('delete');
            Route::post('/demands/{id}/restore', 'DemandController@restore')->name('restore');
            Route::delete('/demands/{demand}/destroy', 'DemandController@destroy')->name('destroy');
        });

Route::namespace('Plan')
        ->middleware(['auth'])
        ->name('plans.')
        ->group(function () {
            Route::get('plans', 'PlanController@index')->name('index');
            Route::get('plans/{slug}', 'PlanController@show')->name('show');
            Route::post('/plans/subscribe', 'PlanController@subscribe')->name('subscribe');
        });

// Route::post('stripe/webhook', 'WebHookController@handleWebhook');

Route::get('/home', '\App\Http\Controllers\HomeController@index')->name('home');
