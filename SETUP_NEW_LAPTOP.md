# 🚀 Setup Guide for New Laptop - AG Balaji ERP

Complete step-by-step commands to set up this Laravel application on a new laptop.

---

## 📋 Prerequisites Check

First, verify you have the required software installed:

```bash
# Check PHP version (needs PHP 8.2 or higher)
php --version

# Check Composer
composer --version

# Check Node.js and NPM
node --version
npm --version

# Check Git (if using version control)
git --version
```

**Required:**
- PHP 8.2 or higher
- Composer
- Node.js 18+ and NPM
- Git (optional)

---

## 📥 Step 1: Get the Project Files

### Option A: Copy from USB/Network Drive
```bash
# Navigate to your project location
cd D:\server\htdocs\cms\laravel\

# Copy the entire project folder to new location
# (Adjust paths as needed)
```

### Option B: Clone from Git (if using version control)
```bash
cd D:\server\htdocs\cms\laravel\
git clone <your-repository-url> ag_balaji_erp
cd ag_balaji_erp
```

---

## 🔧 Step 2: Install PHP Dependencies (Composer)

```bash
# Navigate to project directory
cd D:\server\htdocs\cms\laravel\ag_balaji_erp

# Install all PHP packages
composer install

# If composer install fails, try:
composer update
```

**Expected time:** 2-5 minutes

---

## 📦 Step 3: Install Node.js Dependencies (NPM)

```bash
# Make sure you're in project directory
cd D:\server\htdocs\cms\laravel\ag_balaji_erp

# Install all Node packages
npm install

# If npm install fails, try:
npm install --legacy-peer-deps
```

**Expected time:** 1-3 minutes

---

## ⚙️ Step 4: Environment Configuration

```bash
# Create .env file from example (if .env.example exists)
copy .env.example .env

# OR create new .env file manually
# (You'll need to configure database settings)
```

### Configure .env file:

Open `.env` file and set these values:

```env
APP_NAME="AG Balaji ERP"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
# Option 1: SQLite (Default - No setup needed)
DB_CONNECTION=sqlite
# DB_DATABASE will use database/database.sqlite automatically

# Option 2: MySQL (If you prefer MySQL)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=ag_balaji_erp
# DB_USERNAME=root
# DB_PASSWORD=

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔑 Step 5: Generate Application Key

```bash
# Generate unique application encryption key
php artisan key:generate
```

---

## 💾 Step 6: Database Setup

### Option A: Using SQLite (Easiest - Recommended)

```bash
# Create SQLite database file
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"

# Make sure database directory is writable
# (Windows usually handles this automatically)
```

### Option B: Using MySQL

```bash
# First, create database in MySQL
# Open MySQL command line or phpMyAdmin and run:
# CREATE DATABASE ag_balaji_erp CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Then update .env file with MySQL credentials (see Step 4)
```

---

## 🗄️ Step 7: Run Database Migrations

```bash
# Run all database migrations
php artisan migrate

# If you want to seed sample data (optional)
php artisan db:seed
```

**Note:** This will create all required database tables.

---

## 🔗 Step 8: Create Storage Link

```bash
# Create symbolic link for storage
php artisan storage:link
```

This allows public access to uploaded files.

---

## 🧹 Step 9: Clear and Cache Configuration

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production (optional)
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🧪 Step 10: Verify Installation

```bash
# Check Laravel version
php artisan --version

# List all routes
php artisan route:list

# Check if everything is working
php artisan about
```

---

## 🚀 Step 11: Start the Development Server

```bash
# Start Laravel development server
php artisan serve

# Server will be available at: http://localhost:8000
```

**OR use the launcher files:**
- Double-click `start-server.bat`
- Or use `start-server-with-browser.vbs` to auto-open browser

---

## 📝 Complete Setup Command List (Copy & Paste)

Here's the complete sequence of commands to run:

```bash
# 1. Navigate to project
cd D:\server\htdocs\cms\laravel\ag_balaji_erp

# 2. Install PHP dependencies
composer install

# 3. Install Node dependencies
npm install

# 4. Create .env file (if not exists)
copy .env.example .env

# 5. Generate app key
php artisan key:generate

# 6. Create SQLite database (if using SQLite)
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"

# 7. Run migrations
php artisan migrate

# 8. Create storage link
php artisan storage:link

# 9. Clear caches
php artisan config:clear
php artisan cache:clear

# 10. Start server
php artisan serve
```

---

## 🎯 Quick Setup Script (Windows Batch File)

Save this as `setup-new-laptop.bat` in project root:

```batch
@echo off
echo ========================================
echo AG BALAJI ERP - Setup Script
echo ========================================
echo.

echo [1/8] Installing PHP dependencies...
call composer install
if errorlevel 1 (
    echo ERROR: Composer install failed!
    pause
    exit /b 1
)

echo.
echo [2/8] Installing Node dependencies...
call npm install
if errorlevel 1 (
    echo ERROR: NPM install failed!
    pause
    exit /b 1
)

echo.
echo [3/8] Creating .env file...
if not exist .env (
    if exist .env.example (
        copy .env.example .env
        echo .env file created from .env.example
    ) else (
        echo WARNING: .env.example not found. Please create .env manually.
    )
) else (
    echo .env file already exists.
)

echo.
echo [4/8] Generating application key...
call php artisan key:generate

echo.
echo [5/8] Creating SQLite database...
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"

echo.
echo [6/8] Running database migrations...
call php artisan migrate

echo.
echo [7/8] Creating storage link...
call php artisan storage:link

echo.
echo [8/8] Clearing caches...
call php artisan config:clear
call php artisan cache:clear
call php artisan route:clear
call php artisan view:clear

echo.
echo ========================================
echo Setup Complete!
echo ========================================
echo.
echo To start the server, run:
echo   php artisan serve
echo.
echo Or double-click: start-server.bat
echo.
pause
```

---

## ⚠️ Troubleshooting

### Issue: Composer not found
```bash
# Download and install Composer from: https://getcomposer.org/download/
# Or use: php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
```

### Issue: PHP extensions missing
```bash
# Enable required extensions in php.ini:
# - pdo_sqlite or pdo_mysql
# - mbstring
# - openssl
# - fileinfo
# - tokenizer
# - xml
```

### Issue: Permission errors (Linux/Mac)
```bash
# Make storage and cache directories writable
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Port 8000 already in use
```bash
# Use different port
php artisan serve --port=8001
```

---

## ✅ Verification Checklist

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] Node.js and NPM installed
- [ ] Project files copied/cloned
- [ ] `composer install` completed
- [ ] `npm install` completed
- [ ] `.env` file created and configured
- [ ] `php artisan key:generate` executed
- [ ] Database created (SQLite file or MySQL database)
- [ ] `php artisan migrate` completed
- [ ] `php artisan storage:link` executed
- [ ] Server starts with `php artisan serve`
- [ ] Application accessible at http://localhost:8000

---

## 📞 Need Help?

If you encounter any issues:
1. Check error messages in terminal
2. Check Laravel logs: `storage/logs/laravel.log`
3. Verify all prerequisites are installed
4. Ensure database configuration is correct in `.env`

---

**Setup Time:** Approximately 10-15 minutes (depending on internet speed)

