<?php
use App\Http\Livewire\Chat;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/chat', Chat::class)->name('chat');
});
