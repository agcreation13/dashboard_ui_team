# Inventory Management Application - Implementation Plan

## Overview
This document outlines the step-by-step implementation plan for building the Inventory Management Application based on the requirements.

---

## Phase 1: Setup & Dependencies

### 1.1 Install Required Packages
- [x] Laravel Excel (maatwebsite/excel) - For Excel import/export
- [ ] DomPDF (dompdf/dompdf) - For PDF invoice generation
- [ ] Verify all dependencies are compatible with Laravel 12

### 1.2 Database Structure
Create migrations for:
- [ ] Categories table
- [ ] Products table
- [ ] Customers table
- [ ] Invoices table
- [ ] Invoice Items table
- [ ] Stock History table (optional for future)

---

## Phase 2: Models & Relationships

### 2.1 Create Models
- [ ] Category Model (with relationships)
- [ ] Product Model (with relationships)
- [ ] Customer Model (with relationships)
- [ ] Invoice Model (with relationships)
- [ ] InvoiceItem Model (with relationships)

### 2.2 Define Relationships
- Category → Products (One to Many)
- Product → Category (Many to One)
- Customer → Invoices (One to Many)
- Invoice → Customer (Many to One)
- Invoice → InvoiceItems (One to Many)
- InvoiceItem → Product (Many to One)

---

## Phase 3: Category Management Module

### 3.1 Controller
- [ ] CategoryController (CRUD operations)
  - index() - List all categories
  - create() - Show create form
  - store() - Save new category
  - edit() - Show edit form
  - update() - Update category
  - delete() - Delete category (with validation)

### 3.2 Views
- [ ] categories/index.blade.php - List view with GridJS
- [ ] categories/create.blade.php - Create form
- [ ] categories/edit.blade.php - Edit form

### 3.3 Routes
- [ ] GET /inventory/categories - List
- [ ] GET /inventory/categories/create - Create form
- [ ] POST /inventory/categories - Store
- [ ] GET /inventory/categories/{id}/edit - Edit form
- [ ] PUT /inventory/categories/{id} - Update
- [ ] DELETE /inventory/categories/{id} - Delete

---

## Phase 4: Product Management Module

### 4.1 Controller
- [ ] ProductController (CRUD + Import/Export)
  - index() - List all products
  - create() - Show create form
  - store() - Save new product
  - edit() - Show edit form
  - update() - Update product
  - delete() - Delete product
  - import() - Show import form
  - importStore() - Process Excel import
  - export() - Export products to Excel
  - downloadSample() - Download sample Excel template

### 4.2 Excel Import/Export
- [ ] Create Import class (ProductsImport)
- [ ] Create Export class (ProductsExport)
- [ ] Create Sample Excel template generator
- [ ] Validation for duplicate SKU
- [ ] Error handling for invalid rows

### 4.3 Views
- [ ] products/index.blade.php - List view
- [ ] products/create.blade.php - Create form
- [ ] products/edit.blade.php - Edit form
- [ ] products/import.blade.php - Import form

### 4.4 Routes
- [ ] GET /inventory/products - List
- [ ] GET /inventory/products/create - Create form
- [ ] POST /inventory/products - Store
- [ ] GET /inventory/products/{id}/edit - Edit form
- [ ] PUT /inventory/products/{id} - Update
- [ ] DELETE /inventory/products/{id} - Delete
- [ ] GET /inventory/products/import - Import form
- [ ] POST /inventory/products/import - Process import
- [ ] GET /inventory/products/export - Export
- [ ] GET /inventory/products/sample - Download sample

---

## Phase 5: Customer Management Module

### 5.1 Controller
- [ ] CustomerController (CRUD)
  - index() - List all customers
  - create() - Show create form
  - store() - Save new customer
  - edit() - Show edit form
  - update() - Update customer
  - delete() - Delete customer
  - show() - View customer with invoice history

### 5.2 Views
- [ ] customers/index.blade.php - List view
- [ ] customers/create.blade.php - Create form
- [ ] customers/edit.blade.php - Edit form
- [ ] customers/show.blade.php - View customer details

### 5.3 Routes
- [ ] GET /inventory/customers - List
- [ ] GET /inventory/customers/create - Create form
- [ ] POST /inventory/customers - Store
- [ ] GET /inventory/customers/{id} - Show
- [ ] GET /inventory/customers/{id}/edit - Edit form
- [ ] PUT /inventory/customers/{id} - Update
- [ ] DELETE /inventory/customers/{id} - Delete

---

## Phase 6: Invoice Management Module

### 6.1 Controller
- [ ] InvoiceController (CRUD + PDF)
  - index() - List all invoices
  - create() - Show create form
  - store() - Save invoice & update stock
  - show() - View invoice
  - edit() - Show edit form (if needed)
  - update() - Update invoice
  - cancel() - Cancel invoice & restore stock
  - downloadPDF() - Generate PDF invoice
  - print() - Print invoice view

### 6.2 PDF Generation
- [ ] Create Invoice PDF template
- [ ] Configure DomPDF
- [ ] Format invoice according to requirements

### 6.3 Stock Management
- [ ] Auto-update stock on invoice creation
- [ ] Restore stock on invoice cancellation
- [ ] Low stock warning logic

### 6.4 Views
- [ ] invoices/index.blade.php - List view
- [ ] invoices/create.blade.php - Create form (with dynamic items)
- [ ] invoices/show.blade.php - View invoice
- [ ] invoices/print.blade.php - Print-friendly view
- [ ] invoices/pdf.blade.php - PDF template

### 6.5 Routes
- [ ] GET /inventory/invoices - List
- [ ] GET /inventory/invoices/create - Create form
- [ ] POST /inventory/invoices - Store
- [ ] GET /inventory/invoices/{id} - Show
- [ ] GET /inventory/invoices/{id}/edit - Edit form
- [ ] PUT /inventory/invoices/{id} - Update
- [ ] GET /inventory/invoices/{id}/cancel - Cancel
- [ ] GET /inventory/invoices/{id}/pdf - Download PDF
- [ ] GET /inventory/invoices/{id}/print - Print view

---

## Phase 7: Inventory Management

### 7.1 Stock Management Logic
- [ ] Update stock on invoice creation
- [ ] Restore stock on invoice cancellation
- [ ] Manual stock update functionality
- [ ] Low stock warning (threshold check)

### 7.2 Views
- [ ] inventory/index.blade.php - Stock list with filters
- [ ] inventory/update.blade.php - Manual stock update form

### 7.3 Routes
- [ ] GET /inventory/stock - List stock
- [ ] GET /inventory/stock/{id}/update - Update form
- [ ] PUT /inventory/stock/{id} - Update stock

---

## Phase 8: Reports Module

### 8.1 Controller
- [ ] ReportController
  - stockReport() - Product stock report
  - categoryReport() - Category-wise product report
  - invoiceReport() - Date-wise invoice report
  - salesSummary() - Sales summary report

### 8.2 Views
- [ ] reports/stock.blade.php - Stock report
- [ ] reports/category.blade.php - Category report
- [ ] reports/invoice.blade.php - Invoice report
- [ ] reports/sales.blade.php - Sales summary

### 8.3 Routes
- [ ] GET /inventory/reports/stock - Stock report
- [ ] GET /inventory/reports/category - Category report
- [ ] GET /inventory/reports/invoice - Invoice report
- [ ] GET /inventory/reports/sales - Sales summary

---

## Phase 9: UI/UX Integration

### 9.1 Sidebar Menu
- [ ] Add Inventory Management menu section
  - Dashboard
  - Categories
  - Products (with submenu: Add, Import, Export)
  - Inventory
  - Invoices
  - Customers
  - Reports
  - Users (Admin only - already exists)
  - Settings

### 9.2 Role-Based Access
- [ ] Admin: Full access
- [ ] Normal User: Limited access (no settings, no user management)
- [ ] Update middleware checks

### 9.3 Dashboard
- [ ] Create inventory dashboard
- [ ] Show key metrics (total products, low stock items, recent invoices)

---

## Phase 10: Validation & Security

### 10.1 Form Validation
- [ ] Server-side validation for all forms
- [ ] Custom validation rules (SKU uniqueness, stock availability)
- [ ] Error messages display

### 10.2 Security
- [ ] CSRF protection (already in Laravel)
- [ ] Input sanitization
- [ ] File upload validation
- [ ] Role-based route protection

---

## Phase 11: Testing & Optimization

### 11.1 Testing
- [ ] Test all CRUD operations
- [ ] Test Excel import/export
- [ ] Test PDF generation
- [ ] Test stock updates
- [ ] Test invoice cancellation
- [ ] Test role-based access

### 11.2 Optimization
- [ ] Optimize database queries
- [ ] Add indexes where needed
- [ ] Optimize Excel import for large files
- [ ] Cache frequently accessed data

---

## Phase 12: Final Polish

### 12.1 Error Handling
- [ ] Comprehensive error messages
- [ ] User-friendly error pages
- [ ] Logging for debugging

### 12.2 Documentation
- [ ] Code comments
- [ ] User guide (optional)

---

## Implementation Order

1. ✅ Setup & Dependencies
2. ✅ Database Migrations
3. ✅ Models & Relationships
4. ✅ Category Module (simplest, start here)
5. ✅ Product Module (core functionality)
6. ✅ Customer Module
7. ✅ Invoice Module (most complex)
8. ✅ Inventory Management
9. ✅ Reports Module
10. ✅ UI Integration
11. ✅ Testing & Fixes

---

## Notes

- Follow existing code patterns (GridJS tables, form structure)
- Use existing theme assets
- Maintain consistency with current UI/UX
- Ensure all validations are server-side
- Test each module before moving to next
- Keep code clean and well-commented

