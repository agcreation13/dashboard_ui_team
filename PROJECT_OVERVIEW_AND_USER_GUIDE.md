# AG Balaji ERP - Complete Project Overview and User Guide

## 📖 Table of Contents

1. [What is This Application?](#what-is-this-application)
2. [Who Can Use This?](#who-can-use-this)
3. [What Can You Do With This?](#what-can-you-do-with-this)
4. [How to Get Started](#how-to-get-started)
5. [Complete Feature Guide](#complete-feature-guide)
6. [Step-by-Step User Guide](#step-by-step-user-guide)
7. [Understanding Your Dashboard](#understanding-your-dashboard)
8. [Common Tasks Explained](#common-tasks-explained)
9. [Tips and Best Practices](#tips-and-best-practices)
10. [Troubleshooting](#troubleshooting)
11. [Technical Information](#technical-information)

---

## What is This Application?

**AG Balaji ERP** is a complete business management system designed to help you manage your inventory, customers, and invoices all in one place. Think of it as a digital assistant that keeps track of:

- **Your Products**: What you sell, how much you have, and how much they cost
- **Your Customers**: Who buys from you and their contact information
- **Your Sales**: All invoices and transactions in one organized place
- **Your Stock**: Know when products are running low
- **Your Reports**: See how your business is doing at a glance

This system is built using modern web technology (Laravel) and works in any web browser. You don't need to install any special software on your computer - just open your browser and start using it.

---

## Who Can Use This?

This application is designed for **small to medium businesses** that need to:

- Track inventory and stock levels
- Create professional invoices with GST (tax) calculations
- Manage customer information
- Generate reports for business analysis
- Import and export product data using Excel files

**Perfect for:**
- Retail stores
- Wholesale businesses
- Small manufacturing units
- Trading companies
- Any business that sells products and needs to track inventory

---

## What Can You Do With This?

### Main Features:

1. **Manage Categories**: Organize your products into groups (like Electronics, Clothing, Food, etc.)

2. **Manage Products**: 
   - Add products one by one
   - Import hundreds of products at once using Excel
   - Track stock levels automatically
   - Set prices and GST rates

3. **Manage Customers**: 
   - Keep customer contact details
   - Store GST numbers for tax purposes
   - Track customer purchase history

4. **Create Invoices**: 
   - Generate professional GST invoices
   - Automatic tax calculations
   - Print or download invoices as PDF
   - Track all sales

5. **Stock Management**: 
   - See current stock levels
   - Get warnings when stock is low
   - Update stock automatically when you sell

6. **Reports**: 
   - Stock reports
   - Sales reports
   - Category-wise reports
   - Invoice reports

7. **User Management**: 
   - Create multiple user accounts
   - Set different permission levels
   - Track who did what (audit logs)

---

## How to Get Started

### First Time Setup

#### Step 1: Install Required Software

Before you can use this application, you need these on your computer:

1. **PHP 8.2 or higher** - This is the programming language the system uses
2. **Composer** - This installs all the necessary code libraries
3. **Node.js and NPM** - These handle the website design files
4. **A Database** - Either MySQL or SQLite (SQLite is easier - no setup needed)

#### Step 2: Copy Project Files

Copy the entire project folder to your computer. The recommended location is:
```
D:\server\htdocs\cms\laravel\ag_balaji_erp
```

#### Step 3: Run Setup

**Easy Way (Recommended):**
1. Double-click the file: `setup-new-laptop.bat`
2. Wait for it to finish (takes 5-10 minutes)
3. You're done!

**Manual Way:**
Open a command prompt in the project folder and run these commands one by one:

```bash
composer install
npm install
copy .env.example .env
php artisan key:generate
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
php artisan migrate
php artisan storage:link
php artisan config:clear
```

#### Step 4: Start the Server

**Easy Way:**
- Double-click: `start-server-with-browser.vbs`
- This will start the server and open your browser automatically

**Manual Way:**
- Double-click: `start-server.bat`
- Or run: `php artisan serve`
- Open browser and go to: `http://localhost:8000`

#### Step 5: Login

1. Go to the login page
2. Enter your username and password
3. If this is first time, you may need to create an account or use the default admin account

---

## Complete Feature Guide

### 1. Dashboard

**What it shows:**
- Overview of your business
- Total products, customers, invoices
- Recent activity
- Quick statistics

**How to use:**
- This is the first page you see after login
- Click on any number to see more details
- Use it to get a quick overview of your business

### 2. Categories Management

**What it does:**
Organizes your products into groups. For example:
- Electronics
- Clothing
- Food & Beverages
- Office Supplies

**How to use:**

**Add a Category:**
1. Go to: Inventory → Categories
2. Click "Add New Category" or "+" button
3. Enter category name (e.g., "Electronics")
4. Select status: Active or Inactive
5. Click "Save" or "Submit"

**Edit a Category:**
1. Go to Categories list
2. Click "Edit" button next to the category
3. Change the name or status
4. Click "Update"

**Delete a Category:**
1. Go to Categories list
2. Click "Delete" button
3. Confirm deletion
4. Note: You cannot delete a category if it has products in it

**Why it's important:**
Categories help you organize products and find them quickly. It's like having folders for your products.

### 3. Products Management

**What it does:**
This is where you manage all your products - what you sell, how much you have, and their prices.

**Product Information You Can Store:**
- Product name (e.g., "Samsung TV 55 inch")
- Category (which group it belongs to)
- SKU/Product Code (unique code like "TV-SAM-55-001")
- HSN Code (for GST/tax purposes)
- Pack size (e.g., "1 unit", "1 box", "1 kg")
- Purchase price (what you paid)
- Selling price (what you sell for)
- MRP (Maximum Retail Price)
- GST percentage (tax rate like 5%, 12%, 18%)
- Quantity/Stock (how many you have)
- Unit (pcs, kg, box, etc.)
- Status (Active or Inactive)

**How to Add Products:**

**Method 1: Add One Product at a Time**
1. Go to: Inventory → Products → Add Product
2. Fill in all the product details
3. Make sure SKU is unique (no two products can have same SKU)
4. Click "Save"

**Method 2: Import Multiple Products Using Excel**
1. Go to: Inventory → Products → Import Products
2. Click "Download Sample" to get the Excel template
3. Open the Excel file and fill in your products:
   - Product Name
   - Category Name (must match existing category)
   - SKU (must be unique)
   - Purchase Price
   - Selling Price
   - Quantity
   - Unit
   - HSN (optional)
   - Pack (optional)
   - MRP (optional)
   - GST Percentage (optional)
4. Save the Excel file
5. Go back to Import Products page
6. Click "Choose File" and select your Excel file
7. Click "Import"
8. Check for any errors and fix them if needed

**How to Edit Products:**
1. Go to Products list
2. Click "Edit" button next to the product
3. Change any information
4. Click "Update"

**How to Update Stock:**
1. Go to Products list
2. Find the product
3. Click on stock quantity or "Update Stock" button
4. Enter new quantity
5. Click "Update"

**How to Export Products:**
1. Go to: Inventory → Products → Export Products
2. Click "Export" button
3. Excel file will download with all your products

**Stock Status Colors:**
- **Red**: Out of stock (quantity is 0)
- **Yellow**: Low stock (quantity is 10 or less)
- **Green**: In stock (quantity is more than 10)

### 4. Customers Management

**What it does:**
Keeps track of all your customers - who they are, how to contact them, and their tax information.

**Customer Information You Can Store:**
- Customer name
- Email address
- Phone number
- Address
- State
- GSTIN (GST Identification Number - for tax purposes)
- Status (Active or Inactive)

**How to Add a Customer:**
1. Go to: Inventory → Customers
2. Click "Add New Customer" or "+" button
3. Fill in customer details:
   - Name (required)
   - Email (optional but recommended)
   - Phone (optional but recommended)
   - Address (optional)
   - State (optional)
   - GSTIN (optional - needed for GST invoices)
   - Status: Active or Inactive
4. Click "Save" or "Submit"

**How to Edit a Customer:**
1. Go to Customers list
2. Click "Edit" button
3. Update information
4. Click "Update"

**How to View Customer Details:**
1. Go to Customers list
2. Click "View" or customer name
3. See all customer information and purchase history

**Why it's important:**
Having customer information helps you:
- Create invoices quickly
- Contact customers when needed
- Generate GST invoices (needs GSTIN)
- Track who bought what

### 5. Invoices Management

**What it does:**
Create professional invoices for your sales. The system automatically calculates prices, discounts, taxes (GST), and totals.

**Invoice Features:**
- Professional GST invoice format
- Automatic calculations (prices, discounts, taxes, totals)
- Print invoices
- Download invoices as PDF
- Cancel invoices if needed
- Track all sales

**How to Create an Invoice:**

**Step 1: Start New Invoice**
1. Go to: Inventory → Invoices
2. Click "Create New Invoice" or "+" button

**Step 2: Select Customer**
1. Choose a customer from the dropdown
2. If customer doesn't exist, create one first (see Customers section)

**Step 3: Add Products**
1. Click "Add Item" or "+" button
2. Select a product from the dropdown
3. Enter quantity
4. The system will automatically fill:
   - Product name
   - HSN code
   - Pack size
   - MRP
   - Price
   - GST percentage
5. You can modify:
   - Discount percentage (if giving discount)
   - Free quantity (if giving free items)
6. Click "Add" or "Save Item"
7. Repeat to add more products

**Step 4: Invoice Details**
Fill in additional information:
- Invoice date (defaults to today)
- E-way Bill number (if needed for transport)
- MR NO. (Memo Reference Number)
- S. MAN (Salesman name)
- Additional Amount (if any extra charges)
- Round Off (system calculates automatically)

**Step 5: Seller/Company Information**
Enter your company details:
- Company name
- Address
- Email
- Phone
- GSTIN (your company's GST number)

**Step 6: Review and Create**
1. Check all items and totals
2. The system shows:
   - Subtotal (before tax)
   - CGST (Central GST)
   - SGST (State GST)
   - Additional Amount
   - Round Off
   - **Grand Total** (final amount)
3. Click "Create Invoice" or "Save"

**How to View an Invoice:**
1. Go to Invoices list
2. Click "View" or invoice number
3. See complete invoice details

**How to Print an Invoice:**
1. Go to invoice view page
2. Click "Print" button
3. Browser print dialog will open
4. Print or save as PDF

**How to Download Invoice as PDF:**
1. Go to invoice view page
2. Click "Download PDF" button
3. PDF file will download to your computer

**How to Edit an Invoice:**
1. Go to Invoices list
2. Click "Edit" button
3. Make changes
4. Click "Update"
5. Note: You may not be able to edit invoices that are already finalized

**How to Cancel an Invoice:**
1. Go to invoice view page
2. Click "Cancel Invoice" button
3. Confirm cancellation
4. This will restore stock quantities

**Invoice Number:**
- System automatically generates invoice numbers
- Format is usually: INV-001, INV-002, etc.
- Each invoice has a unique number

**Understanding Invoice Totals:**
- **Subtotal**: Sum of all items before tax
- **CGST**: Central Goods and Services Tax (half of total GST)
- **SGST**: State Goods and Services Tax (half of total GST)
- **Additional Amount**: Any extra charges
- **Round Off**: Automatic rounding to nearest rupee
- **Grand Total**: Final amount customer pays

### 6. Stock Management

**What it does:**
Shows you current stock levels for all products. Helps you know what's in stock and what's running low.

**How to View Stock:**
1. Go to: Inventory → Stock
2. See list of all products with:
   - Product name
   - Current quantity
   - Stock status (color coded)

**Stock Status Indicators:**
- **🟢 Green**: Good stock (more than 10 units)
- **🟡 Yellow**: Low stock (10 or fewer units) - time to reorder
- **🔴 Red**: Out of stock (0 units) - urgent reorder needed

**How Stock Updates:**
- When you create an invoice, stock automatically decreases
- When you cancel an invoice, stock automatically increases
- You can manually update stock from Products page

**Low Stock Alerts:**
- System shows warning when stock is low
- Check regularly to avoid running out

### 7. Reports

**What it does:**
Generate various reports to understand your business performance.

**Available Reports:**

**1. Stock Report**
- Shows all products with current stock
- Filter by category
- See which products are low or out of stock

**How to use:**
1. Go to: Inventory → Reports → Stock Report
2. Select category (optional - leave blank for all)
3. Click "Generate Report"
4. View or export the report

**2. Category Report**
- Shows products grouped by category
- See how many products in each category
- See total value of products in each category

**How to use:**
1. Go to: Inventory → Reports → Category Report
2. View report
3. See breakdown by category

**3. Invoice Report**
- List of all invoices
- Filter by date range
- See total sales

**How to use:**
1. Go to: Inventory → Reports → Invoice Report
2. Select date range (optional)
3. Click "Generate Report"
4. View all invoices in that period

**4. Sales Summary**
- Overall sales statistics
- Total sales amount
- Number of invoices
- Average invoice value

**How to use:**
1. Go to: Inventory → Reports → Sales Summary
2. Select date range (optional)
3. View summary statistics

**Why Reports are Important:**
- Understand your business performance
- Know which products sell most
- Track sales over time
- Make informed business decisions

### 8. User Management

**What it does:**
Manage who can access the system and what they can do.

**User Roles:**

**1. Super Admin**
- Full access to everything
- Can manage users
- Can change system settings
- Can do everything

**2. Admin**
- Can manage inventory
- Can create invoices
- Can view reports
- Usually cannot manage other users

**3. User/Normal User**
- Can manage products
- Can create invoices
- Can view stock
- Limited access

**How to Add a User:**
1. Go to: Master Entry → User List
2. Click "Add New User"
3. Fill in:
   - Name
   - Email
   - Password
   - Role (Super Admin, Admin, or User)
   - Status (Active or Inactive)
4. Click "Save"

**How to Edit a User:**
1. Go to User List
2. Click "Edit"
3. Update information
4. Click "Update"

**How to Change User Status:**
1. Go to User List
2. Click "Status" button
3. Toggle between Active and Inactive
4. Inactive users cannot login

**Security Tips:**
- Use strong passwords
- Don't share login credentials
- Deactivate users who no longer need access
- Regularly review user list

---

## Step-by-Step User Guide

### Your First Day - Getting Started

**Morning Setup (15 minutes):**

1. **Login to the system**
   - Open browser
   - Go to: http://localhost:8000
   - Enter username and password
   - Click "Login"

2. **Create Your First Category**
   - Go to: Inventory → Categories
   - Click "Add New Category"
   - Enter name: "Electronics"
   - Status: Active
   - Click "Save"
   - Repeat for other categories you need

3. **Add Your Products**
   - Go to: Inventory → Products → Add Product
   - Fill in product details
   - Click "Save"
   - Repeat for all products
   - OR use Excel import for bulk products

4. **Add Your Customers**
   - Go to: Inventory → Customers
   - Click "Add New Customer"
   - Fill in customer details
   - Click "Save"
   - Repeat for all customers

**Daily Use:**

1. **Creating an Invoice (5 minutes)**
   - Go to: Inventory → Invoices → Create New Invoice
   - Select customer
   - Add products
   - Review totals
   - Click "Create Invoice"
   - Print or email to customer

2. **Checking Stock (2 minutes)**
   - Go to: Inventory → Stock
   - Check for red (out of stock) or yellow (low stock) items
   - Reorder if needed

3. **End of Day (10 minutes)**
   - Go to: Inventory → Reports → Sales Summary
   - Select today's date
   - Review daily sales
   - Check which products sold most

### Common Workflows

**Workflow 1: New Product Arrival**

1. Receive new products
2. Go to: Inventory → Products → Add Product
3. Enter product details
4. Enter quantity received
5. Click "Save"
6. Product is now in system and ready to sell

**Workflow 2: Making a Sale**

1. Customer wants to buy products
2. Go to: Inventory → Invoices → Create New Invoice
3. Select customer (or create new if first time)
4. Add products customer wants
5. Enter quantities
6. Review invoice
7. Click "Create Invoice"
8. Print invoice and give to customer
9. Stock automatically decreases

**Workflow 3: Stock Reordering**

1. Go to: Inventory → Stock
2. Look for yellow (low) or red (out) items
3. Note which products need reordering
4. Order from supplier
5. When products arrive, update stock:
   - Go to Products
   - Find the product
   - Click "Edit"
   - Update quantity
   - Click "Update"

**Workflow 4: Monthly Reports**

1. Go to: Inventory → Reports → Sales Summary
2. Select month date range
3. View total sales
4. Go to Invoice Report
5. Select same date range
6. Review all invoices
7. Export if needed for accounting

---

## Understanding Your Dashboard

When you login, you see the dashboard. Here's what everything means:

**Top Section:**
- **Total Products**: How many different products you have
- **Total Customers**: How many customers in your database
- **Total Invoices**: How many invoices you've created
- **Total Sales**: Total money from all invoices

**Middle Section:**
- **Recent Invoices**: Last few invoices created
- **Low Stock Alerts**: Products running low
- **Quick Stats**: Various business metrics

**Bottom Section:**
- **Activity Log**: Recent actions in the system
- **Quick Links**: Fast access to common tasks

**How to Use Dashboard:**
- Click on any number to see details
- Use quick links for common tasks
- Check low stock alerts regularly
- Review recent invoices

---

## Common Tasks Explained

### Task 1: Importing Products from Excel

**When to use:**
- You have many products (50+)
- You have product data in Excel already
- You want to add products quickly

**Steps:**
1. Prepare your Excel file with columns:
   - Product Name
   - Category Name
   - SKU
   - Purchase Price
   - Selling Price
   - Quantity
   - Unit
2. Go to: Inventory → Products → Import Products
3. Click "Download Sample" to see format
4. Fill your data in same format
5. Save Excel file
6. Go back to Import page
7. Click "Choose File"
8. Select your Excel file
9. Click "Import"
10. Check for errors
11. Fix errors if any
12. Re-import if needed

**Tips:**
- Make sure category names match exactly
- SKU must be unique
- Use numbers for prices and quantities
- Save as .xlsx format

### Task 2: Generating GST Invoice

**When to use:**
- Customer needs GST invoice
- For tax purposes
- For business transactions

**Steps:**
1. Create invoice normally (see Invoice section)
2. Make sure:
   - Customer has GSTIN
   - Your company GSTIN is entered
   - Products have HSN codes
   - GST percentages are set
3. System automatically calculates:
   - CGST (Central GST)
   - SGST (State GST)
   - Total tax
4. Review invoice
5. Click "Create Invoice"
6. Download PDF for records
7. Print and give to customer

**Important:**
- GSTIN is required for GST invoices
- HSN codes help in tax filing
- Keep invoice copies for tax records

### Task 3: Finding a Product Quickly

**Methods:**

**Method 1: Search**
1. Go to Products list
2. Use search box
3. Type product name or SKU
4. Results appear instantly

**Method 2: Filter by Category**
1. Go to Products list
2. Select category from dropdown
3. See only products in that category

**Method 3: Sort**
1. Go to Products list
2. Click column headers to sort
3. Sort by name, price, stock, etc.

### Task 4: Checking What a Customer Bought

**Steps:**
1. Go to: Inventory → Customers
2. Find the customer
3. Click "View" or customer name
4. See customer details
5. See purchase history (all invoices)
6. Click on invoice to see details

**Useful for:**
- Customer service
- Understanding buying patterns
- Follow-up sales

### Task 5: Finding Out-of-Stock Items

**Quick Method:**
1. Go to: Inventory → Stock
2. Look for red items (out of stock)
3. Note product names
4. Reorder from supplier

**Detailed Method:**
1. Go to: Inventory → Reports → Stock Report
2. Filter if needed
3. Export to Excel
4. Review and plan reordering

---

## Tips and Best Practices

### Product Management Tips

1. **Use Clear Product Names**
   - Good: "Samsung 55-inch Smart TV 4K"
   - Bad: "TV" or "Samsung TV"
   - Why: Easier to find and identify

2. **Keep SKU Unique and Meaningful**
   - Good: "TV-SAM-55-4K-001"
   - Bad: "123" or "ABC"
   - Why: Helps in inventory tracking

3. **Set Accurate Prices**
   - Update prices regularly
   - Include GST in selling price if needed
   - Keep purchase price for profit calculation

4. **Update Stock Regularly**
   - Update when you receive goods
   - Update when you sell
   - System does this automatically for invoices

5. **Use Categories Wisely**
   - Not too many (hard to manage)
   - Not too few (everything mixed)
   - 5-15 categories usually good

### Invoice Tips

1. **Double-Check Before Creating**
   - Verify customer details
   - Check product quantities
   - Review totals
   - Once created, hard to change

2. **Keep Invoice Numbers Sequential**
   - System does this automatically
   - Don't delete invoices (cancel instead)
   - Helps in record keeping

3. **Print or Save PDF Immediately**
   - Don't wait
   - Keep copies for records
   - Give to customer promptly

4. **Enter Complete Information**
   - Customer GSTIN for GST invoices
   - Your company GSTIN
   - All product details
   - Helps in tax filing

### Stock Management Tips

1. **Check Stock Daily**
   - Morning routine
   - Identify low stock early
   - Avoid running out

2. **Set Reorder Levels**
   - Know minimum stock for each product
   - Reorder when stock reaches that level
   - Use low stock alerts

3. **Update Stock Immediately**
   - When goods arrive
   - When goods sold
   - Don't wait

4. **Regular Stock Audits**
   - Count physical stock
   - Compare with system
   - Update if different

### Customer Management Tips

1. **Enter Complete Information**
   - Name, email, phone
   - Address for delivery
   - GSTIN for business customers
   - Helps in communication

2. **Keep Information Updated**
   - Change of address
   - New phone number
   - Updated GSTIN
   - Regular updates

3. **Use Status Field**
   - Mark inactive customers
   - Don't delete (keep history)
   - Reactivate if they return

### General Tips

1. **Backup Regularly**
   - Database backup
   - Important files
   - Regular schedule

2. **Use Reports**
   - Weekly sales review
   - Monthly analysis
   - Yearly summaries
   - Make data-driven decisions

3. **Train Your Team**
   - Show how to use system
   - Document procedures
   - Regular updates

4. **Keep System Updated**
   - Regular maintenance
   - Security updates
   - Feature updates

---

## Troubleshooting

### Problem: Cannot Login

**Possible Causes:**
- Wrong username or password
- Account is inactive
- Server not running

**Solutions:**
1. Check username and password (case sensitive)
2. Contact admin to check account status
3. Make sure server is running (check command window)
4. Try resetting password

### Problem: Products Not Showing

**Possible Causes:**
- Products not added
- Filter applied
- Wrong category selected

**Solutions:**
1. Check if products exist (go to Products list)
2. Clear search/filter
3. Select "All Categories"
4. Refresh page

### Problem: Cannot Create Invoice

**Possible Causes:**
- No products in system
- No customers in system
- Stock is zero
- Browser issue

**Solutions:**
1. Add products first
2. Add customers first
3. Check product stock
4. Try different browser
5. Clear browser cache

### Problem: Stock Not Updating

**Possible Causes:**
- Invoice not saved properly
- Manual update needed
- System error

**Solutions:**
1. Check if invoice was created
2. Manually update stock from Products page
3. Refresh page
4. Contact support if persists

### Problem: Excel Import Not Working

**Possible Causes:**
- Wrong file format
- Missing columns
- Invalid data
- Category doesn't exist

**Solutions:**
1. Download sample file and compare
2. Check all required columns present
3. Verify category names match exactly
4. Check for special characters
5. Make sure SKU is unique
6. Save as .xlsx format

### Problem: Invoice PDF Not Downloading

**Possible Causes:**
- Browser blocking download
- PDF generation error
- File permission issue

**Solutions:**
1. Check browser download settings
2. Allow pop-ups for the site
3. Try different browser
4. Check if invoice exists
5. Try print instead (save as PDF)

### Problem: Slow Performance

**Possible Causes:**
- Too much data
- Server resources
- Internet connection

**Solutions:**
1. Clear browser cache
2. Close other applications
3. Check server resources
4. Optimize database (contact admin)
5. Use filters to reduce data shown

### Problem: Cannot See Reports

**Possible Causes:**
- No data in date range
- Wrong date selection
- Permission issue

**Solutions:**
1. Select wider date range
2. Check if data exists for that period
3. Verify user permissions
4. Try different report type

### Getting Help

If you encounter problems:

1. **Check This Guide First**
   - Most common issues covered
   - Step-by-step solutions

2. **Check Error Messages**
   - Read carefully
   - Note exact error text
   - Take screenshot if possible

3. **Contact Support**
   - Provide error details
   - Explain what you were doing
   - Include screenshots

4. **Check System Status**
   - Is server running?
   - Is database connected?
   - Are all services working?

---

## Technical Information

### System Requirements

**Server Requirements:**
- PHP 8.2 or higher
- MySQL 5.7+ or SQLite 3
- Composer
- Node.js 18+ and NPM

**Browser Requirements:**
- Chrome (recommended)
- Firefox
- Edge
- Safari
- Any modern browser

**Recommended:**
- 4GB RAM minimum
- 10GB free disk space
- Stable internet connection (if using online)

### Technology Used

**Backend:**
- Laravel 12 (PHP framework)
- MySQL/SQLite (database)
- Composer (package manager)

**Frontend:**
- HTML, CSS, JavaScript
- Tailwind CSS (styling)
- Blade templates (Laravel)

**Libraries:**
- Laravel Excel (for import/export)
- DomPDF (for PDF generation)
- GridJS (for data tables)

### Database Structure

**Main Tables:**
- `users` - User accounts
- `categories` - Product categories
- `products` - Product information
- `customers` - Customer information
- `invoices` - Invoice headers
- `invoice_items` - Invoice line items

**Relationships:**
- Product belongs to Category
- Invoice belongs to Customer
- Invoice has many Invoice Items
- Invoice Item belongs to Product

### File Structure

**Important Folders:**
- `app/Http/Controllers` - Business logic
- `app/Models` - Database models
- `resources/views` - Web pages
- `routes` - URL routing
- `database/migrations` - Database structure
- `public` - Public files (CSS, JS, images)

### Security Features

- Password encryption
- User authentication
- Role-based access control
- Input validation
- SQL injection protection
- XSS protection

### Backup Recommendations

**What to Backup:**
- Database file (SQLite) or database export (MySQL)
- `.env` file (configuration)
- Uploaded files (if any)

**How Often:**
- Daily for active systems
- Weekly for less active
- Before major updates

**How to Backup:**
- Copy database file
- Export database
- Use backup tools
- Cloud storage recommended

---

## Quick Reference Card

### Common URLs (after login)

- Dashboard: `/dashboard` or `/inventory/dashboard`
- Categories: `/inventory/categories`
- Products: `/inventory/products`
- Customers: `/inventory/customers`
- Invoices: `/inventory/invoices`
- Stock: `/inventory/stock`
- Reports: `/inventory/reports`

### Keyboard Shortcuts

- `Ctrl + P` - Print current page
- `Ctrl + S` - Save (in forms)
- `Ctrl + F` - Search/find
- `F5` - Refresh page
- `Esc` - Close dialogs

### Important Reminders

- ✅ Always check stock before creating invoice
- ✅ Verify customer GSTIN for GST invoices
- ✅ Keep invoice copies for records
- ✅ Update stock when goods arrive
- ✅ Check low stock alerts regularly
- ✅ Backup database regularly
- ✅ Keep system updated

---

## Conclusion

This system is designed to make your business management easier. Start with basic features and gradually use advanced features as you get comfortable.

**Remember:**
- Take your time to learn
- Practice with test data first
- Ask for help when needed
- Regular use makes it easier

**Success Tips:**
- Enter data accurately
- Keep information updated
- Use reports for insights
- Train your team
- Regular backups

**For Support:**
- Refer to this guide
- Check error messages
- Contact technical support
- Provide detailed information

---

**Last Updated:** December 2025
**Version:** 1.0
**Application Name:** AG Balaji ERP
**Purpose:** Inventory and Invoice Management System

---

*This document is written in simple, easy-to-understand language. If you find any section confusing or need more details, please contact support for assistance.*

