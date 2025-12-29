@echo off
title Laravel Server - AG Balaji ERP
cd /d "%~dp0"
echo Starting Laravel Development Server...
echo.
echo Server will be available at: http://localhost:8000
echo Press Ctrl+C to stop the server
echo.
php artisan serve
pause

