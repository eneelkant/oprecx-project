@echo off
echo Database Migrate
echo .
echo Ketik perintah kemudian tekan enter
echo pilihan : up | down | redo
set /p inp="> "
yiic.bat migrate %inp%
echo .
echo Tekan sembarang tombol untuk keluar
pause > nul
