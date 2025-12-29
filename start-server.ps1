# Laravel Server Launcher Script
# Double-click this file to start the Laravel development server

$scriptPath = Split-Path -Parent $MyInvocation.MyCommand.Path
Set-Location $scriptPath

Write-Host "Starting Laravel Development Server..." -ForegroundColor Green
Write-Host ""
Write-Host "Server will be available at: http://localhost:8000" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server" -ForegroundColor Yellow
Write-Host ""

php artisan serve

Read-Host "Press Enter to exit"

