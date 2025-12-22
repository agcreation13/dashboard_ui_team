<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\SiteReportController;
use App\Http\Controllers\Leads\LeadsReportController;
use App\Http\Controllers\Auto\DailyUpdateCheckController;
use App\Http\Controllers\WorkCheckIncentiveController;

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

   // report
   Route::get('/report/site-reports', [SiteReportController::class, 'siteReport'])->name('siteReport');
   Route::get('/report/leads-report', [LeadsReportController::class, 'leadsReport'])->name('leadsReport');
   Route::post('/urlredirect', [DailyUpdateCheckController::class, 'checkUpdaterun'])->name('checkUpdaterun');
   
});

Route::get('/check-incentive', [WorkCheckIncentiveController::class, 'index'])->name('checkIncentive.index');
Route::post('/check-incentive', [WorkCheckIncentiveController::class, 'getData'])->name('checkIncentive.getData');

require __DIR__.'/auth.php';
require __DIR__.'/webDashboard.php';
require __DIR__.'/webDashboardLeads.php';
require __DIR__.'/webDashboardWork.php';
