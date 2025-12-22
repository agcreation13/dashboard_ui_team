<?php

use Illuminate\Support\Facades\Route;
// 
use App\Http\Controllers\Dashboard\UsersListController;
use App\Http\Controllers\Dashboard\UsersRoleListController;
use App\Http\Controllers\Leads\LeadsSheetController;
use App\Http\Controllers\Leads\LeadsFollowsController;
use App\Http\Controllers\Leads\LeadsDashboardController;



Route::middleware(['auth', 'check.permission'])->group(function () {

   // leads-Dashboard 
    Route::get('/dashboard-leads', [LeadsDashboardController::class, 'index'])->name('leads-Dashboard');
    
   // leads-sheet 
   Route::get('/dashboard-leads/leads-sheet', [LeadsSheetController::class, 'index'])->name('leadsSheet.Index');
   Route::get('/dashboard-leads/leads-sheets/{id}', [LeadsSheetController::class, 'showbyStatus'])->name('leadsSheet.StatusWise');
   Route::get('/dashboard-leads/leads-sheet/create', [LeadsSheetController::class, 'create'])->name('leadsSheet.Create');
   Route::post('/dashboard-leads/leads-sheet/store', [LeadsSheetController::class, 'store'])->name('leadsSheet.Store');
   Route::get('/dashboard-leads/leads-sheet/edit/{id}', [LeadsSheetController::class, 'edit'])->name('leadsSheet.Edit');
   Route::get('/dashboard-leads/leads-sheet/show/{id}', [LeadsSheetController::class, 'show'])->name('leadsSheet.Show');
   Route::put('/dashboard-leads/leads-sheet/update/{id}', [LeadsSheetController::class, 'update'])->name('leadsSheet.Update');
   Route::get('/dashboard-leads/leads-sheet/status/{id}', [LeadsSheetController::class, 'statusUpdate'])->name('leadsSheet.StatusUpdate');
   Route::get('/dashboard-leads/leads-sheet/delete/{id}', [LeadsSheetController::class, 'delete'])->name('leadsSheet.Delete');
    
   // leads-follows
   Route::get('/dashboard-leads/leads-follow', [LeadsFollowsController::class, 'index'])->name('leadsFollow.Index');
//    Route::get('/dashboard-leads/leads-follow/create', [LeadsFollowsController::class, 'create'])->name('leadsFollow.Create');
   Route::get('/dashboard-leads/leads-follow/create/{id}', [LeadsFollowsController::class, 'createById'])->name('leadsFollow.CreateById');
   Route::post('/dashboard-leads/leads-follow/store', [LeadsFollowsController::class, 'store'])->name('leadsFollow.Store');
   Route::get('/dashboard-leads/leads-follow/edit/{id}', [LeadsFollowsController::class, 'edit'])->name('leadsFollow.Edit');
   Route::get('/dashboard-leads/leads-follow/show/{id}', [LeadsFollowsController::class, 'show'])->name('leadsFollow.Show');
   Route::put('/dashboard-leads/leads-follow/update/{id}', [LeadsFollowsController::class, 'update'])->name('leadsFollow.Update');
   Route::get('/dashboard-leads/leads-follow/status/{id}', [LeadsFollowsController::class, 'statusUpdate'])->name('leadsFollow.StatusUpdate');
   Route::get('/dashboard-leads/leads-follow/delete/{id}', [LeadsFollowsController::class, 'delete'])->name('leadsFollow.Delete');
    
   // User-sheet 
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

