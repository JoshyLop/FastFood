@echo off
echo ========================================
echo   Iniciando Fast Food EJFA
echo ========================================
echo.
echo Servidor corriendo en: http://localhost:8000
echo Abriendo navegador...
echo.
echo Presiona Ctrl+C para detener el servidor
echo ========================================
echo.
start http://localhost:8000/index.html
timeout /t 2 /nobreak >nul
C:\xampp\php\php.exe -S localhost:8000
