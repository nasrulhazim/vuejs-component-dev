<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('/subscribe', 'SubscriberController@subscribe');

Route::post('/unsubscribe', 'SubscriberController@unsubscribe');

Route::get('/tasks/{id}', function($id){
	$tasks = \App\Task::where('user_id', $id)->orderBy('created_at','asc')->paginate(5);
	return response()->json($tasks);
});
