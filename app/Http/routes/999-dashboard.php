<?php
\Route::group(['namespace' => 'Manage', 'before' => 's5_manage_events', 'middleware' => ['web']], function() {
    \Route::get('/', 'DashboardController@getIndex');
    \Route::get('/front-plugin', 'DashboardController@getFrontPlugin');
    \Route::get('/front-plugin-data', 'DashboardController@getFrontPluginData');
    \Route::get('/updates.json', 'DashboardController@getUpdates');
    \Route::get('/logout', function(){
        \CodeDay\Clear\Models\User::me()->forget();
        return \Redirect::to('https://s5.studentrnd.org/login/logout');
    });
});