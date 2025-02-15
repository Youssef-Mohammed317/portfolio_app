<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::post('/user/register', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/skills', SkillController::class);
    Route::resource('/projects', ProjectController::class);
    Route::post('/skills/{skill}', [SkillController::class, 'update']);
    Route::post('/projects/{project}', [ProjectController::class, 'update']);
    Route::post('/user/logout', [AuthController::class, 'logout']);
    Route::post('/user/verify', [AuthController::class, 'verify']);
    Route::post('/user/verify/check', [AuthController::class, 'checkVerify']);
    Route::get('/user',function(Request $request){
        return $request->user();
    });

});
Route::get('/get-skills',[SkillController::class,'getSkills']);
Route::get('/get-skill/{skill}',[SkillController::class,'getSkill']);
Route::get('/get-projects',[ProjectController::class,'getProjects']);
Route::get('/get-project/{project}',[ProjectController::class,'getProject']);

