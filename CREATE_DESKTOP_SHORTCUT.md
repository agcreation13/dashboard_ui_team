# How to Create Desktop Shortcut for Laravel Application

## Quick Start (Easiest Method)

**Option A: Simple Launcher (Recommended)**
1. **Right-click** on `start-server.bat` file in your project folder
2. Select **"Send to"** → **"Desktop (create shortcut)"**
3. **Right-click** on the shortcut on your desktop → **"Rename"** → Name it "AG Balaji ERP"
4. **Double-click** the shortcut to start the server!

**Option B: Auto-Open Browser**
1. **Right-click** on `start-server-with-browser.vbs` file in your project folder
2. Select **"Send to"** → **"Desktop (create shortcut)"**
3. **Rename** it to "AG Balaji ERP"
4. **Double-click** to start the server AND automatically open your browser!

## Method 1: Using Batch File (start-server.bat)

1. **Right-click** on `start-server.bat` file in your project folder
2. Select **"Send to"** → **"Desktop (create shortcut)"**
3. **Right-click** on the shortcut on your desktop
4. Select **"Properties"**
5. In the **"Shortcut"** tab:
   - You can change the icon by clicking **"Change Icon"**
   - You can rename it to something like "AG Balaji ERP"
6. Click **"OK"**

Now you can double-click the shortcut on your desktop to start the server!

## Method 2: Manual Shortcut Creation

1. **Right-click** on your desktop
2. Select **"New"** → **"Shortcut"**
3. In the location field, enter:
   ```
   cmd.exe /c "cd /d D:\server\htdocs\cms\laravel\ag_balaji_erp && php artisan serve && pause"
   ```
   (Replace the path with your actual project path if different)
4. Click **"Next"**
5. Name it "AG Balaji ERP" or any name you prefer
6. Click **"Finish"**

## Method 3: Using PowerShell Script

1. **Right-click** on `start-server.ps1` file
2. Select **"Send to"** → **"Desktop (create shortcut)"**
3. If you get a security error when running, you may need to:
   - Right-click the shortcut → **Properties**
   - In the **"Shortcut"** tab, change the target to:
     ```
     powershell.exe -ExecutionPolicy Bypass -File "D:\server\htdocs\cms\laravel\ag_balaji_erp\start-server.ps1"
     ```
   - (Replace the path with your actual project path)

## Method 4: Using VBScript (Auto-Open Browser)

The `start-server-with-browser.vbs` file will:
- Start the Laravel server
- Automatically open your default browser to `http://localhost:8000`

1. **Right-click** on `start-server-with-browser.vbs` file
2. Select **"Send to"** → **"Desktop (create shortcut)"**
3. **Rename** the shortcut to "AG Balaji ERP"
4. **Double-click** to run - it will start the server and open your browser automatically!

## Notes

- The server will start on `http://localhost:8000` by default
- A command window will open showing the server status
- To stop the server, press `Ctrl+C` in the command window
- Make sure PHP is in your system PATH for this to work

