<?php

use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;

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

Route::get('/dashboard/{user_id}', function ($user_id) {
    $user = User::find($user_id);

    $messages = Message::where('sender', auth()->id())->where('receiver', $user_id)->orwhere('sender', $user_id)->where('receiver', auth()->id())->get();
    if ($user) {
        return view('dashboard', compact('user', 'messages', 'user_id'));
    }

    toastr()->error('user not found');
    return back();
})->middleware(['auth', 'verified']);

Route::get('dashboard', function ($user_id) {
    return view('dashboard', compact('user'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/chat/{user_id}', function ($user_id) {
    $user = User::find($user_id);
    if ($user) {
        return view('chat', compact('user'));
    }

    toastr()->error('user not found');
    return back();
})->middleware(['auth', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('chat/{user_id}', [MessageController::class, 'sender'])->name('message.send');
});

require __DIR__ . '/auth.php';
