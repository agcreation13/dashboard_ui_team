@echo off
title AG Balaji ERP - Setup Script
echo ========================================
echo AG BALAJI ERP - Setup Script
echo ========================================
echo.

echo [1/8] Installing PHP dependencies...
call composer install
if errorlevel 1 (
    echo ERROR: Composer install failed!
    echo Please check if Composer is installed: composer --version
    pause
    exit /b 1
)

echo.
echo [2/8] Installing Node dependencies...
call npm install
if errorlevel 1 (
    echo ERROR: NPM install failed!
    echo Please check if Node.js is installed: node --version
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
        echo You can copy from existing .env file or create new one.
    )
) else (
    echo .env file already exists.
)

echo.
echo [4/8] Generating application key...
call php artisan key:generate
if errorlevel 1 (
    echo WARNING: Key generation failed. Make sure .env file exists.
)

echo.
echo [5/8] Creating SQLite database...
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"
if exist database\database.sqlite (
    echo SQLite database file created successfully.
) else (
    echo WARNING: Could not create SQLite database file.
)

echo.
echo [6/8] Running database migrations...
call php artisan migrate
if errorlevel 1 (
    echo WARNING: Migration failed. Check database configuration in .env file.
)

echo.
echo [7/8] Creating storage link...
call php artisan storage:link
if errorlevel 1 (
    echo WARNING: Storage link creation failed.
)

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
echo Server will be available at: http://localhost:8000
echo.
pause

