<?php

namespace App\Services;

use App\Models\CheckUpdateSite;
use App\Models\LeadsFollow;
use App\Models\SiteDetail;
use App\Models\Leadsheet;
use App\Models\DailyLabourEntry;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DailyUpdateChecker
{
    
  public function checkUpdaterun()
    {
        // Initialize variables
        $today = Carbon::today()->toDateString();
        $userId = Auth::user()->id;
        
        // ==================== LEADS SECTION ====================
        $unupdatedLeadIds = [];
        $leadsList = Leadsheet::whereIn('leads_status', ['good', 'open', 'unupdated'])->get();

        // Process each lead for follow-up status
        foreach ($leadsList as $lead) {
            $latestFollow = $lead->latestFollow;

            if ($latestFollow) {
                $nextDate = Carbon::parse($latestFollow->leads_follow_next_date);

                // Check if next follow-up date has passed
                if ($nextDate->lt($today)) {
                    // Check if any follow-up entry exists from next date onwards
                    $hasEntry = $lead->leadFollows()
                        ->whereDate('leads_follow_date', '>=', $nextDate->toDateString())
                        ->exists();

                    if (!$hasEntry) {
                        // No follow-up found - mark as unupdated
                        $lead->leads_status = 'unupdated';
                        $lead->save();
                        $unupdatedLeadIds[] = $lead->id;
                    } elseif ($lead->leads_status === 'unupdated') {
                        // Follow-up found - restore to open
                        $lead->leads_status = 'open';
                        $lead->save();
                    }
                } else {
                    // Next follow-up date is in the future
                    if ($lead->leads_status === 'unupdated') {
                        $lead->leads_status = 'open';
                        $lead->save();
                    }
                }
            }
        }

        // Log leads check results
        $CheckUpdateLeadsLogs = CheckUpdateSite::firstOrNew([
            'check_date' => $today,
            'check_type' => 'lead',
        ]);

        $CheckUpdateLeadsLogs->check_site      = $unupdatedLeadIds ? json_encode($unupdatedLeadIds) : null;
        $CheckUpdateLeadsLogs->check_by        = $userId;
        $CheckUpdateLeadsLogs->check_status    = $unupdatedLeadIds ? 'yes' : 'no data';
        $CheckUpdateLeadsLogs->check_run_types = $CheckUpdateLeadsLogs->exists ? 'Update' : 'Manual';
        $CheckUpdateLeadsLogs->save();

        // ==================== SITES SECTION ====================
        $unupdatedSiteIds = [];
        $sitesList = SiteDetail::whereIn('status', ['active', 'unupdated', 'standby'])
            ->whereDate('start_date', '<=', $today)
            ->get();

        // Process each site for daily entry status
        foreach ($sitesList as $site) {
            $lastEntry = $site->dailyLabourEntries()->latest('plan_of_date')->first();
            
            if ($site->status === 'standby') {
                // ==================== STANDBY SITES ====================
                // Auto-insert today's entry if not exists
                $hasEntryStandby = $site->dailyLabourEntries()
                    ->whereDate('plan_of_date', $today)
                    ->exists();
                    
                if (!$hasEntryStandby) {
                    $newDailyLabourEntry = new DailyLabourEntry();
                    $newDailyLabourEntry->site_id = $site->id;
                    $newDailyLabourEntry->plan_of_date = $today;
                    $newDailyLabourEntry->plan_message = 'This Site is on Standby';
                    $newDailyLabourEntry->labour_data = json_encode([]);
                    $newDailyLabourEntry->added_by = Auth::id() ?? 1;
                    $newDailyLabourEntry->save();
                }
            } else {
                // ==================== ACTIVE/UNUPDATED SITES ====================
                if ($site->status === 'unupdated' || $site->status === 'active') {
                    $startDate = Carbon::parse($site->start_date);
                    $todayDate = Carbon::parse($today);
                    
                    // Check if any entry exists from start_date to today
                    $hasAnyEntry = $site->dailyLabourEntries()
                        ->whereDate('plan_of_date', '>=', $startDate->toDateString())
                        ->whereDate('plan_of_date', '<=', $todayDate->toDateString())
                        ->exists();
                    
                    // Check for missing dates from start_date to today
                    $missingDates = [];
                    $currentDate = $startDate->copy();
                    
                    while ($currentDate->lte($todayDate)) {
                        $checkDate = $currentDate->toDateString();
                        $hasEntryForDate = $site->dailyLabourEntries()
                            ->whereDate('plan_of_date', $checkDate)
                            ->exists();
                        
                        if (!$hasEntryForDate) {
                            $missingDates[] = $checkDate;
                        }
                        
                        $currentDate->addDay();
                    }
                    
                    if (!empty($missingDates)) {
                        // Missing dates found - mark as unupdated
                        $site->status = 'unupdated';
                        $site->save();
                        $unupdatedSiteIds[] = $site->id;
                    } elseif ($site->status === 'unupdated') {
                        // No missing dates - restore to active
                        $site->status = 'active';
                        $site->save();
                    }
                }
            }
        }

        // Log sites check results
        $CheckUpdateSiteLogs = CheckUpdateSite::firstOrNew([
            'check_date' => $today,
            'check_type' => 'site',
        ]);

        $CheckUpdateSiteLogs->check_site      = $unupdatedSiteIds ? json_encode($unupdatedSiteIds) : null;
        $CheckUpdateSiteLogs->check_by        = $userId;
        $CheckUpdateSiteLogs->check_status    = $unupdatedSiteIds ? 'yes' : 'no data';
        $CheckUpdateSiteLogs->check_run_types = $CheckUpdateSiteLogs->exists ? 'Update' : 'Manual';
        $CheckUpdateSiteLogs->save();

        // return redirect('/');
    }


}
