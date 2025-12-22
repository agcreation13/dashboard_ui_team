<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleRoutePermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Labour;
use Carbon\Carbon;

class UsersListController extends Controller
{
    /**
     * Display a listing of the Users.
     */
       public function index(Request $request)
    {
        $userListQuery = User::query();
        $dateRange = $request->input('date_range');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');
        $today = Carbon::today();

        if ($dateRange) {
            if ($dateRange === 'last_30') {
                $userListQuery->whereDate('created_at', '>=', Carbon::now()->subDays(30))
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'last_90') {
                $userListQuery->whereDate('created_at', '>=', Carbon::now()->subDays(90))
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'last_180') {
                $userListQuery->whereDate('created_at', '>=', Carbon::now()->subDays(180))
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'one_year') {
                $userListQuery->whereDate('created_at', '>=', Carbon::now()->subYear())
                            ->whereDate('created_at', '<=', $today);
            } elseif ($dateRange === 'last_year') {
                $userListQuery->whereYear('created_at', Carbon::now()->subYear()->year)
                            ->whereDate('created_at', '<=', $today);
            } else {
                if ($startDate && $endDate) {
                    // Ensure endDate does not exceed today
                    $endDate = Carbon::parse($endDate)->gt($today) ? $today : $endDate;
                    $userListQuery->whereBetween('created_at', [$startDate, $endDate]);
                }
            }
        }

        $userList = $userListQuery->where('status', '!=', 'close')->where('role', '!=', 'superadmin')->get();

        return view('pages.users.index', compact('userList'));
    }



    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
         $labourList = Labour::whereStatus('active')
    ->whereHas('labourRole', fn($q) =>
        $q->whereIn('name', ['representative', 'supervisor','other'])
        // $q->whereNotIn('name', ['representative', 'supervisor'])
    )
    ->get();
    // dd($labourList);
        $roleList = RoleRoutePermission::all(); // For parent role dropdown
        return view('pages.users.create', compact('roleList','labourList'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(Request $request)
    {
        // 'role'       => 'required|string|exists:role_route_permissions,id',
        // Validation
         $request->validate([
            'emp_id'   => 'required|string|unique:users,emp_id|max:50',
            'email'    => 'required|email|unique:users,email',
            'password'   => 'required|string',
            'role'   => 'required|string',
        ]);

        $labourdata = Labour::find($request->emp_id);
        $userDataStore = new User();
        $userDataStore->name = $labourdata->name;
        $userDataStore->email = $request->email;
        $userDataStore->password = Hash::make($request->password);
        $userDataStore->role = $request->role;
        $userDataStore->status = 'active';
        $userDataStore->email_verified_at = now();
        $userDataStore->remember_token = Str::random(60);
        $userDataStore->emp_id = $request->emp_id;
        $userDataStore->save();

        return redirect()->route('Uers.Index')
                         ->with('bg-color', 'success')
                         ->with('password', $request->password)
                         ->with('emailId', $request->email)
                         ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {  
    $labourList = Labour::whereStatus('active')
    ->whereHas('labourRole', fn($q) =>
        $q->whereIn('name', ['representative', 'supervisor','other'])
        // $q->whereNotIn('name', ['representative', 'supervisor'])
    )
    ->get();
        $userData = User::findOrFail($id);
        $roleList = RoleRoutePermission::all();
        return view('pages.users.edit', compact('userData', 'roleList','labourList'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
    $labourList = Labour::whereStatus('active')
    ->whereHas('labourRole', fn($q) =>
        $q->whereNotIn('name', ['representative', 'supervisor'])
    )
    ->get();
        $userData = User::findOrFail($id);
        $roleList = RoleRoutePermission::all();
        return view('pages.users.show', compact('userData', 'roleList','labourList'));
    }

    /**
     * Update the specified User in storage.
     */
    public function update(Request $request, $id)
    {
        // 'role'       => 'required|string|exists:role_route_permissions,id',
        // Validation
      $request->validate([
         'emp_id'   => 'required|string|max:50|unique:users,emp_id,' . $id,
         'email'    => 'required|email|unique:users,email,' . $id,
         'password' => $id ? 'nullable|string' : 'required|string',
         'role'     => 'required|string',
         ]);
        $labourdata = Labour::find($request->emp_id);
        $userUpdate = User::find($id);
        $userUpdate->name = $labourdata->name;
        $userUpdate->email = $request->email;
        if($request->changepassword == '1')
        {
            $userUpdate->password = hash::make($request->password);
        }
        $userUpdate->role = $request->role;
        $userUpdate->status = $labourdata->status;
        $userUpdate->emp_id = $request->emp_id;
        $userUpdate->save();
        
        // dd($labourRoles);
        return redirect()->route('Uers.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Toggle the status of the specified User (custom logic needed).
     */
    public function statusUpdate($id)
    {
        $Users = User::findOrFail($id);
        if($Users->status == 'deactivate'){
            $Users->status = 'active';
        }
        else{
            $Users->status = 'active';
        }

        $Users->save();

        return redirect()->route('Uers.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User status updated successfully.');
    }

    /**
     * Remove the specified User from storage.
     */
    public function delete($id)
    {
        $Users = User::findOrFail($id);
        $Users->status = 'close';
        $Users->save();
        return redirect()->route('Uers.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User deleted successfully.');
    }
}
