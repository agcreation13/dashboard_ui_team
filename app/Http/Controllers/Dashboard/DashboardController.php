<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SiteDetail;
use App\Models\Labour;
use App\Models\Material;
use App\Models\CheckUpdateSite;
use App\Models\DailyLabourEntry;
use App\Models\DailyMaterialEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    protected $userRole;
    protected $userId;

    public function __construct()
    {
        // Set user role and ID from authenticated user
        $this->userRole = Auth::user()->role;
        $this->userId = Auth::user()->emp_id;
    }

    public function index(Request $request)
    {
       
        $SiteDetails = [];
        $upcomingPayments = [];
        $unupdatedSites = [];
        $activeSites = [];
        return view("pages.dashboard.dashboard", [
            "SiteDetails" => $SiteDetails,
            "upcomingPayments" => $upcomingPayments,
            "unupdatedSites" => $unupdatedSites,
            "activeSites" => $activeSites,
        ]);
    }
}
