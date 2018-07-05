<?php
use App\Models\Album;
use App\Models\Photo;
use App\User;
use Illuminate\Support\Facades\Auth;
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



Route::redirect('/home', '/');



// Possiamo raggruppare le rotte in gruppi per dare dei parametri comuni o fissare caratteristiche comuni
Route::group(
    [
        'middleware' => 'auth',
        'prefix' => 'dashboard'
    ],
    function (){

    // ALBUMS

    //Route::get('/', 'AlbumsController@index')->name('albums');
    //Route::get('/home', 'AlbumsController@index')->name('albums');
    Route::get('/albums/create', 'AlbumsController@create')->name('album.create');
    Route::get('/albums', 'AlbumsController@index')->name('albums');
    Route::delete('/albums/{id}', 'AlbumsController@delete')->where('id', '[0-9]+')->name('album.delete');
    Route::get('/albums/{id}', 'AlbumsController@show')->where('id', '[0-9]+');
    Route::get('/albums/{id}/edit', 'AlbumsController@edit')->name('album.edit');
//Route::post('/albums/{id}', 'AlbumsController@store');
    Route::patch('/albums/{id}', 'AlbumsController@store')->name('album.store');
    Route::post('/albums', 'AlbumsController@save')->name('album.save');

    Route::get('/albums/{id}/images', 'AlbumsController@showImages')->name('album.showimages')
        ->where('id', '[0-9]+');





    // Immagini. Creando la route con resource e avendo un resource controller vengono effettuate in automatico
    // le chiamate ai metodi.
    Route::resource('photos', 'PhotosController');


});

// Gallery pubblica
Route::group(
    [
        'prefix' => 'gallery'
    ],
    function (){
        Route::get('/', 'GalleryController@index')->name('gallery');
        Route::get('/album/{album}/photos', 'GalleryController@showAlbumPhotos')->where('album', '[0-9]+')->name('gallery.albumphotos');
        Route::get('/category/{id}', 'GalleryController@showCategoryAlbums')->name('gallery.category');
});





Auth::routes();

Route::get('/', 'GalleryController@index')->name('home');




// PROVA MAIL

Route::get('ProvaMail', function (){
    $user = User::get()->first();
    Mail::to(Auth::user()->getEmailForPasswordReset())->send(new \App\Mail\MailProvaMd($user));
});
//Route::view('ProvaMail', 'email.mailprova');


// COMMENTI
Route::resource('comments', 'CommentsController')->except(['index'])->middleware('auth');
Route::get('/comments/{comment}', 'CommentsController@index')->middleware('auth')->name('comments.index');
Route::get('/comments/{comment}/show', 'CommentsController@showComment')->middleware('auth')->name('comments.showbyid');