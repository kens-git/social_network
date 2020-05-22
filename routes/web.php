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

Route::get('/login', 'AuthController@getLogin')->name('login');

Route::post('/login', 'AuthController@postLogin');

Route::middleware(['auth'])->group(function() {

    Route::get('/activity', 'ActivityController@getActivity')->name('activity');

    Route::get('/albums/{username?}', 'AlbumController@getAlbums')->name('albums');

    Route::post('/albums', 'AlbumController@postAlbum');

    Route::get('/albums/{username}/{id}', 'AlbumController@getAlbumsView')->name('albums.view');

    Route::get('/albums/{username}/{id}/{file_id}/preview', 'AlbumController@getAlbumFilePreview')
        ->name('albums.file_preview');

    Route::get('albums/{username}/{id}/{file_id}/view', 'AlbumController@getAlbumFileView')
        ->name('albums.file.view');

    Route::get('/albums/{username}/{id}/{file_id}', 'AlbumController@getAlbumFile')
        ->name('albums.file');

    Route::post('albums/{username}/{id}/{file_id}', 'AlbumController@postAlbumFileView')
        ->name('post.file.view');

    Route::get('/albums/{username}/{id}/{file_id}/full_size', 'AlbumController@getFullSizeAlbumFile')
        ->name('albums.file.full_size');

    Route::get('/messages/{username?}', 'MessageController@getMessages')->name('messages');

    Route::post('messages/{username?}', 'MessageController@postMessage');

    Route::get('/create', 'AuthController@getCreate')->name('create');

    Route::post('/create', 'AuthController@postCreate');

    Route::get('/logout', 'AuthController@logout')->name('logout');

    Route::get('/edit', 'ProfileController@getEditProfile')
        ->name('profile.edit');

    Route::post('/edit', 'ProfileController@postEditProfile');

    Route::get('/directory', 'DirectoryController@getDirectory')->name('directory');

    Route::get('/todo', 'TodoController@getIndex')->name('todo');

    Route::post('/todo', 'TodoController@postIndex');

    Route::get('/profile_photo/{id}', 'ProfileController@getProfilePhoto')->name('profile_photo');

    Route::get('/cover_photo/{id}', 'ProfileController@getCoverPhoto')->name('cover_photo');

    Route::get('/set_profile_photo/{id}', 'ProfileController@setProfilePhoto')->name('set_profile_photo');

    Route::get('/set_cover_photo/{id}', 'ProfileController@setCoverPhoto')->name('set_cover_photo');

    // placed last so that the other routes won't match this
    Route::get('/{username?}', 'HomeController@getIndex')->name('index');

    Route::post('/{username}/{parent_id?}', 'HomeController@postIndex')->name('post.index');
});
