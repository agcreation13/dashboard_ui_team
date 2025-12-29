Set WshShell = CreateObject("WScript.Shell")
Set fso = CreateObject("Scripting.FileSystemObject")

' Get the directory where this script is located
scriptPath = fso.GetParentFolderName(WScript.ScriptFullName)

' Change to the project directory
WshShell.CurrentDirectory = scriptPath

' Start the Laravel server in a new command window
WshShell.Run "cmd.exe /c ""cd /d """ & scriptPath & """ && php artisan serve""", 1, False

' Wait a few seconds for the server to start
WScript.Sleep 3000

' Open the browser
WshShell.Run "http://localhost:8000", 1, False

