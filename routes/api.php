<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\AuthController;

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
/**
 *  @OA\Post(
 *      path="/api/home",
 *      summary="Home data",
 *      description="",
 *      tags={"Home"},
 *      @OA\Parameter(
 *          name="name",
 *          in="query",
 *          description="Provide your name",
 *          required=true,
 *      ),
 *      @OA\Response(
 *          response=200,
 *          description="OK",
 *          @OA\MediaType(
 *              mediaType="application/json",
 *          )
 *      )
 * )
 */
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::get('info', 'App\Http\Controllers\AuthController@info')->middleware(['middleware' => 'jwt.auth']);
    Route::get('logout', 'App\Http\Controllers\AuthController@logout')->middleware(['middleware' => 'jwt.auth']);
});

