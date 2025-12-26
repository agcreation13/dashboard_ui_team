<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Dashboard\UsersListController;
use App\Http\Controllers\Dashboard\UsersRoleListController;
use App\Http\Controllers\Inventory\CategoryController;
use App\Http\Controllers\Inventory\ProductController;
use App\Http\Controllers\Inventory\CustomerController;
use App\Http\Controllers\Inventory\InvoiceController;
use App\Http\Controllers\Inventory\InventoryController;
use App\Http\Controllers\Inventory\ReportController;


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

   // User-sheet 
   Route::get('/dashboard', [UsersListController::class, 'index'])->name('dashboard');

   Route::get('/master-entry/user-list', [UsersListController::class, 'index'])->name('Uers.Index');
   Route::get('/master-entry/user-list/create', [UsersListController::class, 'create'])->name('Uers.Create');
   Route::post('/master-entry/user-list/store', [UsersListController::class, 'store'])->name('Uers.Store');
   Route::get('/master-entry/user-list/edit/{id}', [UsersListController::class, 'edit'])->name('Uers.Edit');
   Route::get('/master-entry/user-list/show/{id}', [UsersListController::class, 'show'])->name('Uers.Show');
   Route::put('/master-entry/user-list/update/{id}', [UsersListController::class, 'update'])->name('Uers.Update');
   Route::get('/master-entry/user-list/status/{id}', [UsersListController::class, 'statusUpdate'])->name('Uers.StatusUpdate');
   Route::get('/master-entry/user-list/delete/{id}', [UsersListController::class, 'delete'])->name('Uers.Delete');
    
   // User-sheet 
   Route::get('/master-entry/user-role', [UsersRoleListController::class, 'index'])->name('UersRole.Index');
   Route::get('/master-entry/user-role/create', [UsersRoleListController::class, 'create'])->name('UersRole.Create');
   Route::post('/master-entry/user-role/store', [UsersRoleListController::class, 'store'])->name('UersRole.Store');
   Route::get('/master-entry/user-role/edit/{id}', [UsersRoleListController::class, 'edit'])->name('UersRole.Edit');
   Route::get('/master-entry/user-role/show/{id}', [UsersRoleListController::class, 'show'])->name('UersRole.Show');
   Route::put('/master-entry/user-role/update/{id}', [UsersRoleListController::class, 'update'])->name('UersRole.Update');
   Route::get('/master-entry/user-role/status/{id}', [UsersRoleListController::class, 'statusUpdate'])->name('UersRole.StatusUpdate');
   Route::get('/master-entry/user-role/delete/{id}', [UsersRoleListController::class, 'delete'])->name('UersRole.Delete');
   
   // Inventory - Categories
   Route::resource('inventory/categories', CategoryController::class)->names([
       'index' => 'categories.index',
       'create' => 'categories.create',
       'store' => 'categories.store',
       'show' => 'categories.show',
       'edit' => 'categories.edit',
       'update' => 'categories.update',
       'destroy' => 'categories.destroy',
   ]);

   // Inventory - Products
   Route::resource('inventory/products', ProductController::class)->names([
       'index' => 'products.index',
       'create' => 'products.create',
       'store' => 'products.store',
       'show' => 'products.show',
       'edit' => 'products.edit',
       'update' => 'products.update',
       'destroy' => 'products.destroy',
   ]);
  Route::get('inventory/products/import', [ProductController::class, 'import'])->name('products.import');
  Route::post('inventory/products/import', [ProductController::class, 'importStore'])->name('products.import.store');
  Route::get('inventory/products/export', [ProductController::class, 'export'])->name('products.export');
  Route::get('inventory/products/sample', [ProductController::class, 'downloadSample'])->name('products.sample');
  Route::put('inventory/products/{id}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');

   // Inventory - Customers
   Route::resource('inventory/customers', CustomerController::class)->names([
       'index' => 'customers.index',
       'create' => 'customers.create',
       'store' => 'customers.store',
       'show' => 'customers.show',
       'edit' => 'customers.edit',
       'update' => 'customers.update',
       'destroy' => 'customers.destroy',
   ]);

   // Inventory - Invoices
   Route::resource('inventory/invoices', InvoiceController::class)->names([
       'index' => 'invoices.index',
       'create' => 'invoices.create',
       'store' => 'invoices.store',
       'show' => 'invoices.show',
       'edit' => 'invoices.edit',
       'update' => 'invoices.update',
       'destroy' => 'invoices.destroy',
   ]);
   Route::get('inventory/invoices/{id}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
   Route::get('inventory/invoices/{id}/pdf', [InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
   Route::get('inventory/invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');

  // Inventory - Stock Management (Redirected to Products)
  Route::get('inventory/stock', function() {
      return redirect()->route('products.index');
  })->name('inventory.stock');

   // Inventory - Reports
   Route::get('inventory/reports', [ReportController::class, 'index'])->name('reports.index');
   Route::get('inventory/reports/stock', [ReportController::class, 'stockReport'])->name('reports.stock');
   Route::get('inventory/reports/category', [ReportController::class, 'categoryReport'])->name('reports.category');
   Route::get('inventory/reports/invoice', [ReportController::class, 'invoiceReport'])->name('reports.invoice');
   Route::get('inventory/reports/sales', [ReportController::class, 'salesSummary'])->name('reports.sales');
   
});


require __DIR__.'/auth.php';
