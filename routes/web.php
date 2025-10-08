<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Minimal named routes to support Blade helpers like route('login') / route('logout').
| Authentication here is basic; the API token login flow remains separate.
*/

Route::view('/', 'demo')->name('home');

// Login page (named) so route('login') works in Blade.
Route::view('/login', 'login')->name('login');

// Dashboard (named). You can wrap with auth middleware later if you enable session auth.
Route::view('/dashboard', 'dashboard')->name('dashboard');

// Logout route used by the form in the layout. If session auth is not active, it just redirects.
Route::post('/logout', function (Request $request) {
    try {
        Auth::logout();
    } catch (\Throwable $e) {
        // Ignore if no session-based auth in place.
    }
    if (method_exists($request, 'session')) {
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
    return redirect()->route('login');
})->name('logout');

