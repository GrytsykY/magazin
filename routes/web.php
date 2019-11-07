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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/** Admin */
Route::group(['middleware' => ['status','auth']],function (){
    $groupData = [
        'namespace' => 'Blog\Admin',
        'prefix' => 'admin',
    ];

    Route::group($groupData, function (){
        Route::resource('index','MainController')
            ->names('blog.admin.index');

        Route::resource('orders','OrderController')
            ->names('blog.admin.orders');
        Route::get('/orders/change/{id}','OrderController@change')
            ->name('blog.admin.orders.change');
        Route::post('/orders/save/{id}','OrderController@save')
            ->name('blog.admin.orders.save');
        Route::get('/orders/forcedestroy/{id}','OrderController@forcedestroy')
            ->name('blog.admin.orders.forcedestroy');

        Route::get('/categories/mydel','CategoryController@mydel')
            ->name('blog.admin.categories.mydel');
        Route::resource('catigories','CategoryController')
            ->names('blog.admin.categories');

        Route::resource('users','UserController')
            ->names('blog.admin.users');

        Route::resource('products','ProductController')
            ->names('blog.admin.products');
    });
});

Route::get('user/index','Blog\User\MainController@index');

