<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Dashboard\SiteDetailsController;
use App\Http\Controllers\Dashboard\LabourDetailsController;
use App\Http\Controllers\Dashboard\MaterialDetailsController;
use App\Http\Controllers\Dashboard\labourRolesController;
use App\Http\Controllers\Dashboard\DailyMaterialEntryController;
use App\Http\Controllers\Dashboard\DailyLabourEntryController;
use App\Http\Controllers\Dashboard\DailySiteSheetController;
use App\Http\Controllers\Dashboard\SiteBillingController;
use App\Http\Controllers\Dashboard\SitePaymentReceiptController;
use App\Http\Controllers\Dashboard\CompletedSiteDashboardController;


Route::middleware(['auth', 'check.permission'])->group(function () {
   Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//    site-detail
   Route::get('/dashboard/site-details', [SiteDetailsController::class, 'index'])->name('siteDetails.Index');
   Route::post('/dashboard/site-details/{prv}/{next}', [SiteDetailsController::class, 'ShowByDate'])->name('siteDetails.ShowByDate');
   Route::get('/dashboard/site-details/create', [SiteDetailsController::class, 'create'])->name('siteDetails.Create');
   Route::post('/dashboard/site-details/store', [SiteDetailsController::class, 'store'])->name('siteDetails.Store');
   Route::get('/dashboard/site-details/show/{id}', [SiteDetailsController::class, 'show'])->name('siteDetails.Show');
   Route::get('/dashboard/site-details/add-daily-sheet/{id}', [SiteDetailsController::class, 'dailySheet'])->name('siteDetails.AddDailySheet');
   Route::get('/dashboard/site-details/edit/{id}', [SiteDetailsController::class, 'edit'])->name('siteDetails.Edit');
   Route::get('/dashboard/site-details/{id}', [SiteDetailsController::class, 'showbyStatus'])->name('siteStage.Status');
   Route::put('/dashboard/site-details/update/{id}', [SiteDetailsController::class, 'update'])->name('siteDetails.Update');
   Route::get('/dashboard/site-details/status/{id}', [SiteDetailsController::class, 'statusUpdate'])->name('siteDetails.StatusUpdate');
   Route::get('/dashboard/site-details/delete/{id}', [SiteDetailsController::class, 'delete'])->name('siteDetails.Delete');
//    labour-detail
   Route::get('/master-entry/labour', [LabourDetailsController::class, 'index'])->name('labour.Index');
   Route::get('/master-entry/labour/create', [LabourDetailsController::class, 'create'])->name('labour.Create');
   Route::post('/master-entry/labour/store', [LabourDetailsController::class, 'store'])->name('labour.Store');
   Route::get('/master-entry/labour/edit/{id}', [LabourDetailsController::class, 'edit'])->name('labour.Edit');
   Route::get('/master-entry/labour/show/{id}', [LabourDetailsController::class, 'show'])->name('labour.Show');
   Route::put('/master-entry/labour/update/{id}', [LabourDetailsController::class, 'update'])->name('labour.Update');
   Route::get('/master-entry/labour/status/{id}', [LabourDetailsController::class, 'statusUpdate'])->name('labour.StatusUpdate');
   Route::get('/master-entry/labour/delete/{id}', [LabourDetailsController::class, 'delete'])->name('labour.Delete');
//    material-detail
   Route::get('/master-entry/material', [MaterialDetailsController::class, 'index'])->name('material.Index');
   Route::get('/master-entry/material/create', [MaterialDetailsController::class, 'create'])->name('material.Create');
   Route::post('/master-entry/material/store', [MaterialDetailsController::class, 'store'])->name('material.Store');
   Route::get('/master-entry/material/edit/{id}', [MaterialDetailsController::class, 'edit'])->name('material.Edit');
   Route::get('/master-entry/material/show/{id}', [MaterialDetailsController::class, 'show'])->name('material.Show');
   Route::put('/master-entry/material/update/{id}', [MaterialDetailsController::class, 'update'])->name('material.Update');
   Route::get('/master-entry/material/status/{id}', [MaterialDetailsController::class, 'statusUpdate'])->name('material.StatusUpdate');
   Route::get('/master-entry/material/delete/{id}', [MaterialDetailsController::class, 'delete'])->name('material.Delete');
//    labourRoles
   Route::get('/master-entry/labour-roles', [labourRolesController::class, 'index'])->name('labourRoles.Index');
   Route::get('/master-entry/labour-roles/create', [labourRolesController::class, 'create'])->name('labourRoles.Create');
   Route::post('/master-entry/labour-roles/store', [labourRolesController::class, 'store'])->name('labourRoles.Store');
   Route::get('/master-entry/labour-roles/edit/{id}', [labourRolesController::class, 'edit'])->name('labourRoles.Edit');
   Route::get('/master-entry/labour-roles/show/{id}', [labourRolesController::class, 'show'])->name('labourRoles.Show');
   Route::put('/master-entry/labour-roles/update/{id}', [labourRolesController::class, 'update'])->name('labourRoles.Update');
   Route::get('/master-entry/labour-roles/status/{id}', [labourRolesController::class, 'statusUpdate'])->name('labourRoles.StatusUpdate');
   Route::get('/master-entry/labour-roles/delete/{id}', [labourRolesController::class, 'delete'])->name('labourRoles.Delete');
//    DailyMaterialEntry-detail
   Route::get('/dashboard/daily-material-entry', [DailyMaterialEntryController::class, 'index'])->name('DailyMaterialEntry.Index');
   Route::get('/dashboard/daily-material-entry/create', [DailyMaterialEntryController::class, 'create'])->name('DailyMaterialEntry.Create');
   Route::post('/dashboard/daily-material-entry/store', [DailyMaterialEntryController::class, 'store'])->name('DailyMaterialEntry.Store');
   Route::get('/dashboard/daily-material-entry/edit/{id}', [DailyMaterialEntryController::class, 'edit'])->name('DailyMaterialEntry.Edit');
   Route::get('/dashboard/daily-material-entry/show/{id}', [DailyMaterialEntryController::class, 'show'])->name('DailyMaterialEntry.Show');
   Route::put('/dashboard/daily-material-entry/update/{id}', [DailyMaterialEntryController::class, 'update'])->name('DailyMaterialEntry.Update');
   Route::get('/dashboard/daily-material-entry/status/{id}', [DailyMaterialEntryController::class, 'statusUpdate'])->name('DailyMaterialEntry.StatusUpdate');
   Route::get('/dashboard/daily-material-entry/delete/{id}', [DailyMaterialEntryController::class, 'delete'])->name('DailyMaterialEntry.Delete');
   Route::get('/dashboard/daily-material-entry-list/{id}', [DailyMaterialEntryController::class, 'allDataList'])->name('DailyMaterialEntry.allDataList');
   //    DailyLabourEntry-detail
   Route::get('/dashboard/daily-labour-entry', [DailyLabourEntryController::class, 'index'])->name('DailyLabourEntry.Index');
   Route::get('/dashboard/daily-labour-entry/create', [DailyLabourEntryController::class, 'create'])->name('DailyLabourEntry.Create');
   Route::post('/dashboard/daily-labour-entry/store', [DailyLabourEntryController::class, 'store'])->name('DailyLabourEntry.Store');
   Route::get('/dashboard/daily-labour-entry/edit/{id}', [DailyLabourEntryController::class, 'edit'])->name('DailyLabourEntry.Edit');
   Route::get('/dashboard/daily-labour-entry/show/{id}', [DailyLabourEntryController::class, 'show'])->name('DailyLabourEntry.Show');
   Route::put('/dashboard/daily-labour-entry/update/{id}', [DailyLabourEntryController::class, 'update'])->name('DailyLabourEntry.Update');
   Route::put('/dashboard/daily-labour-entry/remark-update/{id}', [DailyLabourEntryController::class, 'RemarkUpdate'])->name('DailyLabourEntry.RemarkUpdate');
   Route::get('/dashboard/daily-labour-entry/status/{id}', [DailyLabourEntryController::class, 'statusUpdate'])->name('DailyLabourEntry.StatusUpdate');
   Route::get('/dashboard/daily-labour-entry/delete/{id}', [DailyLabourEntryController::class, 'delete'])->name('DailyLabourEntry.Delete');
   Route::get('/dashboard/daily-labour-entry-list/{id}', [DailyLabourEntryController::class, 'allDataList'])->name('DailyLabourEntry.allDataList');
   // Route::get('/dashboard/daily-labour-entry/next/{id}', [DailyLabourEntryController::class, 'statusUpdate'])->name('DailyLabourEntry.StatusUpdate');
//    DailyEntrySheet-detail
   Route::get('/dashboard/daily-sheet', [DailySiteSheetController::class, 'index'])->name('dailySheet.Index');
   Route::get('/dashboard/daily-sheet/create/{id}', [DailySiteSheetController::class, 'create'])->name('dailySheet.Create');
   Route::get('/dashboard/daily-sheet/edit/{id}', [DailySiteSheetController::class, 'edit'])->name('dailySheet.Edit');
   Route::put('/dashboard/daily-sheet/update-labour/{id}', [DailyLabourEntryController::class, 'update'])->name('DailyLabourSheet.Update');
   Route::put('/dashboard/daily-sheet/update-material/{id}', [DailyMaterialEntryController::class, 'update'])->name('DailyMaterialSheet.Update');
   Route::get('/dashboard/daily-sheet/show/{id}', [DailySiteSheetController::class, 'show'])->name('dailySheet.Show');
   Route::get('/dashboard/daily-sheet/delete/{id}', [DailySiteSheetController::class, 'show'])->name('dailySheet.Delete');

   //  site Bill
   Route::get('/dashboard/site-bill', [SiteBillingController::class, 'index'])->name('siteBill.Index');
   Route::get('/dashboard/site-bill/create', [SiteBillingController::class, 'create'])->name('siteBill.Create');
   Route::get('/dashboard/site-bill/create/{id}', [SiteBillingController::class, 'createById'])->name('siteBill.CreateById');
   Route::post('/dashboard/site-bill/store', [SiteBillingController::class, 'store'])->name('siteBill.Store');
   Route::get('/dashboard/site-bill/edit/{id}', [SiteBillingController::class, 'edit'])->name('siteBill.Edit');
   Route::get('/dashboard/site-bill/show/{id}', [SiteBillingController::class, 'show'])->name('siteBill.Show');
   Route::put('/dashboard/site-bill/update/{id}', [SiteBillingController::class, 'update'])->name('siteBill.Update');
   Route::get('/dashboard/site-bill/status/{id}', [SiteBillingController::class, 'statusUpdate'])->name('siteBill.StatusUpdate');
   Route::get('/dashboard/site-bill/delete/{id}', [SiteBillingController::class, 'delete'])->name('siteBill.Delete');
   Route::get('/dashboard/site-bill-getlist/{id}', [SiteBillingController::class, 'getlist'])->name('siteBill.getList');

   //  payment Receipt
   Route::get('/dashboard/payment-receipt', [SitePaymentReceiptController::class, 'index'])->name('paymentReceipt.Index');
   Route::get('/dashboard/payment-receipt/create', [SitePaymentReceiptController::class, 'create'])->name('paymentReceipt.Create');
   Route::get('/dashboard/payment-receipt/create/{id}', [SitePaymentReceiptController::class, 'createById'])->name('paymentReceipt.CreateById');
   Route::post('/dashboard/payment-receipt/store', [SitePaymentReceiptController::class, 'store'])->name('paymentReceipt.Store');
   Route::get('/dashboard/payment-receipt/edit/{id}', [SitePaymentReceiptController::class, 'edit'])->name('paymentReceipt.Edit');
   Route::get('/dashboard/payment-receipt/show/{id}', [SitePaymentReceiptController::class, 'show'])->name('paymentReceipt.Show');
   Route::put('/dashboard/payment-receipt/update/{id}', [SitePaymentReceiptController::class, 'update'])->name('paymentReceipt.Update');
   Route::get('/dashboard/payment-receipt/status/{id}', [SitePaymentReceiptController::class, 'statusUpdate'])->name('paymentReceipt.StatusUpdate');
   Route::get('/dashboard/payment-receipt/delete/{id}', [SitePaymentReceiptController::class, 'delete'])->name('paymentReceipt.Delete');
   Route::get('/dashboard/payment-receipt-getlist/{id}', [SitePaymentReceiptController::class, 'getlist'])->name('paymentReceipt.getList');

// 
// CompletedSiteDashboardController
   Route::get('/dashboard/completed-sites', [CompletedSiteDashboardController::class, 'index'])->name('completedSites.Index');
   Route::get('/dashboard/completed-sites/delete/{id}', [CompletedSiteDashboardController::class, 'destroy'])->name('completedSites.Delete');
   Route::get('/dashboard/completed-sites/export/{id}', [CompletedSiteDashboardController::class, 'export'])->name('completedSites.Export');

});

