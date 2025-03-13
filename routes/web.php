<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TaskController::class, 'index']);
Route::put('/tasks/changeStatus/{task}', [TaskController::class, 'changeStatus'])
->name('tasks.changeStatus');

Route::resource('tasks', TaskController::class);