<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GroupController;
use Illuminate\Support\Facades\Route;
use App\Events\SendChat;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat', [ChatController::class, 'index']);
    Route::get('/private-chat/{receiver_id}', [ChatController::class, 'privateChat']);
    Route::post('/send', [ChatController::class, 'send']);
    Route::post('/save', [ChatController::class, 'saveReceiver_id']);
    Route::post('/upload', [ChatController::class, 'upload']);
    
    Route::get('/get/user/{userID}', [ChatController::class, 'get_friend_by_id']);
    Route::get('/user/by/{name}', [ChatController::class, 'get_friend_name']);
    
    // Group
    Route::post('/create/group' ,[GroupController::class , 'create'] );

    Route::get('/group/{group_id}' ,[GroupController::class , 'index'] );
    Route::get('/group/detail/{group_id}' ,[GroupController::class , 'get_detail'] );
    Route::post('/group/send', [GroupController::class, 'send_group_chat_message']);
    Route::post('/user/url', [GroupController::class, 'get_user_url']);

    Route::delete('/leave/group', [GroupController::class, 'leave_group']);
});



require __DIR__.'/auth.php';
