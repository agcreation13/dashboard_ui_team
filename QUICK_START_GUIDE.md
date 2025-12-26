# Quick Start Guide - Inventory Management Application

## 🚀 Getting Started

### Prerequisites
- Laravel 12 application already set up
- Database configured
- User authentication working
- All packages installed (already done)

### What's Already Done ✅
1. ✅ All database migrations run
2. ✅ All packages installed (Laravel Excel, DomPDF)
3. ✅ All controllers created and implemented
4. ✅ All models with relationships
5. ✅ All views created
6. ✅ All routes configured
7. ✅ Sidebar menu updated

## 📍 Access Points

### Main Menu
After login, you'll see **Inventory** menu in the sidebar with:
- Dashboard
- Categories
- Products
  - Add Product
  - Import Products
  - Export Products
- Stock
- Invoices
- Customers
- Reports

## 🎯 Quick Tasks

### 1. Create Your First Category
```
Inventory → Categories → + New
- Enter category name
- Select status: Active
- Click Submit
```

### 2. Add Products

**Option A: Manual Entry**
```
Inventory → Products → Add Product
- Fill all fields
- SKU must be unique
- Click Submit
```

**Option B: Excel Import**
```
Inventory → Products → Import Products
- Click "Download Sample"
- Fill the Excel file
- Upload and import
```

### 3. Add Customer
```
Inventory → Customers → + New
- Enter customer details
- Click Submit
```

### 4. Create Invoice
```
Inventory → Invoices → + New Invoice
- Select customer
- Add products (click + Add Item)
- Enter quantities
- System calculates totals
- Click Create Invoice
```

### 5. View Stock
```
Inventory → Stock
- See all products with stock levels
- Red = Out of stock
- Yellow = Low stock (≤10)
- Green = In stock
```

### 6. Generate Reports
```
Inventory → Reports
- Select report type
- Apply filters
- View data
```

## 🔑 Key Features

### Excel Import Format
Required columns in Excel:
1. Product Name
2. Category Name (must exist in system)
3. SKU (must be unique)
4. Purchase Price
5. Selling Price
6. Quantity
7. Unit

### Invoice Number Format
Auto-generated: `INV-YYYY-#####`
Example: `INV-2025-00001`

### Stock Management
- Stock automatically deducted when invoice created
- Stock restored when invoice cancelled
- Low stock warning at ≤10 units

### PDF Invoice
- Click PDF button on invoice
- Downloads as PDF file
- Can be printed or emailed

## ⚠️ Important Notes

1. **Cannot Delete**:
   - Category if it has products
   - Product if used in invoices
   - Customer if has invoices

2. **Stock Validation**:
   - System checks stock before creating invoice
   - Shows error if insufficient stock

3. **SKU Uniqueness**:
   - Each product must have unique SKU
   - Import will skip duplicate SKUs

4. **Invoice Cancellation**:
   - Cancelled invoices restore stock
   - Cannot undo cancellation

## 🆘 Common Issues

### Issue: "Table doesn't exist"
**Solution**: Run `php artisan migrate` (already done)

### Issue: "View not found"
**Solution**: All views are created, check route name

### Issue: "Excel import fails"
**Solution**: 
- Check Excel format matches sample
- Ensure category names exist
- Check SKU uniqueness

### Issue: "Cannot create invoice"
**Solution**:
- Check product stock availability
- Ensure customer is selected
- Verify all item quantities are valid

## 📞 Support

For issues or questions:
1. Check error messages
2. Review validation errors
3. Check stock availability
4. Verify data format

---

**Everything is ready to use! Just login and start managing your inventory.**

