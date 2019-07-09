<?php

use Metko\Galera\Facades\Galera;

Route::get('/', function () {
    //Galera::participants(1, 2)->subject('Test conversation')->description('This is a new conversation')->make();

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
            Route::get('users/profile/{user?}', 'UserController@profile')->name('profile');
            Route::post('users/{user}/edit', 'UserController@update')->name('update');
            Route::get('users/{user}/edit', 'UserController@edit')->name('edit');
            Route::get('users/{user}/edit/password', 'UserController@editPassword')->name('edit.password');
            Route::post('users/{user}/edit/password', 'UserController@updatePassword')->name('update.password');
            Route::get('users/{user}/{token}/restore', 'UserController@restore')->name('restore');
            Route::delete('users/{user}/suspend', 'UserController@suspendAccount')->name('suspend');

            Route::get('users/notifications', 'UserController@notifications')->name('notifications');
            Route::post('users/notifications/read', 'UserController@readNotifications')->name('notifications.read');
        });

Route::namespace('Demand')
        ->middleware(['auth'])
        ->name('demands.')
        ->group(function () {
            Route::get('/demands', 'DemandController@index')->name('index');
            Route::get('/demands/{demand}', 'DemandController@show')->name('show');
            Route::post('/demands', 'DemandController@store')->name('post');
            Route::get('/demands/{demand}/apply', 'DemandController@showApply')->name('apply.show');
            Route::post('/demands/{demand}/apply', 'DemandController@apply')->name('apply');
            Route::post('/demands/{demand}/contracted', 'DemandController@contracted')->name('contracted');
            Route::post('/demands/{demand}/contract/{candidature}', 'DemandController@contractCandidature')->name('contract.candidature');
            Route::patch('/demands/{demand}', 'DemandController@update')->name('update');
            Route::post('/demands/{demand}/contact/{userCandidature}', 'DemandController@contactCandidature')->name('contact');

            Route::delete('/demands/{demand}/delete', 'DemandController@delete')->name('delete');
            Route::post('/demands/{id}/restore', 'DemandController@restore')->name('restore');
            Route::delete('/demands/{demand}/destroy', 'DemandController@destroy')->name('destroy');
        });

Route::namespace('Contract')
    ->middleware(['auth'])
    ->name('contracts.')
    ->group(function () {
        Route::get('dashboard/contracts', 'ContractController@index')->name('index');
        Route::get('dashboard/contracts/{contract}', 'ContractController@show')->name('show');
        Route::post('dashboard/contracts/{contract}/store/{conversation}', 'ContractController@storeMessage')->name('store.message');
        Route::post('dashboard/contracts/{contract}/settings', 'ContractController@storeSettings')->name('propose-settings');
        Route::post('dashboard/contracts/{contract}', 'ContractController@validateContract')->name('validate');
        Route::delete('dashboard/contracts/{contract}', 'ContractController@cancel')->name('cancel');
    });

Route::namespace('Plan')
        ->middleware(['auth'])
        ->name('plans.')
        ->group(function () {
            Route::get('plans', 'PlanController@index')->name('index');
            Route::get('plans/{slug}', 'PlanController@show')->name('show');
            Route::post('/plans/subscribe', 'PlanController@subscribe')->name('subscribe');
        });

Route::namespace('Dashboard')
        ->middleware(['auth'])
        ->name('dashboard.')
        ->group(function () {
            Route::get('dashboard', 'DashboardController@index')->name('index');
            Route::get('dashboard/demands', 'DashboardController@demands')->name('demands');
            Route::get('dashboard/profile', 'DashboardController@demands')->name('profile');

            Route::get('dashboard/inbox', '\App\Conversation\ConversationController@index')->name('inbox');

            Route::get('dashboard/inbox/thread/{conversationId}', '\App\Conversation\ConversationController@show')->name('conversations.show');
            Route::post('dashboard/inbox/thread/{conversationId}', '\App\Message\MessageController@store')->name('messages.store');
        });

// Route::post('stripe/webhook', 'WebHookController@handleWebhook');

Route::get('/home', '\App\Http\Controllers\HomeController@index')->name('home');
