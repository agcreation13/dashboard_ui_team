<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleRoutePermission;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\RouteURLList;

class UsersRoleListController extends Controller
{
    /**
     * Display a listing of the Users-roles.
     */
    public function index()
    
    {
        // $roleList = RoleRoutePermission::all();
            $roleList = RoleRoutePermission::where('status', '!=', 'deactivate')->get();
        return view('pages.users-roles.index', compact('roleList'));
    }

    /**
     * Show the form for creating a new User.
     */
    public function create()
    {
        // roledata
        $routeList = RouteURLList::all(); // For parent role dropdown
        return view('pages.users-roles.create', compact('routeList'));
    }

    /**
     * Store a newly created User in storage.
     */
    public function store(Request $request)
    {
        // 'role'       => 'required|string|exists:role_route_permissions,id',
        // Validation
      $request->validate([
    'role_name' => 'required|string|max:255',
    'url_ids' => 'required|array',
    'url_ids.*' => 'integer',
]);

    //    dd($request->url_ids);
        // $urlIds = array_map('intval', $request->url_ids ?? []);
        $userRoleStore = new RoleRoutePermission();
        $userRoleStore->role_name = $request->role_name;
        $userRoleStore->url_ids =  json_encode(array_map('intval', $request->url_ids));
        $userRoleStore->status = 'active';
        $userRoleStore->save();

        return redirect()->route('UersRole.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified User.
     */
    public function edit($id)
    {
        $roledata = RoleRoutePermission::findOrFail($id);
        $routeIds = array_map('strval', json_decode($roledata->url_ids ?? '[]', true));
        $routeList = RouteURLList::all();
        // dd($routeIds);
        return view('pages.users-roles.edit', compact('roledata', 'routeList','routeIds'));
    }

    /**
     * Display the specified User.
     */
    public function show($id)
    {
       $roledata = RoleRoutePermission::findOrFail($id);
        $routeList = RouteURLList::all();
        return view('pages.users-roles.show', compact('roledata', 'routeList'));
    }

    /**
     * Update the specified User in storage.
     */
    public function update(Request $request, $id)
    {
        // 'role'       => 'required|string|exists:role_route_permissions,id',
        // Validation
     $request->validate([
    'role_name' => 'required|string|max:255',
    'url_ids' => 'required|array',
      ]);

        $userRoleUpdate = RoleRoutePermission::find($id);
        $userRoleUpdate->role_name = $request->role_name;
        $userRoleUpdate->url_ids = json_encode(array_map('intval', $request->url_ids));
        $userRoleUpdate->save();
        
        // dd($labourRoles);
        return redirect()->route('UersRole.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User updated successfully.');
    }

    /**
     * Toggle the status of the specified User (custom logic needed).
     */
    public function statusUpdate($id)
    {
        $Users = RoleRoutePermission::findOrFail($id);
        if($Users->status == 'deactivate'){
            $Users->status = 'active';
        }
        else{
            $Users->status = 'active';
        }

        $Users->save();

        return redirect()->route('UersRole.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User status updated successfully.');
    }

    /**
     * Remove the specified User from storage.
     */
    public function delete($id)
    {
        $Users = RoleRoutePermission::findOrFail($id);
        $Users->status = 'deactivate';
        $Users->save();
        // $Users->delete();
        return redirect()->route('UersRole.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'User deleted successfully.');
    }
}
