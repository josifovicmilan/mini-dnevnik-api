<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserSubjectController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Subject\SubjectController;
use App\Http\Controllers\Classroom\ClassroomController;
use App\Http\Controllers\Student\StudentMarkController;
use App\Http\Controllers\Subject\SubjectImportController;
use App\Http\Controllers\Subject\SubjectSubjectController;
use App\Http\Controllers\Classroom\ClassroomImportController;
use App\Http\Controllers\Classroom\ClassroomStudentController;
use App\Http\Controllers\Classroom\ClassroomSubjectController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Student\StudentPersonalDataController;
use App\Http\Controllers\Student\StudentPrimarySchoolDataController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('users/login',[LoginController::class, 'login']);
Route::post('users/logout',[LoginController::class, 'logout'])->middleware('auth:sanctum');
Route::get('users/info',[LoginController::class, 'info'])->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function(){
    //CLASSROOMS
    Route::apiResource('classrooms', ClassroomController::class);
    Route::apiResource('classrooms.students', ClassroomStudentController::class)->only(['index','update', 'store', 'destroy']);
    Route::apiResource('classrooms.subjects', ClassroomSubjectController::class)->only(['index', 'store', 'destroy']);
    Route::post('classrooms/import', [ClassroomImportController::class, 'store']);
    //SUBJECTS
    Route::post('/subjects/import', [SubjectImportController::class, 'store']);
    Route::patch('subjects/{subject1}/subjects/{subject2}', [SubjectSubjectController::class, 'update']);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('users.subjects', UserSubjectController::class);
    //STUDENTS
    Route::post('students/{student}/primary-school-data', [StudentPrimarySchoolDataController::class, 'store']);
    Route::get('students/{student}/primary-school-data', [StudentPrimarySchoolDataController::class, 'index']);
    Route::put('students/{student}/primary-school-data', [StudentPrimarySchoolDataController::class, 'update']);
    Route::get('students/{student}/personal-data', [StudentPersonalDataController::class, 'index']);
    Route::put('students/{student}/personal-data/update', [StudentPersonalDataController::class, 'update']);
    Route::post('students/{student}/personal-data', [StudentPersonalDataController::class, 'store']);
    Route::post('students/{student}/marks/store-many', [StudentMarkController::class, 'storeMany']);
    Route::apiResource('students.marks', StudentMarkController::class)->only('index','store','update');
    Route::apiResource('students',StudentController::class);
});