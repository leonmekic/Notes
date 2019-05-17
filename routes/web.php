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

Route::get(
    '/',
    function () {
        return view('welcome');
    }
);

Route::resource('notes', 'NotesController');
Route::get('/tags', 'TagsController@showTags')->name('tags');
Route::get('/tags/{id}/notes', 'TagsController@notesByTag')->name('notesWithTag');
Route::get('/users', 'UsersController@showUsers')->name('users');
Route::get('/users/me', 'UsersController@showCurrentUser')->name('me');
Route::get('/users/{id}', 'UsersController@showUserById')->name('user');
Route::get('/users/{id}/notes', 'UsersController@showUserNotes')->name('userNotes');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


