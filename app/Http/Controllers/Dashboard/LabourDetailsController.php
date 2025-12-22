<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Labour;
use App\Models\Remarks;
use App\Models\LabourRole;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LabourDetailsController extends Controller
{
    public $statuslist = [
        ['title' => 'active'],
        ['title' => 'deactivate']
    ];

    public function index(Request $request)
    {
        $labourQuery = Labour::query();
        $dateRange = $request->input('date_range');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $today = Carbon::today();

        if ($dateRange) {
            if ($dateRange === 'last_30') {
                $labourQuery->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'last_90') {
                $labourQuery->whereDate('created_at', '>=', Carbon::now()->subDays(90))
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'last_180') {
                $labourQuery->whereDate('created_at', '>=', Carbon::now()->subDays(180))
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'one_year') {
                $labourQuery->whereDate('created_at', '>=', Carbon::now()->subYear())
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'last_year') {
                $labourQuery->whereYear('created_at', Carbon::now()->subYear()->year)
                            ->whereDate('created_at', '<=', $today);
            } else {
                if ($startDate && $endDate) {
                    // Ensure endDate does not exceed today
                    $endDate = Carbon::parse($endDate)->gt($today) ? $today : $endDate;
                    $labourQuery->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        }

        $labourDetails = $labourQuery->get();
        return view('pages.labour.index', compact('labourDetails'));
    }

    public function create()
    {
        $labour_role = LabourRole::where('status', 'active')->get();
        $labour_count = Labour::count() + 1; // Generate a new labour ID based on count
        $labour_id = 'EMP-' . $labour_count;
        return view('pages.labour.create', [
            'labour_role' => $labour_role,
            'labour_id' => $labour_id,
            'statuslist' => $this->statuslist
        ]);
    }

    public function store(Request $request)
    {
        // Validate form inputs
        $request->validate([
            'labour_id' => ['required', 'string', 'unique:labours,labour_id'],
            'name' => 'required|string|max:100',
            'role' => 'required|integer|exists:labour_roles,id',
            'phoneno' => ['required', 'regex:/^(\+91[\-\s]?)?\d{10}$/'],
            'aadhar_no' => ['required', 'digits:12', 'unique:labours,aadhar_no'],
            'dailywage' => 'required|numeric|min:0',
            'status' => 'required|in:active,close,deactivate',
        ], [
            'phoneno.regex' => 'Enter a valid 10-digit phone number (with or without +91).'
        ]);

        // Save labour details
        $labour = new Labour();
        $labour->labour_id = $request->labour_id;
        $labour->name = $request->name;
        $labour->role = $request->role;
        $labour->phoneno = $request->phoneno;
        $labour->aadhar_no = $request->aadhar_no;
        $labour->dailywage = $request->dailywage;
        $labour->status = $request->status;
        $labour->addedBy = Auth::user()->id;

        if ($request->login_access == 'yes') {
            $role_name = LabourRole::find($request->role);
            $labour->email = $request->email;
            $labour->password = $request->password;
            $labour->login_access = 'yes';
        } else {
            $labour->login_access = 'no';
        }
        $labour->save();

        // Create new user
        if ($request->login_access == 'yes') {
            $newUser = new User();
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($request->password);
            $newUser->role = $role_name->name;
            $newUser->phone_no = $request->phoneno;
            $newUser->status = 'active';
            $newUser->profile_image = 'default';
            $newUser->remember_token = null;
            // $newUser->user_id = $labour->id;
            // $newUser->save();
        }

        return redirect()->route('labour.Index')
            ->with('bg-color', 'success')
            ->with('success', 'Labour detail created successfully.');
    }

    public function show($id)
    {
        $labourDetail = Labour::findOrFail($id);
        $RemarksList = Remarks::where('remarkable_type', 'Labour')
            ->where('remarkable_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.labour.show', compact('labourDetail', 'RemarksList'));
    }

    public function edit($id)
    {
        $labour_role = LabourRole::where('status', 'active')->get();
        $labourDetail = Labour::findOrFail($id);
        $RemarksList = Remarks::where('remarkable_type', 'Labour')
            ->where('remarkable_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.labour.edit', [
            'labourDetail' => $labourDetail,
            'labour_role' => $labour_role,
            'RemarksList' => $RemarksList,
            'statuslist' => $this->statuslist
        ]);
    }

    public function update(Request $request, $id)
    {
        // Validate inputs
        $request->validate([
            'name' => 'required|string|max:100',
            'role' => 'required|integer|exists:labour_roles,id',
            'phoneno' => ['required', 'regex:/^(\+91[\-\s]?)?\d{10}$/'],
            'aadhar_no' => ['nullable', 'digits:12', Rule::unique('labours')->ignore($id)],
            'dailywage' => 'required|numeric|min:0',
            'status' => 'required|in:active,close,deactivate',
            'remark' => 'nullable|string|max:255|required_if:status,deactivate',
        ], [
            'phoneno.regex' => 'Enter a valid 10-digit phone number (with or without +91).'
        ]);

        // Update labour details
        $updatelabour = Labour::findOrFail($id);
        $updatelabour->name = $request->name;
        $updatelabour->role = $request->role;
        $updatelabour->phoneno = $request->phoneno;
        $updatelabour->aadhar_no = $request->aadhar_no;
        $updatelabour->dailywage = $request->dailywage;
        $updatelabour->status = $request->status;

        if ($request->status == 'deactivate') {
            $saveRemarks = new Remarks();
            $saveRemarks->remarkable_type = 'Labour';
            $saveRemarks->remarkable_id = $updatelabour->id;
            $saveRemarks->remark_type = 'labour-deactivate';
            $saveRemarks->remark_text = $request->remark;
            $saveRemarks->remark_status = 'active';
            $saveRemarks->addedBy = Auth::user()->id;
            $saveRemarks->save();

            $userAccesBlock = User::where('emp_id', $id)->first();
            if ($userAccesBlock) {
                $userAccesBlock->status = 'deactivate';
                $userAccesBlock->save();
            }
        }

        if ($request->login_access == 'yes') {
            $role_name = LabourRole::find($request->role);
            $updatelabour->email = $request->email;
            $updatelabour->password = $request->password;
            $updatelabour->login_access = 'yes';

            $newUser = new User();
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->password = Hash::make($request->password);
            $newUser->role = $role_name->name;
            $newUser->phone_no = $request->phoneno;
            $newUser->status = 'active';
            // $newUser->user_id = $id;
            $newUser->profile_image = 'default';
            $newUser->remember_token = null;
            // $newUser->save();
        }

        if ($request->status == 'active') {
            $userAccesAllow = User::where('emp_id', $id)->first();
            if ($userAccesAllow) {
                $userAccesAllow->status = 'active';
                $userAccesAllow->save();
            }
        }

        if ($request->login_access == 'no') {
            $updatelabour->login_access = 'no';
        }

        $updatelabour->save();

        return redirect()->route('labour.Index')
            ->with('bg-color', 'success')
            ->with('success', 'Labour detail updated successfully.');
    }

    public function statusUpdate($id)
    {
        $labour = Labour::findOrFail($id);

        // Toggle status
        if ($labour->status === 'active') {
            $labour->status = 'deactivate';
        } elseif ($labour->status === 'deactivate') {
            $labour->status = 'active';
        } else {
            $labour->status = 'active'; // default fallback
        }

        $labour->save();

        return redirect()->route('labour.Index')
            ->with('bg-color', 'success')
            ->with('success', 'Labour status updated successfully.');
    }

    public function delete($id)
    {
        Labour::destroy($id);
        return redirect()->route('labour.Index')
            ->with('bg-color', 'success')
            ->with('success', 'Labour detail deleted successfully.');
    }
}

