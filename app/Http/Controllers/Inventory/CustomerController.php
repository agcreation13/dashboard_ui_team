<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = Customer::withCount('invoices')->orderBy('created_at', 'desc')->get();
        return view('pages.inventory.customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.inventory.customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
        ]);

        Customer::create($request->all());

        return redirect()->route('customers.index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $customer = Customer::with('invoices.items')->findOrFail($id);
        return view('pages.inventory.customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('pages.inventory.customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'gstin' => 'nullable|string|max:50',
            'state' => 'nullable|string|max:100',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($request->all());

        return redirect()->route('customers.index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        
        // Check if customer has invoices
        if ($customer->invoices()->count() > 0) {
            return redirect()->route('customers.index')
                             ->with('bg-color', 'danger')
                             ->with('success', 'Cannot delete customer. They have associated invoices.');
        }

        $customer->delete();

        return redirect()->route('customers.index')
                         ->with('bg-color', 'success')
                         ->with('success', 'Customer deleted successfully.');
    }
}
