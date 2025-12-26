# Inventory Management Application – Requirements Document

## 1. Project Overview

The goal of this project is to build a **simple and scalable Inventory Management Application** using **Laravel**.  
The system will allow users to manage products category-wise, maintain inventory stock, and generate **customer invoices** based on a provided invoice format.

The application is designed for **small to medium businesses** and focuses on ease of use, accuracy, and flexibility.

---

## 2. Technology Stack

- Backend Framework: Laravel (Latest Stable Version)
- Database: MySQL / MariaDB
- Frontend: Blade + Bootstrap / Tailwind CSS
- Authentication: Laravel Auth
- File Handling: Excel Import/Export (Laravel Excel)
- PDF Generation: DomPDF / Snappy (for invoices)

---

## 3. User Roles & Access

### 3.1 Admin User
- Full access to the system
- Manage users
- Manage categories
- Manage products
- Import / Export products
- Create and manage invoices
- View reports

### 3.2 Normal User
- Manage inventory products
- Create invoices
- View product stock
- No access to system settings or user management

---

## 4. Authentication Module

- Login
- Logout
- Password reset
- Role-based access control
- Middleware protection for routes

---

## 5. Category Management

### Features:
- Create category
- Edit category
- Delete category (with validation if products exist)
- View category list

### Fields:
- Category Name
- Status (Active / Inactive)
- Created Date

---

## 6. Product Management

### 6.1 Manual Product Entry
Users can add products **one by one manually**.

#### Fields:
- Product Name
- Category
- SKU / Product Code
- Purchase Price
- Selling Price
- Quantity / Stock
- Unit (pcs, kg, box, etc.)
- Status (Active / Inactive)

---

### 6.2 Bulk Product Import (Excel)

- Import products using Excel file
- Validate data before saving
- Show error messages for invalid rows
- Prevent duplicate product codes

#### Excel Columns:
- Product Name
- Category Name
- SKU
- Purchase Price
- Selling Price
- Quantity
- Unit

---

### 6.3 Download Sample Excel Format

- Provide **Download Sample Excel** button
- Sample file includes:
  - Proper headers
  - Example row
  - Notes sheet (optional)

---

### 6.4 Product Export

- Export product list to Excel
- Filter export by:
  - Category
  - Status
  - Stock availability

---

## 7. Inventory Management

- Auto update stock on:
  - Invoice creation
  - Manual stock update
- Low stock warning
- Stock history log (optional)

---

## 8. Invoice Management

### 8.1 Invoice Creation

Invoices must follow the **shared invoice format**.

#### Invoice Details:
- Invoice Number (Auto-generated)
- Invoice Date
- Customer Name
- Customer Mobile
- Customer Address

#### Invoice Items:
- Product
- Quantity
- Rate
- Discount (if applicable)
- Tax (if applicable)
- Line Total

#### Summary:
- Subtotal
- Discount
- Tax
- Grand Total

---

### 8.2 Invoice Actions

- Create invoice
- View invoice
- Download invoice (PDF)
- Print invoice
- Cancel invoice (restore stock)

---

## 9. Customer Management (Basic)

- Customer Name
- Phone Number
- Address
- Invoice History

---

## 10. Reports Module

- Product stock report
- Category-wise product report
- Invoice report (date-wise)
- Sales summary report

---

## 11. Menu Structure

- Dashboard
- Categories
- Products
  - Add Product (Manual)
  - Import Products
  - Export Products
- Inventory
- Invoices
- Customers
- Reports
- Users (Admin only)
- Settings

---

## 12. Validation & Security

- Server-side validation
- CSRF protection
- Input sanitization
- Role-based permissions
- Secure file uploads

---

## 13. Future Enhancements (Optional)

- Barcode scanning
- GST/VAT support
- Multi-warehouse support
- Payment tracking
- Email invoice to customer

---

## 14. Development Steps (Implementation Flow)

1. Setup Laravel project
2. Configure database & authentication
3. Create user roles & middleware
4. Implement category CRUD
5. Implement product CRUD (manual)
6. Implement Excel import/export
7. Add sample Excel download
8. Build inventory logic
9. Create invoice module
10. Generate PDF invoices
11. Add reports
12. Testing & optimization
13. Deployment

---

## 15. Conclusion

This Inventory Management Application will provide a **clean, efficient, and user-friendly system** to manage products, stock, and invoices with flexibility for both manual and bulk operations.
