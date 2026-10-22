GP Fleet UI Patch - 15 Oktober 2026

Isi patch:
1. public/css/gpfleet.css
   - Menggabungkan variabel tema :root menjadi satu blok.
   - Menambahkan baseline konsisten untuk form, tombol, alert, pagination, dan tampilan mobile.
2. resources/views/vendor/adminlte/page.blade.php
   - Memuat gpfleet.css secara global pada halaman aplikasi AdminLTE, sebelum CSS khusus halaman.
3. public/css/dokumen.css
   - Menambahkan style tombol hapus dokumen agar tombol bulk delete punya tampilan merah yang jelas.
4. resources/views/dokumen/index.blade.php
   - Menampilkan jumlah dokumen terpilih pada tombol bulk delete.
   - Memperbaiki colspan empty state menjadi 10 kolom.

Cara pakai:
- Backup empat file yang sama terlebih dahulu.
- Ekstrak isi ZIP ke folder proyek C:\xampp\htdocs\gp-fleet dengan mempertahankan struktur folder.
- Cek halaman Dashboard, Kendaraan, Dokumen, PIC, User, Profile, Notifikasi, dan Audit Log.
- Tes bulk delete dengan data uji yang aman. Jangan gunakan data produksi untuk uji hapus.
- Tidak ada database, .env, .git, vendor, atau node_modules di dalam patch ini.
