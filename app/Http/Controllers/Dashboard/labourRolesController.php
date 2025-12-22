<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LabourRole;

class LabourRolesController extends Controller
{
    /**
     * Display a listing of the labour roles.
     */
    public function index()
    {
        $labourRoles = LabourRole::all();
        return view('pages.labour_roles.index', compact('labourRoles'));
    }

    /**
     * Show the form for creating a new labour role.
     */
    public function create()
    {
        $labourRolesList = LabourRole::all(); // For parent role dropdown
        return view('pages.labour_roles.create', compact('labourRolesList'));
    }

    /**
     * Store a newly created labour role in storage.
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'name'      => 'required|string|max:20',
        ]);

        // Create and save labour role
        LabourRole::create([
            'name'      => $request->name,
            'parent_id' => $request->parent_id,
            'addedBy'   => auth()->id(),
        ]);

        return redirect()->route('labourRoles.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Labour Role created successfully.');
    }

    /**
     * Show the form for editing the specified labour role.
     */
    public function edit($id)
    {
        $labourRoles = LabourRole::findOrFail($id);
        $labourRolesList = LabourRole::all();
        return view('pages.labour_roles.edit', compact('labourRoles', 'labourRolesList'));
    }

    /**
     * Display the specified labour role.
     */
    public function show($id)
    {
        $labourRoles = LabourRole::findOrFail($id);
        $labourRolesList = LabourRole::all();
        return view('pages.labour_roles.show', compact('labourRoles', 'labourRolesList'));
    }

    /**
     * Update the specified labour role in storage.
     */
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'name'      => 'required|string|max:20',
        ]);
        
        $labourRoles = LabourRole::findOrFail($id);
        
        $labourRoles->update([
            'name'      => $request->name,
            'parent_id' => $request->parent_id,
        ]);
        
        // dd($labourRoles);
        return redirect()->route('labourRoles.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Labour Role updated successfully.');
    }

    /**
     * Toggle the status of the specified labour role (custom logic needed).
     */
    public function statusUpdate($id)
    {
        $role = LabourRole::findOrFail($id);
        if($role->status == 'active')
        {
           $role->status = 'deactivate';
        }else{
            $role->status = 'active';
        }
        $role->save();

        return redirect()->route('labourRoles.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Labour Role status updated successfully.');
    }

    /**
     * Remove the specified labour role from storage.
     */
    public function delete($id)
    {
        //  $role = LabourRole::destroy($id);
         $role = LabourRole::find($id);
         $role->status = 'deactivate';
         $role->save();
        return redirect()->route('labourRoles.Index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Labour Role deactivate successfully.');
    }
}
