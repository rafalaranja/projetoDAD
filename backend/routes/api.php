<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\VcardController;
use App\Http\Controllers\CategorieController;
use App\Models\Transaction;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']); 
Route::get('teste', function () {
    return App\Models\View_auth_user::all();
});
Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('users/me', [UserController::class, 'show_me']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show'])->middleware('can:view,user');
   Route::put('vcards/{vcard}', [UserController::class, 'update'])->middleware('can:update,vcard');
    
    
    
    Route::get('transactions', [TransactionController::class,'index']);
    Route::get('transactions/{transaction}', [TransactionController::class,'show'])->middleware('can:view,transaction');
    Route::get('vcards', [VcardController::class, 'index']);
    
    
    /*Route::get('projects', [ProjectController::class, 'index']);
    Route::get('projects/{project}', [ProjectController::class, 'show']);
    Route::get('projects/{project}/tasks', [ProjectController::class, 'showWithTasks']);
    Route::post('projects', [ProjectController::class, 'store']);
    Route::delete('projects/{project}', [ProjectController::class, 'destroy']);
    Route::put('projects/{project}', [ProjectController::class, 'update']);
    Route::get('users/{user}/projects', [ProjectController::class, 'getProjectsOfUser']);
    Route::get('users/{user}/projects/inprogress', [ProjectController::class, 'getProjectsInProgressOfUser']);
    */
});

///Route::put('users/{user}', [UserController::class, 'update'])
 //->middleware('can:update,user');

Route::patch('users/{user}/password', [UserController::class, 'update_password']);

      //  ->middleware('can:updatePassword,user'); tratar disto

Route::patch('users/{user}/pin', [UserController::class, 'update_pin']);//->middleware('can:updatePassword,user');

       // ->middleware('can:updatePassword,user'); tratar disto
Route::post('transactions',[TransactionController::class,'store']);


Route::post('vcard/new', [VcardController::class, 'store']);
Route::delete('users/{user}/delete', [VcardController::class, 'load']);
Route::get('vcard/{user}/load', [VcardController::class, 'load']);


Route::get('statistics/{user}/load', [TransactionController::class, 'loadStatistics']);

Route::get('categories',[CategorieController::class, 'index']);
Route::get('categories/send/{user}',[CategorieController::class, 'indexSend']);
Route::get('categories/{user}', [CategorieController::class,'show']);
Route::post('categories',[CategorieController::class,'store']);