<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'PagesController@root')->name('root');

// Auth::routes();
// 用户身份验证相关的路由
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// 用户注册相关路由
Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

// 密码重置相关路由
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');

// Email 认证相关路由
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}/{hash}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::post('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');

// 用户资源路由
Route::resource('users', 'UsersController', ['only' => ['show', 'update', 'edit']]);
//Route::get('/users/{user}', 'UsersController@show')->name('users.show');          // 显示页面
//Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');     // 编辑页面
//Route::patch('/users/{user}', 'UsersController@update')->name('users.update');    // 处理编辑

// 话题资源路由
Route::resource('topics', 'TopicsController', ['only' => ['index', 'create', 'store', 'update', 'edit', 'destroy']]);
Route::get('topics/{topic}/{slug?}', 'TopicsController@show')->name('topics.show');

// 分类资源路由
Route::resource('categories', 'CategoriesController', ['only' => ['show']]);

// 编辑器上传图片
Route::post('upload_image', 'TopicsController@uploadImage')->name('topics.upload_image');

Route::resource('replies', 'RepliesController', ['only' => ['store', 'destroy']]);

Route::resource('notifications', 'NotificationsController', ['only' => ['index']]);

Route::get('permission-denied', 'PagesController@permissionDenied')->name('permission-denied');










