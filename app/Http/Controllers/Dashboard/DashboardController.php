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
        $today = Carbon::today()->toDateString();
        // Check if today's site update check is already done
        // $checkUpdateSite = CheckUpdateSite::where('check_date', $today)->first();
        $checkUpdateSite = CheckUpdateSite::where("check_date", $today)
            ->where("check_type", "site")
            ->first();

        if (!$checkUpdateSite) {
            $unupdatedSiteIds = [];

            // Get sites for supervisor or all, based on role

            $sitesList = SiteDetail::whereIn("status", [
                "active",
                "unupdated",
                "standby",
            ])
                ->whereDate("start_date", "<=", $today)
                ->get();

            // Check if each site has a labour entry for today
            foreach ($sitesList as $site) {
                if ($site->status === "standby") {
                    $hasEntryStandby = $site
                        ->dailyLabourEntries()
                        ->whereDate("plan_of_date", $today)
                        ->exists();
                    if (!$hasEntryStandby) {
                        $newDailyLabourEntry = new DailyLabourEntry();
                        $newDailyLabourEntry->site_id = $site->id;
                        $newDailyLabourEntry->plan_of_date = $today;
                        $newDailyLabourEntry->plan_message =
                            "This Site is on Standby";
                        $newDailyLabourEntry->labour_data = json_encode([]);
                        $newDailyLabourEntry->added_by = Auth::id() ?? 1;
                        $newDailyLabourEntry->save();
                    }
                } else {
                    // $hasEntry = $site->dailyLabourEntries()->whereDate('plan_of_date', $today)->exists();

                    // if (!$hasEntry) {
                    //      $site->status = 'unupdated';
                    //      $site->save();
                    //      $unupdatedSiteIds[] = $site->id;
                    //  } elseif ($site->status === 'unupdated') {
                    //      $site->status = 'active';
                    //      $site->save();
                    //  }
                    // ==================== ACTIVE/UNUPDATED SITES ====================
                    if (
                        $site->status === "unupdated" ||
                        $site->status === "active"
                    ) {
                        $startDate = Carbon::parse($site->start_date);
                        $todayDate = Carbon::parse($today);

                        // Check if any entry exists from start_date to today
                        $hasAnyEntry = $site
                            ->dailyLabourEntries()
                            ->whereDate(
                                "plan_of_date",
                                ">=",
                                $startDate->toDateString()
                            )
                            ->whereDate(
                                "plan_of_date",
                                "<=",
                                $todayDate->toDateString()
                            )
                            ->exists();

                        // Check for missing dates from start_date to today
                        $missingDates = [];
                        $currentDate = $startDate->copy();

                        while ($currentDate->lte($todayDate)) {
                            $checkDate = $currentDate->toDateString();
                            $hasEntryForDate = $site
                                ->dailyLabourEntries()
                                ->whereDate("plan_of_date", $checkDate)
                                ->exists();

                            if (!$hasEntryForDate) {
                                $missingDates[] = $checkDate;
                            }

                            $currentDate->addDay();
                        }

                        if (!empty($missingDates)) {
                            // Missing dates found - mark as unupdated
                            $site->status = "unupdated";
                            $site->save();
                            $unupdatedSiteIds[] = $site->id;
                        } elseif ($site->status === "unupdated") {
                            // No missing dates - restore to active
                            $site->status = "active";
                            $site->save();
                        }
                    }
                }
            }

            // Record today's site update check
            CheckUpdateSite::create([
                "check_site" => !empty($unupdatedSiteIds)
                    ? json_encode($unupdatedSiteIds)
                    : null,
                "check_by" => Auth::id() ?? 1,
                "check_status" => !empty($unupdatedSiteIds) ? "yes" : "no data",
                "check_date" => $today,
                "check_run_types" => "Auto",
                "check_type" => "site",
            ]);
        } else {
            // Log if today's check is already done
            // logger("ℹ️ '" . $today . "' Today's check already done. Skipping process.");
        }

        // Prepare site query based on user role
        $Sitequery = SiteDetail::query();
        if ($this->userRole == "supervisor") {
            $Sitequery->where("supervisor", $this->userId);
        }

        // Handle date range filters from request
        $dateRange = $request->input("date_range");
        $startDate = $request->input("start_date");
        $endDate = $request->input("end_date");

        if ($dateRange) {
            if ($dateRange === "last_30") {
                $Sitequery->whereDate(
                    "created_at",
                    ">=",
                    Carbon::now()->subDays(30)
                );
            } elseif ($dateRange === "last_90") {
                $Sitequery->whereDate(
                    "created_at",
                    ">=",
                    Carbon::now()->subDays(90)
                );
            } elseif ($dateRange === "last_180") {
                $Sitequery->whereDate(
                    "created_at",
                    ">=",
                    Carbon::now()->subDays(180)
                );
            } elseif ($dateRange === "one_year") {
                $Sitequery->whereDate(
                    "created_at",
                    ">=",
                    Carbon::now()->subYear()
                );
            } elseif ($dateRange === "last_year") {
                $Sitequery->whereYear(
                    "created_at",
                    Carbon::now()->subYear()->year
                );
            } else {
                if ($startDate && $endDate) {
                    $Sitequery->whereBetween("created_at", [
                        $startDate,
                        $endDate,
                    ]);
                }
            }
        }

        // Get filtered site details
        $SiteDetails = $Sitequery->get();

        // Get active/unupdated sites list based on role
        if ($this->userRole == "supervisor") {
            $activeSiteDetailsList = SiteDetail::whereIn("status", [
                "active",
                "unupdated",
                "standby",
            ])
                ->where("supervisor", $this->userId)
                ->get();
        } else {
            $activeSiteDetailsList = SiteDetail::whereIn("status", [
                "active",
                "unupdated",
                "standby",
            ])->get();
        }

        // Fetch all labour and material entries and lists
        $dailyLabourEntries = DailyLabourEntry::all();
        $dailyMaterialEntries = DailyMaterialEntry::all();
        $labourList = Labour::all();
        $materialList = Material::all();

        $upcomingPayments = [];

        // Calculate costs and payment info for each active site
        foreach ($activeSiteDetailsList as $site) {
            $labourCost = 0;
            $materialCost = 0;

            // Calculate labour cost for site
            $siteLabourEntries = $dailyLabourEntries->where(
                "site_id",
                $site->id
            );

            foreach ($siteLabourEntries as $entry) {
                $labourDataArray = json_decode($entry->labour_data, true);
                if (is_array($labourDataArray)) {
                    foreach ($labourDataArray as $labourData) {
                        $labour = $labourList
                            ->where("id", $labourData["labour_id"])
                            ->first();
                        if ($labour) {
                            $labourCost +=
                                $labour->dailywage * $labourData["shift"];
                        }
                    }
                }
            }

            // Calculate material cost for site
            $siteMaterialEntries = $dailyMaterialEntries->where(
                "site_id",
                $site->id
            );

            foreach ($siteMaterialEntries as $entry) {
                $materialDataArray = json_decode($entry->material_data, true);
                if (is_array($materialDataArray)) {
                    foreach ($materialDataArray as $materialData) {
                        $materialCost += $materialData["price"];
                    }
                }
            }

            // $totalCost = floatval($labourCost + $materialCost);
            $totalCost_lm = floatval($labourCost + $materialCost);
            $totalCost = floatval(1.25 * $totalCost_lm);
            $paymentReceived = floatval($site->payment_received);
            $billValue = floatval($site->bill_value);

            // Calculate inverse payment percentage with special case

            if ($paymentReceived == 0) {
                $paymentPercentage = 100;
            } elseif ($totalCost == 0) {
                $paymentPercentage = 0;
            } else {
                $paymentPercentage = ($totalCost / $paymentReceived) * 100;
            }

            // Format it nicely
            $paymentPercentageFormatted = number_format($paymentPercentage, 0);

            // Set status
            $status = "";
            // $status = $paymentPercentageFormatted > 60 ? 'critical' : '';

            //  if($formattedRatio < 100){
            if (env("MENU_SHOW") == "Yes") {
                $check_number = '';
            } else {
                $check_number = 50;
            }
              if($paymentPercentageFormatted > $check_number){
            $upcomingPayments[] = [
                "site_id" => $site->id,
                "site_name" => $site->name,
                "project_cost" => round($totalCost, 2),
                "payment_received" => round($paymentReceived, 2),
                "bill_value" => $billValue,
                "status" => $status,
                "project_cost_ration" => $paymentPercentageFormatted,
                "next_payment_date" => $site->next_payment_date,
            ];
            }
        }

        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        // Get all active sites where no labour entry exists for yesterday or today
        $unupdatedSites = SiteDetail::where("status", "active")
            ->whereDoesntHave("dailyLabourEntries", function ($query) use (
                $today,
                $yesterday
            ) {
                $query->whereIn("plan_of_date", [
                    $yesterday->toDateString(),
                    $today->toDateString(),
                ]);
            })
            ->get();

        // Get all active sites with labour entry for yesterday or today
        $activeSites = SiteDetail::where("status", "active")
            ->whereHas("dailyLabourEntries", function ($query) use (
                $today,
                $yesterday
            ) {
                $query->whereIn("plan_of_date", [$yesterday, $today]);
            })
            ->get();

        // Return dashboard view with all calculated data
        return view("pages.dashboard.dashboard", [
            "SiteDetails" => $SiteDetails,
            "upcomingPayments" => $upcomingPayments,
            "unupdatedSites" => $unupdatedSites,
            "activeSites" => $activeSites,
        ]);
    }
}
