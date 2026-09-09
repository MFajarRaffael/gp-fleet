Set WshShell = CreateObject("WScript.Shell")

WshShell.Run "C:\xampp\php\php.exe C:\xampp\htdocs\gp-fleet\artisan schedule:run", 0, False

Set WshShell = Nothing