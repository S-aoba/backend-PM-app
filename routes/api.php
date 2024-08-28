<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectUserController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


// Route::group(['middleware' => 'auth:sanctum'], function () {
//     Route::apiResources([
//         'projects' => ProjectController::class,
//         'task' => TaskController::class,
//         'project.user' => ProjectUserController::class
//     ]);

//     Route::middleware(['auth:sanctum'])->get('/user/{user_id}/projects', [UserController::class, 'fetchUserProject']);
// });

Route::apiResource('/projects', ProjectController::class)->only([
   'store',
   'show',
   'update',
   'destroy', 
])->middleware(['auth:sanctum']);

