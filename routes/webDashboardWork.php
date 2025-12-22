<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Worksheet\WorksheetDashboardController;
use App\Http\Controllers\Worksheet\WorksheetRoomListController;
use App\Http\Controllers\Worksheet\WorksheetRoomWorkEntityController;
use App\Http\Controllers\Worksheet\WorksheetWorkKittyController;
use App\Http\Controllers\Worksheet\WorksheetWorkKittyAssignmentController;
use App\Http\Controllers\Worksheet\WorkAreaController;
use App\Http\Controllers\Worksheet\WorkProductController;



Route::middleware(['auth', 'check.permission'])->group(function () {

   // worksheet-Dashboard 
    Route::get('/worksheet', [WorksheetDashboardController::class, 'index'])->name('worksheet');
    
   // roome list
   Route::get('/worksheet/room-list', [WorksheetRoomListController::class, 'index'])->name('workSheet.Index');
   Route::get('/worksheet/rooms-list/{id}', [WorksheetRoomListController::class, 'showbyStatus'])->name('workSheet.StatusWise');
   Route::get('/worksheet/room-list/create', [WorksheetRoomListController::class, 'create'])->name('workSheet.Create');
   Route::post('/worksheet/room-list/store', [WorksheetRoomListController::class, 'store'])->name('workSheet.Store');
   Route::get('/worksheet/room-list/edit/{id}', [WorksheetRoomListController::class, 'edit'])->name('workSheet.Edit');
   Route::get('/worksheet/room-list/show/{id}', [WorksheetRoomListController::class, 'show'])->name('workSheet.Show');
   Route::put('/worksheet/room-list/update/{id}', [WorksheetRoomListController::class, 'update'])->name('workSheet.Update');
   Route::get('/worksheet/room-list/status/{id}', [WorksheetRoomListController::class, 'statusUpdate'])->name('workSheet.StatusUpdate');
   Route::get('/worksheet/room-list/delete/{id}', [WorksheetRoomListController::class, 'delete'])->name('workSheet.Delete');
   Route::get('/worksheet/room-list/work-entry/{id}', [WorksheetRoomListController::class, 'addWork'])->name('workSheet.addWork');
   
   // work-entry-list
   Route::get('/worksheet/work-entry-list', [WorksheetRoomWorkEntityController::class, 'index'])->name('workEntry.Index');
   Route::get('/worksheet/work-entry/{id}', [WorksheetRoomWorkEntityController::class, 'showbyStatus'])->name('workEntry.StatusWise');
   Route::get('/worksheet/work-entry-list/create', [WorksheetRoomWorkEntityController::class, 'create'])->name('workEntry.Create');
   Route::post('/worksheet/work-entry-list/store', [WorksheetRoomWorkEntityController::class, 'store'])->name('workEntry.Store');
   Route::get('/worksheet/work-entry-list/edit/{id}', [WorksheetRoomWorkEntityController::class, 'edit'])->name('workEntry.Edit');
   Route::get('/worksheet/work-entry-list/show/{id}', [WorksheetRoomWorkEntityController::class, 'show'])->name('workEntry.Show');
   Route::put('/worksheet/work-entry-list/update/{id}', [WorksheetRoomWorkEntityController::class, 'update'])->name('workEntry.Update');
   Route::get('/worksheet/work-entry-list/status/{id}', [WorksheetRoomWorkEntityController::class, 'statusUpdate'])->name('workEntry.StatusUpdate');
   Route::get('/worksheet/work-entry-list/delete/{id}', [WorksheetRoomWorkEntityController::class, 'delete'])->name('workEntry.Delete');
   Route::get('worksheet/work-entry-list/create/{id}', [WorksheetRoomWorkEntityController::class, 'addWork'])->name('workEntry.addWork');
    
   // work-kitty-entry-list
   Route::get('/worksheet/work-kitty-list', [WorksheetWorkKittyController::class, 'index'])->name('workKitty.Index');
   Route::get('/worksheet/work-kitty/{id}', [WorksheetWorkKittyController::class, 'showbyStatus'])->name('workKitty.StatusWise');
   Route::get('/worksheet/work-kitty-list/create', [WorksheetWorkKittyController::class, 'create'])->name('workKitty.Create');
   Route::get('/worksheet/work-kitty-list/create/{id}', [WorksheetWorkKittyController::class, 'addWork'])->name('workKitty.addWork');
   Route::post('/worksheet/work-kitty-list/store', [WorksheetWorkKittyController::class, 'store'])->name('workKitty.Store');
   Route::get('/worksheet/work-kitty-list/edit/{id}', [WorksheetWorkKittyController::class, 'edit'])->name('workKitty.Edit');
   Route::get('/worksheet/work-kitty-list/show/{id}', [WorksheetWorkKittyController::class, 'show'])->name('workKitty.Show');
   Route::put('/worksheet/work-kitty-list/update/{id}', [WorksheetWorkKittyController::class, 'update'])->name('workKitty.Update');
   Route::get('/worksheet/work-kitty-list/status/{id}', [WorksheetWorkKittyController::class, 'statusUpdate'])->name('workKitty.StatusUpdate');
   Route::get('/worksheet/work-kitty-list/delete/{id}', [WorksheetWorkKittyController::class, 'delete'])->name('workKitty.Delete');
   Route::get('/worksheet/get-work-kitty-list/{id}/{room}', [WorksheetWorkKittyController::class, 'getBySiteId'])->name('workKitty.GetBySiteId');
   //  work-kitty-assingment 
   Route::get('/worksheet/work-kitty-assignment-list', [WorksheetWorkKittyAssignmentController::class, 'index'])->name('workKittyAssignment.Index');
   Route::get('/worksheet/work-kitty-assignment/{id}', [WorksheetWorkKittyAssignmentController::class, 'showbyStatus'])->name('workKittyAssignment.StatusWise');
   Route::get('/worksheet/work-kitty-assignment-list/create', [WorksheetWorkKittyAssignmentController::class, 'create'])->name('workKittyAssignment.Create');
   Route::get('/worksheet/work-kitty-assignment-list/create/{id}', [WorksheetWorkKittyAssignmentController::class, 'addWork'])->name('workKittyAssignment.addWork');
   Route::post('/worksheet/work-kitty-assignment-list/store', [WorksheetWorkKittyAssignmentController::class, 'store'])->name('workKittyAssignment.Store');
   Route::get('/worksheet/work-kitty-assignment-list/edit/{id}', [WorksheetWorkKittyAssignmentController::class, 'edit'])->name('workKittyAssignment.Edit');
   Route::get('/worksheet/work-kitty-assignment-list/report/{id}', [WorksheetWorkKittyAssignmentController::class, 'editReport'])->name('workKittyAssignment.ReportUpdate');
   Route::put('/worksheet/work-kitty-assignment-list/report-update/{id}', [WorksheetWorkKittyAssignmentController::class, 'reportUpdates'])->name('workKittyAssignment.ReportUpdates');
   Route::get('/worksheet/work-kitty-assignment-list/show/{id}', [WorksheetWorkKittyAssignmentController::class, 'show'])->name('workKittyAssignment.Show');
   Route::put('/worksheet/work-kitty-assignment-list/update/{id}', [WorksheetWorkKittyAssignmentController::class, 'update'])->name('workKittyAssignment.Update');
   Route::get('/worksheet/work-kitty-assignment-list/status/{id}', [WorksheetWorkKittyAssignmentController::class, 'statusUpdate'])->name('workKittyAssignment.StatusUpdate');
   Route::get('/worksheet/work-kitty-assignment-list/delete/{id}', [WorksheetWorkKittyAssignmentController::class, 'delete'])->name('workKittyAssignment.Delete');
   Route::get('/worksheet/get-work-kitty-assignment-list/{id}', [WorksheetWorkKittyAssignmentController::class, 'getBySiteId'])->name('workKittyAssignment.GetBySiteId');
   
   // work-area-list
   Route::get('/worksheet/work-area-list', [WorkAreaController::class, 'index'])->name('workAreaList.Index');
   Route::get('/worksheet/work-area-list/create', [WorkAreaController::class, 'create'])->name('workAreaList.Create');
   Route::post('/worksheet/work-area-list/store', [WorkAreaController::class, 'store'])->name('workAreaList.Store');
   Route::get('/worksheet/work-area-list/edit/{id}', [WorkAreaController::class, 'edit'])->name('workAreaList.Edit');
   Route::get('/worksheet/work-area-list/show/{id}', [WorkAreaController::class, 'show'])->name('workAreaList.Show');
   Route::put('/worksheet/work-area-list/update/{id}', [WorkAreaController::class, 'update'])->name('workAreaList.Update');
   Route::get('/worksheet/work-area-list/status/{id}', [WorkAreaController::class, 'statusUpdate'])->name('workAreaList.StatusUpdate');
   Route::get('/worksheet/work-area-list/delete/{id}', [WorkAreaController::class, 'delete'])->name('workAreaList.Delete');
   
   // work-product-list
   Route::get('/worksheet/work-product-list', [WorkProductController::class, 'index'])->name('workProductList.Index');
   Route::get('/worksheet/work-product-list/create', [WorkProductController::class, 'create'])->name('workProductList.Create');
   Route::post('/worksheet/work-product-list/store', [WorkProductController::class, 'store'])->name('workProductList.Store');
   Route::get('/worksheet/work-product-list/edit/{id}', [WorkProductController::class, 'edit'])->name('workProductList.Edit');
   Route::get('/worksheet/work-product-list/show/{id}', [WorkProductController::class, 'show'])->name('workProductList.Show');
   Route::put('/worksheet/work-product-list/update/{id}', [WorkProductController::class, 'update'])->name('workProductList.Update');
   Route::get('/worksheet/work-product-list/status/{id}', [WorkProductController::class, 'statusUpdate'])->name('workProductList.StatusUpdate');
   Route::get('/worksheet/work-product-list/delete/{id}', [WorkProductController::class, 'delete'])->name('workProductList.Delete');

  
});

