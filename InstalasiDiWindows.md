# Yang dibutuhkan #
  * Apache Web Server, PHP (>5.2), dan Mysql (>5) yang telah dikonfigurasi.
  * [Yii Framework](http://www.yiiframework.com/)
  * svn client (tidak wajib)


# langkah #

## Checkout via SVN ##
  * Buka command line dan masuk ke direktory htdocs. contohnya
> > ` cd C:\XAMPP\htdocs `
  * checkout code dari google code
> > ` svn checkout https://oprecx-project.googlecode.com/svn/trunk/ oprecx `
  * jika anda adalah **developer oprecx**, ketikan
> > ` svn checkout https://oprecx-project.googlecode.com/svn/trunk/ oprecx --username username@mail.com `


> _ganti username@gmail.com dengan username anda_

Setelah berhasil lanjutkan ke langkah setup framework

## Setup Framework ##
  * Download yiiframework terbaru di http://www.yiiframework.com/
  * extract-kan folder framework ke directory htdocs anda, sehingga folder _oprecx_ dan _framework_ berada di dalam folder yang sama.


## Setup Database ##
  * buat database "oprecx" di mysql anda. buat juga user "oprecx" dengan password "oprecx" dan berikan izin penuh ke database "oprecx".
    * how? => http://bit.ly/ZHqu7M
  * buka folder app yang ada di dalam folder oprecx
  * jalankan buka file migrate.bat
  * ketik **up** untuk mengupdate database

## tadaaa ##
> buka http://localhost/oprecx/ dan semoga berhasil :)