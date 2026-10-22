Perbaikan kecil patch UI GP Fleet

Temuan: baris link gpfleet.css pada patch sebelumnya memiliki tanda kutip yang ter-escape, sehingga browser bisa gagal membaca URL stylesheet global.

Isi ZIP ini hanya satu file pengganti:
gp-fleet/resources/views/vendor/adminlte/page.blade.php

Cara pakai:
1. Backup file page.blade.php yang sekarang.
2. Ekstrak ZIP ke C:\xampp\htdocs\gp-fleet dengan mempertahankan struktur folder.
3. Tekan Ctrl+F5 di browser.
4. Pastikan tampilan tetap normal.

Tidak mengubah database, route, atau data.
