# Implementasi CRUD Shortlink

## Plan

Menghubungkan model dan tabel `shortlinks` yang sudah tersedia ke dashboard
Laravel 13 / Inertia / Vue 3. Mengikuti pola resource CRUD Users/Roles dan
permission Spatie yang sudah ada. Scope hanya pengelolaan data shortlink;
redirect publik dan pencatatan klik tidak ditambahkan.

## Todo dan task

- [x] Memeriksa struktur proyek dan membaca empat skill melalui `npx skills use`
      dari luar repo; tidak menginstal skill ke repo.
- [x] Menambahkan resource controller, shared Form Request, dan route CRUD.
- [x] Menambahkan halaman daftar, search, pagination, dan modal tambah/ubah/hapus.
- [x] Menghubungkan menu Shortlink dan kontrol tombol ke permission yang ada.
- [x] Menambahkan factory dan test HTTP CRUD dengan database SQLite memory.
- [x] Menjalankan check dan review sub-agent, lalu memperbaiki temuan zona waktu.
- [x] Memastikan `.env` tidak dibuat dan `.env.example` tidak diubah.

Pembagian task: agent utama menangani backend dan integrasi; sub-agent menangani
UI, test, serta review arsitektur dan perubahan akhir.

## Perilaku

| Route                               | Permission                              |
| ----------------------------------- | --------------------------------------- |
| `GET /shortlinks`                   | `view_shortlinks`                       |
| `POST /shortlinks`                  | `view_shortlinks` + `create_shortlinks` |
| `PUT/PATCH /shortlinks/{shortlink}` | `view_shortlinks` + `edit_shortlinks`   |
| `DELETE /shortlinks/{shortlink}`    | `view_shortlinks` + `delete_shortlinks` |

Semua route memerlukan login. Super Admin mengikuti bypass Gate proyek.
Daftar menampilkan 10 record per halaman, terbaru terlebih dahulu; pencarian
mencocokkan kode atau URL tujuan. Pengguna berizin mengelola seluruh shortlink,
sesuai pola dashboard proyek.

Form tambah dan ubah memerlukan `short_code`, `destination_url`, dan `is_active`.
Kode unik sepanjang 1-50 karakter hanya menerima huruf ASCII, angka, `_`, dan
`-`, serta dinormalisasi menjadi huruf kecil. URL tujuan hanya HTTP/HTTPS dengan
panjang maksimum 2048 karakter. `expires_at` opsional, dapat dikosongkan, dan
boleh berada di masa lalu agar record kedaluwarsa tetap dapat diedit. Offset
tanggal dinormalisasi ke zona waktu aplikasi sebelum disimpan. UI menampilkan
waktu lokal perangkat.

Pembuat diambil dari pengguna yang login. Input `user_id` dan `click_count`
diabaikan; kedua atribut tetap dipertahankan saat update. Hapus bersifat permanen
setelah konfirmasi UI. Record yang tidak ditemukan menghasilkan 404.

## Check

- `php artisan test tests/Feature/ShortlinkTest.php`: 52 test, 229 assertion lulus.
- `php artisan route:list --path=shortlinks -v`: empat route resource dan
  middleware sesuai.
- Pint pada seluruh file PHP yang berubah: lulus.
- PHPStan pada controller, request, model, dan factory shortlink: nol error.
- `npm run types:check`: lulus.
- `vp lint` dan `vp fmt --check` pada file UI yang berubah: lulus.
- `npm run build`: lulus.
- `git diff --check`: lulus.

Test shortlink menggunakan `RefreshDatabase`, SQLite memory dari `phpunit.xml`,
dan app key khusus test di memori. Tidak memerlukan file `.env`.
Analisis PHPStan seluruh proyek masih menemukan 24 error pada model/seeder lama
yang tidak berubah. Suite test lama juga tidak menyiapkan database karena
`RefreshDatabase` global di `tests/Pest.php` masih dikomentari. Coverage numerik
tidak diukur karena runtime PHP tidak menyediakan driver coverage.

## Review

Sub-agent memeriksa permission, validasi, uniqueness, atribut yang diatur server,
search, pagination, dan tanggal. Temuan hilangnya offset tanggal telah diperbaiki
dan diuji untuk create/update. Validasi dipusatkan dalam Form Request karena
dipakai kedua aksi; persistensi tetap memakai Eloquent tanpa lapisan tambahan.
Migration dan permission yang sudah ada digunakan kembali.

## Review ulang (6 Oktober 2026)

Skill `find-skills` dijalankan ulang dari luar repo, output lengkap dibaca, dan
pencarian skill Laravel dilakukan. Tidak ada skill yang diinstal ke repo.

Regresi backend sebelum perbaikan mereproduksi enam kegagalan: benturan kode
setelah validasi pada create/update, dua tanggal di luar batas TIMESTAMP, dan dua
halaman pagination yang melampaui halaman terakhir. Perbaikan berikut selesai:

- Benturan unique index `short_code` saat persistensi dikembalikan sebagai error
  validasi form, sementara exception constraint lain tetap diteruskan.
- Tanggal kedaluwarsa dibatasi ke 1 Januari 1970 00:00:01 UTC sampai 19 Januari
  2038 03:14:07 UTC, sesuai kolom TIMESTAMP yang sudah ada. Form menunjukkan batas
  tersebut dalam waktu perangkat; tidak ada perubahan schema.
- Listing mengarahkan halaman yang melampaui halaman terakhir ke URL yang valid,
  sambil mempertahankan search.
- Debounce pencarian dibatalkan saat clear/unmount untuk mencegah pencarian lama
  muncul lagi. Perbaikan kecil ini berada pada SearchInput bersama.
- Pola URL frontend menerima huruf besar/kecil pada skema HTTP/HTTPS, sesuai
  validasi backend.

52 test HTTP / 229 assertion lulus, termasuk PATCH dan bypass Super Admin.
PHPStan scoped, Pint, type-check frontend, lint/format file terkait, dan build
lulus. Harness JavaScript sementara di OS temp juga memverifikasi pembatalan
debounce dan pola skema URL tanpa memasang dependensi baru ke repo.

Batas verifikasi: runtime database yang diuji adalah SQLite memory, bukan MySQL;
rentang TIMESTAMP diperiksa terhadap schema dan dokumentasi MySQL. Smoke test
browser belum dilakukan. Modal bersama belum menerapkan focus trap dan
pengembalian fokus keyboard; ini tetap merupakan keterbatasan aksesibilitas yang
perlu diperbaiki sebelum mengklaim pengalaman UI sempurna. Review ini memastikan
fungsi CRUD yang diuji, bukan jaminan tidak adanya bug pada semua lingkungan.

## Integrasi branch

Perubahan fitur dicommit dari allowlist 13 file pada branch
`devinayeon-shortlink`. Dua commit terbaru `origin/main` (`09ab007` dan `c075cc2`)
diintegrasikan terlebih dahulu agar perbaikan proyek lain tetap terjaga.
Konflik pada model Shortlink diselesaikan dengan mempertahankan HasFactory
bertipe ShortlinkFactory yang dibutuhkan test fitur.

Hasil check pada kode hasil integrasi: 75 dari 77 test lulus (seluruh 52 test
shortlink lulus), PHPStan seluruh proyek nol error, Pint lulus, pemeriksaan tipe
dan lint/format frontend terkait lulus, serta build lulus. Dua test lama masih
gagal: `correct password must be provided to update password` (error bag) dan
`profile information can be updated` (status verifikasi email). Pengguna telah
mengizinkan commit/push dan merge dengan pengecualian kegagalan test lama.
Catatan 24 error PHPStan di atas adalah hasil sebelum integrasi perbaikan main.
Tidak ada file `.env` yang dibuat atau diisi.
