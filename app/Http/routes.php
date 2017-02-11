<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('users/user',['user'=>'Thong is the boss']);
});
//Route::get('/user/{id}','UserController@showProfile');
//Route::get('/insert/{email}','UserController@insertUser');




/* auth controller group */

Route::group(['namespace' => 'Auth'],function (){
    Route::get('auth/login','AuthController@login')->name('login');
    Route::post('auth/login','AuthController@login')->name('login');
    Route::get('auth/register','AuthController@register')->name('register');
    Route::post('auth/register','AuthController@register')->name('register');
    Route::post('auth/validate','AuthController@validateRegister')->name('validate');
    Route::get('auth/logout','AuthController@logout')->name('logout');
});
/* create data group */
Route::group(['namespace' => 'Admin\CreateData'],function (){
    Route::get('admin/song-index','SongDetailController@index')->name('get_song');
    Route::post('admin/song-index','SongDetailController@index')->name('post_song');
});
/* news controller group */
Route::group(['namespace' => 'Admin\Setting'],function (){
    //setting SubtitleType
    Route::get('admin/subtitletype','SubtitleTypeController@index')->name('get_subtitle_type');
    Route::post('admin/subtitletype','SubtitleTypeController@index')->name('post_subtitle_type');
    //setting language
    Route::get('admin/language','LanguageController@index')->name('get_language');
    Route::post('admin/language','LanguageController@index')->name('post_language');
    //setting category
    Route::get('admin/category','CategoryController@index')->name('get_category');
    Route::post('admin/category','CategoryController@index')->name('post_category');
    //setting ProviderPayment
    Route::get('admin/provider-payment','ProviderPaymentController@index')->name('get_provider_payment');
    Route::post('admin/provider-payment','ProviderPaymentController@index')->name('post_provider_payment');
    //setting role
    Route::get('admin/role','RoleController@index')->name('get_role');
    Route::post('admin/role','RoleController@index')->name('post_role');
    //setting subscribe type
    Route::get('admin/subscribe-type','SubscribeTypeController@index')->name('get_subscribe_type');
    Route::post('admin/subscribe-type','SubscribeTypeController@index')->name('post_subscribe_type');
    //setting singer
    Route::get('admin/singer','SingerController@index')->name('get_singer');
    Route::post('admin/singer','SingerController@index')->name('post_singer');
    //setting default image
    Route::get('admin/default-image','DefaultImageController@index')->name('get_default_image');
    Route::post('admin/default-image','DefaultImageController@index')->name('post_default_image');


});
/* news controller group */

//Route::group(['namespace' => 'Admin'],function (){
//
//    /* admin route */
//        //setting role
//        Route::get('admin/createRole','SettingController@createRole')->name('get_createRole');
//        Route::post('admin/createRole','SettingController@createRole')->name('createRole');
//        //setting category
//        Route::get('admin/category','CategoryController@index')->name('get_category');
//        Route::post('admin/category','CategoryController@index')->name('post_category');
//
//        Route::get('admin','AdminController@index');
//        Route::post('admin/login','AdminController@login')->name('admin');
//        Route::get('admin/login','AdminController@login')->name('admin');
//
//        Route::get('admin/newssetting','AdminNewsController@index');
//        Route::get('admin/listnews','AdminNewsController@listNewsmedia');
//
//        Route::get('admin/addnews','AdminNewsController@addNewspaper');
//        Route::post('admin/addnews','AdminNewsController@addNewspaper')->name('addNewspaper');
//
//
//        Route::get('admin/addnewcategory','AdminNewsController@addNewCategory');
//        Route::post('admin/addnewcategory','AdminNewsController@addNewCategory')->name('addNewCategory');
//
//
//        Route::get('admin/addnewsmedia','AdminNewsController@addNewsMedia');
//        Route::post('admin/addnewsmedia','AdminNewsController@addNewsMedia')->name('addNewsMedia');
//
//        /* social media controller */
//
//
//        Route::get('admin/socialmedia','AdminSocialMediaController@index')->name('list_social');
//        Route::get('admin/listsocial','AdminController@listsocial');
//        Route::get('admin/addsocial','AdminController@addsocial');
//        Route::get('admin/socialsetting','AdminController@socialsetting');
//
//        Route::get('admin/addnewsocial','AdminController@addnewsocial');
//        Route::post('admin/addnewsocial','AdminController@addnewsocial')->name('addnewsocial');
//
//
//        Route::get('admin/addnewfanpage','AdminController@addnewfanpage');
//        Route::post('admin/addnewfanpage','AdminController@addnewfanpage')->name('addnewfanpage');
//
//        Route::get('admin/addnewsocialmedia','AdminSocialMediaController@addNewSocialMedia');
//        Route::post('admin/addnewsocialmedia','AdminSocialMediaController@addNewSocialMedia')->name('addNewSocialMedia');
//
//});



Route::get('api/news/getnews/{page}','Api\v1\NewsController@getNews')->where('page','[0-9]+');

Route::get('api/news/getsocial/{page}','Api\v1\SocialController@getSocial')->where('page','[0-9]+');









Route::auth();

Route::get('/home', 'HomeController@index');
