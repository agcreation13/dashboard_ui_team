<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UsersListController;
use App\Http\Controllers\Dashboard\UsersRoleListController;


Route::get('/', function () {
      if (Auth::check()) {
        // Example: role-based redirect
        if (in_array(Auth::user()->role, ['representative'])) {
            return redirect()->route('leads-Dashboard');
        }
        // if (in_array(Auth::user()->role, ['supervisor', 'supervisor'])) {
        //     return redirect()->route('leads-Dashboard');
        // }
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

Route::middleware(['auth', 'check.permission'])->group(function () {

   // User-sheet 
   Route::get('/dashboard', [UsersListController::class, 'index'])->name('dashboard');

   Route::get('/master-entry/user-list', [UsersListController::class, 'index'])->name('Uers.Index');
   Route::get('/master-entry/user-list/create', [UsersListController::class, 'create'])->name('Uers.Create');
   Route::post('/master-entry/user-list/store', [UsersListController::class, 'store'])->name('Uers.Store');
   Route::get('/master-entry/user-list/edit/{id}', [UsersListController::class, 'edit'])->name('Uers.Edit');
   Route::get('/master-entry/user-list/show/{id}', [UsersListController::class, 'show'])->name('Uers.Show');
   Route::put('/master-entry/user-list/update/{id}', [UsersListController::class, 'update'])->name('Uers.Update');
   Route::get('/master-entry/user-list/status/{id}', [UsersListController::class, 'statusUpdate'])->name('Uers.StatusUpdate');
   Route::get('/master-entry/user-list/delete/{id}', [UsersListController::class, 'delete'])->name('Uers.Delete');
    
   // User-sheet 
   Route::get('/master-entry/user-role', [UsersRoleListController::class, 'index'])->name('UersRole.Index');
   Route::get('/master-entry/user-role/create', [UsersRoleListController::class, 'create'])->name('UersRole.Create');
   Route::post('/master-entry/user-role/store', [UsersRoleListController::class, 'store'])->name('UersRole.Store');
   Route::get('/master-entry/user-role/edit/{id}', [UsersRoleListController::class, 'edit'])->name('UersRole.Edit');
   Route::get('/master-entry/user-role/show/{id}', [UsersRoleListController::class, 'show'])->name('UersRole.Show');
   Route::put('/master-entry/user-role/update/{id}', [UsersRoleListController::class, 'update'])->name('UersRole.Update');
   Route::get('/master-entry/user-role/status/{id}', [UsersRoleListController::class, 'statusUpdate'])->name('UersRole.StatusUpdate');
   Route::get('/master-entry/user-role/delete/{id}', [UsersRoleListController::class, 'delete'])->name('UersRole.Delete');
   
   
});


require __DIR__.'/auth.php';
