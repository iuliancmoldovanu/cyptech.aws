<?php

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');


Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


Route::get( 'reload_session', 'SessionController@reloadSession' );

// Super User
Route::group([ 'prefix' => 'master', 'middleware' => ['master']], function () { // authenticated Super Admin users
    Route::get( 'visitors', 'VisitorsController@index' );
    Route::get( 'visitors_table', 'VisitorsController@table' );
    /*
     * show laravel.log
     */
    Route::get( 'syslog/clear', 'MasterController@clearLog');
    Route::get('syslog/{index?}', 'MasterController@readLog');

    Route::get('phpinfo', function(){ phpinfo(); });

    Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index');
});

// Admin
Route::group(['middleware' => ['admin', 'activity']], function () { // authenticated Admin users
    Route::get('admin/users', 'UserController@getIndex');
    Route::get('admin/users/index/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@getIndex');
    Route::get('admin/users/list', 'UserController@getList');
    Route::get('admin/users/list/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@getList');
    Route::get('admin/users/create/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@getCreate');
    Route::post('admin/users/create/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@postCreate');
    Route::get('admin/users/update/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@getUpdate');
    Route::post('admin/users/update/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@postUpdate');
    Route::get('admin/users/delete/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@getDelete');
    Route::get('admin/users/active/{one?}/{two?}/{three?}/{four?}/{five?}', 'UserController@getActive');

    Route::get('admin/players', 'AdminController@getPlayers');
    Route::get('admin/players/list', 'AdminController@getPlayersList');

    Route::post('admin/player/update/{id}', 'AdminController@updatePlayerDetails');
    Route::get('admin/player/update/{id}', 'AdminController@getPlayerDetails');

    Route::post('admin/player/create/post', 'AdminController@createNewPlayer');
    Route::get('admin/player/create', 'AdminController@getCreatePlayer');

    Route::get('admin/player/disable/{id}', 'AdminController@setDisablePlayer');
    Route::get('admin/player/active/{id}', 'AdminController@setActivePlayer');

    Route::get('admin/games', 'AdminController@games');
    Route::post('admin/update_game', 'AdminController@update_game');
    Route::post('admin/complete_game', 'AdminController@complete_game');
    Route::get('admin/cancel_teams', 'AdminController@cancel_teams');
    Route::get('admin/cancel_game', 'AdminController@cancel_game');

    Route::get('ranking', 'PlayerController@getPlayersRanking');
    Route::get('players/list', 'PlayerController@getPlayersList');
    Route::post('suspend', 'PlayerController@suspendPlayer');
    Route::post('delete', 'PlayerController@deletePlayer');
});


Route::group(['middleware' => ['auth', 'activity']], function () { // authenticated Admin users
    Route::get('/', 'AppController@isUserLogged');
    Route::get('/dashboard', 'AppController@dashboard');
    Route::get('/profile', 'AppController@profile');
    Route::patch('/user/update_profile', 'UserController@updateProfile');
    Route::patch('/user/update_password', 'UserController@updatePassword');
    Route::get('/user/confirm', 'AppController@confirm');
    Route::get('/user/unavailable/{weeks}', 'AppController@unavailable');
    //Route::get( 'ranking', 'AppController@getRanking' );
    Route::get('/users/list', 'AppController@getList');
    Route::get('/games', 'AppController@games');
    Route::get('/generate_teams', 'AppController@generate_teams');

    // Email
    Route::get ( '/send', 'MailController@send' );
    Route::get ( '/result_games', 'PlayerController@resultGames' );
    Route::get ( '/result_games/table/{player}', 'PlayerController@tableResultPlayer' );
});

