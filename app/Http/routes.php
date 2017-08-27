<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return "Will Come Back Soon!";
});

Route::group(['prefix' => 'api'], function () {
    Route::post('/facebook/login', 'Api\AuthenticationController@facebook_login');
    Route::post('/user/login', 'Api\AuthenticationController@login');
    Route::post('/user/register', 'Api\AuthenticationController@register');

    //  BUTT:  I guess its the only  GET Method 

    Route::get('/{id}/tag', 'Api\TagController@user_tags');
    Route::post('/add/tag', 'Api\TagController@add');
    Route::post('/verify/user/tag', 'Api\TagController@verify');
    Route::post('/delete/tag', 'Api\TagController@delete');

    Route::post('/friend/list', 'Api\FriendsController@all');


    // BUTT: Why not  Create a Group Resource Controller ?? with some exceptions 

    Route::post('/group/list', 'Api\GroupController@all');  // BUTT:  index  or an equivalent named route
    Route::post('/add/group/members', 'Api\GroupController@add_group_members');
    Route::post('/add/group', 'Api\GroupController@add');    // 
    Route::post('/group/friend/list', 'Api\GroupController@friend_list');
    Route::post('/delete/group', 'Api\GroupController@delete'); // BUTT: Destroy  with Delete Method ?


    Route::post('/requests/list', 'Api\RequestController@all');
    Route::post('/accept/request', 'Api\RequestController@accept');
    Route::post('/block/request', 'Api\RequestController@block_user');


    //BUTT:  I did a minor code re arrangement , I hope it doesnt break the sys    
    // BUTT:  2 times written -- Corrected !
    Route::post('/search/users', 'Api\SearchController@search');


    // BUTT:  USMAN  write all the related Controllers together
    Route::post('/add/request', 'Api\RequestController@add');
    Route::post('/accept/request', 'Api\RequestController@accept');
    Route::post('/block/request', 'Api\RequestController@block_user');


    Route::post('/new/message', 'Api\MessageController@new_message');
    Route::post('/get/tag/messages', 'Api\MessageController@get_messages');
    Route::post('/delete/message', 'Api\MessageController@delete'); // BUTT: Destroy  with Delete Method ?

    Route::post('/reply', 'Api\MessageReplyController@reply');
    Route::post('/get/reply', 'Api\MessageReplyController@get_reply');

    Route::post('/get/message/detail', 'Api\MessageController@message_detail');

    Route::post('/get/tag/received/messages', 'Api\MessageController@received_message');


    Route::post('/forgot/password', 'Api\AuthenticationController@forgot');
    Route::post('/auth/token', 'Api\AuthenticationController@auth_token');
    Route::post('/reset/password', 'Api\AuthenticationController@reset_password');
    Route::post('/set/permissions', 'Api\TagController@set_permissions');

    Route::any('/submit/file/{id}', 'Api\MessageController@add_file');
    Route::any('/reply/submit/file/{id}', 'Api\MessageReplyController@add_file');

    Route::post('/user/image/{id}', 'Api\UserController@add_image');
    Route::resource('/user', 'Api\UserController');
    Route::resource('/tags', 'Api\TagController');
    Route::post('/add/device/{id}', 'Api\UserController@add_device_id');

});