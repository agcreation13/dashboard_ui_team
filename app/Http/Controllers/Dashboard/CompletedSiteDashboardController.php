<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\SiteDetail;
use App\Models\DailyLabourEntry;
use App\Models\DailyMaterialEntry;
use App\Models\SiteBilling;
use App\Models\Labour;
use App\Models\Material;
use App\Models\PaymentReceipt;
use App\Models\Remarks;
use App\Models\CheckUpdateSite;

class CompletedSiteDashboardController extends Controller
{
    public function index()
    {
        $siteDetails = SiteDetail::whereIn('status', ['completed','close'])->get();
        // $siteDetails = SiteDetail::where('status', 'completed')->get();
        // Laradumps($siteDetails);
        return view('pages.site-details.completed', [
            'siteDetails' => $siteDetails,
        ]);
    }

    public function destroy($id)
    {
        $siteDetails = SiteDetail::findOrFail($id);

        DailyLabourEntry::where('site_id', $siteDetails->id)->delete();
        DailyMaterialEntry::where('site_id', $siteDetails->id)->delete();

        $siteBilling = SiteBilling::where('site_id', $siteDetails->id)->first();
        if ($siteBilling) {
            if (
                $siteBilling->site_bill_file &&
                file_exists(public_path('storage/' . $siteBilling->site_bill_file))
            ) {
                unlink(public_path('storage/' . $siteBilling->site_bill_file));
            }
            $siteBilling->delete();
        }

        $paymentReceipt = PaymentReceipt::where('site_id', $siteDetails->id)->first();
        if ($paymentReceipt) {
            // if (
            //     $paymentReceipt->receipts_file &&
            //     file_exists(public_path('storage/' . $paymentReceipt->receipts_file))
            // ) {
            //     unlink(public_path('storage/' . $paymentReceipt->receipts_file));
            // }
            $paymentReceipt->delete();
        }

        Remarks::where('remarkable_type', 'SiteDetail')->where('remarkable_id', $siteDetails->id)->delete();

        // Remove site ID from CheckUpdateSite records
        // $checkUpdateSites = CheckUpdateSite::all();
        // foreach ($checkUpdateSites as $checkUpdateSite) {
        //     $siteIds = json_decode($checkUpdateSite->check_site, true);
        //     if (!is_array($siteIds)) {
        //         continue;
        //     }
        //     if (in_array($siteDetails->id, $siteIds)) {
        //         $key = array_search($siteDetails->id, $siteIds);
        //         if ($key !== false) {
        //             unset($siteIds[$key]);
        //             $siteIds = array_values($siteIds);
        //             $checkUpdateSite->check_site = json_encode($siteIds);
        //             $checkUpdateSite->save();
        //         }
        //     }
        // }

        $siteDetails->delete();

        return redirect()->back()->with('success', 'Site Details deleted successfully.');
    }

    public function export($id)
    {

    $site_data = SiteDetail::find($id);
  
    $newfile = str_replace(' ', '-', $site_data->name);

    $filename = "Site-Report-{$newfile}-" . date('y-m-d') . ".csv";
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => "attachment; filename=\"$filename\"",
    ];

    return response()->streamDownload(function () use ($id) {
        $output = fopen('php://output', 'w');

        // UTF-8 BOM (so Excel opens in UTF-8)
        fwrite($output, "\xEF\xBB\xBF");

        // =====================
        // 1. Site Info
        // =====================
        $site = SiteDetail::find($id);

        fputcsv($output, ['=== Site Report ===']);
        fputcsv($output, ['Name', 'Type', 'Owner', 'Email', 'Phone No','Address','Representative','Supervisor', 'Mukadam', 'Designer Name','Designer Phone No','Start Date','Status','Project ID','Bill Value','Payment Received','Next Payment Date']);
        fputcsv($output, [
            $site->name ?? 'N/A',
            $site->site_type ?? 'N/A',
            $site->owner_name ?? 'N/A',
            $site->email ?? 'N/A',
            $site->phoneno ?? 'N/A',
            $site->address ?? 'N/A',
            $site->labour_r->name ?? 'N/A',
            $site->labour_s->name ?? 'N/A',
            $site->labour_m->name ?? 'N/A',
            $site->designer_name ?? 'N/A',
            $site->designer_phone_no ?? 'N/A',
            $site->start_date ?? 'N/A',
            $site->status ?? 'N/A',
            $site->project_id ?? 'N/A',
            $site->bill_value ?? 'N/A',
            $site->payment_received ?? 'N/A'
        ]);
        fputcsv($output, []); // blank line

         // =====================
// 2. Labour Work (Summary)
// =====================
fputcsv($output, ['=== Labour Work Summary ===']);
fputcsv($output, ['Labour Name', 'Role', 'Wage', 'Total Shifts', 'Total Payment']);

$labourEntries = DailyLabourEntry::where('site_id', $id)->get();

$labourSummary = []; 
$labourDays = [];  
// logger($labourEntries);
foreach ($labourEntries as $entry) {
    $labourData = json_decode($entry->labour_data, true);
    // logger($labourData);

    if (is_array($labourData)) {
        foreach ($labourData as $labour) {
            $labourModel = Labour::find($labour['labour_id'] ?? 0);

            $labourId = $labour['labour_id'] ?? null;
            if (!$labourId) continue;

            $name  = $labourModel->name ?? 'N/A';
            $role  = $labourModel->labourRole->name ?? 'N/A';
            $wage  = $labourModel->dailywage ?? 0;
            $shift = $labour['shift'] ?? 0;
            $date  = $entry->plan_of_date ?? null;

            // --- Fill Summary ---
            if (!isset($labourSummary[$labourId])) {
                $labourSummary[$labourId] = [
                    'name'   => $name,
                    'role'   => $role,
                    'wage'   => $wage,
                    'shifts' => 0,
                    'payment'=> 0,
                ];
            }
            $labourSummary[$labourId]['shifts']  += $shift;
            $labourSummary[$labourId]['payment'] += ($shift * $wage);

            // --- Track Days ---
            if (!isset($labourDays[$labourId])) {
                $labourDays[$labourId] = [
                    'name'  => $name,
                    'role'  => $role,
                    'dates' => [],
                    'shift' => [],
                ];
            }
            if ($date) {
                $labourDays[$labourId]['dates'][] = $date;
            }
            if ($shift) {
                $labourDays[$labourId]['shift'][] = $shift;
            }
        }
    }
}

// ✅ Write Summary rows
foreach ($labourSummary as $row) {
    fputcsv($output, [
        $row['name'],
        $row['role'],
        $row['wage'],
        $row['shifts'],
        $row['payment'],
    ]);
}

// empty row for spacing
fputcsv($output, []);

// =====================
// (Optional) Labour Work by Days
// =====================
fputcsv($output, ['=== Labour Work By Days ===']);
fputcsv($output, ['Labour Name', 'Dates Worked','Shifts']);

foreach ($labourDays as $row) {
    fputcsv($output, [
        $row['name'],
        implode(", ", $row['dates']),
        implode(", ", $row['shift']),
    ]);
}




        // =====================
        // 3. Material Usage
        // =====================
fputcsv($output, ['=== Material Usage Summary ===']);
fputcsv($output, ['Material Name', 'Unit Price', 'Total Qty Used', 'Total Cost']);

$materialEntries = DailyMaterialEntry::where('site_id', $id)->get();

$materialSummary = [];
$materialDays = [];

foreach ($materialEntries as $entry) {
    $materialData = json_decode($entry->material_data, true);

    if (is_array($materialData)) {
        foreach ($materialData as $mat) {
            $materialModel = Material::find($mat['id'] ?? 0);

            $matId = $mat['id'] ?? null;
            if (!$matId) continue;

            $name   = $materialModel->name ?? 'N/A';
            $price  = $materialModel->price_byunit ?? 0;
            $qty    = $mat['qty'] ?? 0;
            $date   = $entry->material_add_date ?? null;

            // --- Fill Summary ---
            if (!isset($materialSummary[$matId])) {
                $materialSummary[$matId] = [
                    'name'  => $name,
                    'price' => $price,
                    'qty'   => 0,
                    'cost'  => 0,
                ];
            }
            $materialSummary[$matId]['qty']  += $qty;
            $materialSummary[$matId]['cost'] += ($qty * $price);

            // --- Track Days ---
            if (!isset($materialDays[$matId])) {
                $materialDays[$matId] = [
                    'name'  => $name,
                    'dates' => [],
                    'qty'   => [],
                ];
            }
            if ($date) {
                $materialDays[$matId]['dates'][] = $date;
            }
            if ($qty) {
                $materialDays[$matId]['qty'][] = $qty;
            }
        }
    }
}

// ✅ Write Material Summary rows
foreach ($materialSummary as $row) {
    fputcsv($output, [
        $row['name'],
        $row['price'],
        $row['qty'],
        $row['cost'],
    ]);
}

// empty row for spacing
fputcsv($output, []);

// =====================
// (Optional) Material Work by Days
// =====================
fputcsv($output, ['=== Material Usage By Days ===']);
fputcsv($output, ['Material Name', 'Dates Used','Qty']);

foreach ($materialDays as $row) {
    fputcsv($output, [
        $row['name'],
        implode(", ", $row['dates']),
        implode(", ", $row['qty']),
    ]);
}

 fputcsv($output, []); // blank line
// remarks

// =====================
// 3. Remarks Report
// =====================
fputcsv($output, ['=== Site Standby Remarks ===']);
fputcsv($output, ['Remark', 'Date']);

$standbyRemarks = Remarks::where('remarkable_type','SiteDetail')->where('remarkable_id', $id)->where('remark_type', 'site-standby')->get();

foreach ($standbyRemarks as $remark) {
    fputcsv($output, [
        $remark->remark_text ?? 'N/A',
        $remark->created_at->format('Y-m-d'),
    ]);
}
logger($standbyRemarks);
fputcsv($output, []); // blank line between sections

fputcsv($output, ['=== Site Close Remarks ===']);
fputcsv($output, ['Remark', 'Date']);

$closeRemarks = Remarks::where('remarkable_type','SiteDetail')->where('remarkable_id', $id)->where('remark_type', 'site-close')->get();


foreach ($closeRemarks as $remark) {
    fputcsv($output, [
        $remark->remark_text ?? 'N/A',
        $remark->created_at->format('Y-m-d'),
    ]);
}
 fputcsv($output, []); // blank line
// // =====================
// 4. Site Billings Report
// =====================
fputcsv($output, ['=== Site Billings ===']);
fputcsv($output, ['Bill No', 'Bill Date', 'Bill Value (₹)', 'File']);

$siteBillings = SiteBilling::where('site_id', $id)->get();

foreach ($siteBillings as $bill) {
    fputcsv($output, [
        $bill->site_bill_no ?? 'N/A',
        $bill->site_bill_date ?? 'N/A',
        $bill->site_bill_value ?? '0.00',
        $bill->site_bill_file ?? 'N/A',
    ]);
}

fputcsv($output, []); // blank line between sections


// =====================
// 5. Payment Receipts Report
// =====================
fputcsv($output, ['=== Payment Receipts ===']);
fputcsv($output, ['Receipt No', 'Bill No', 'Receipt Date', 'Receipt Value (₹)', 'Mode']);

$paymentReceipts = PaymentReceipt::where('site_id', $id)->get();

foreach ($paymentReceipts as $receipt) {
    fputcsv($output, [
        $receipt->receipts_no ?? 'N/A',
        $receipt->site_bill_no ?? 'N/A',
        $receipt->receipts_date ?? 'N/A',
        $receipt->receipts_value ?? '0.00',
        $receipt->receipts_pay_type ?? 'N/A',
    ]);
}




        fclose($output);
    }, $filename, $headers);


    }
}
