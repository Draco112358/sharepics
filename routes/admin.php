<?php
/**
 * Created by PhpStorm.
 * User: gianni
 * Date: 17/04/18
 * Time: 10.01
 */

Route::resource('users', 'Admin\UsersController');
Route::get('/users/{user}/restore', 'Admin\UsersController@restore')->name('users.restore');

Route::get('/', 'Admin\DashboardController@index')->name('adminpanel');

//Route::get('/dashboard', function(){
 //   return "Admin Dashboard";
//});

Route::resource('categories', 'Admin\CategoryController')->except(['update']);
Route::patch('/categories/update', 'Admin\CategoryController@update')->name('categories.update');
Route::get('/categories/{category}/restore', 'Admin\CategoryController@restore')->name('categories.restore');

Route::get('/albums', 'AlbumsController@getAll')->name('albums.list');
Route::get('/albums/{id}/edit', 'AlbumsController@edit')->name('albums.modify');

Route::get('/photos', 'PhotosController@index')->name('photos.list');
Route::get('/photos/{photo}/edit', 'PhotosController@edit')->name('adminphotos.edit');


Route::get('/comments', 'CommentsController@getAll')->name('comments.list');