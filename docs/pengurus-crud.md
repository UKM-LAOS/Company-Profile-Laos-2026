# Implementasi CRUD Kelola Pengurus

## Plan

Menghubungkan model `Pengurus` dan tabel `penguruses` yang sudah tersedia ke
dashboard Laravel 13 / Inertia / Vue 3. Mengikuti pola resource CRUD Shortlink,
Users, dan Roles serta permission Spatie yang sudah ada (`*_committee`).

Data pengurus nantinya dipakai halaman publik **Tentang Kami** untuk menampilkan
susunan kepengurusan. Scope branch ini **hanya admin panel** (Kelola Pengurus);
halaman publik Tentang Kami tidak dikerjakan di sini.

Branch: `feat/kelola-pengurus` (dari `development`). Pull request diarahkan ke
`development`.

## Todo dan task

- [x] Menambahkan `PengurusController` (resource: index, store, update, destroy)
      dan route di dalam grup `auth` + `can:view_committee`.
- [x] Menambahkan shared Form Request `PengurusRequest` untuk create/update.
- [x] Menangani upload foto ke disk `public` (ganti/hapus file lama saat update
      dan saat foto dikosongkan).
- [x] Menambahkan halaman `Pengurus/Index.vue`: daftar, search, filter periode,
      filter jabatan, filter status, dan pagination.
- [x] Menambahkan modal tambah/ubah, modal detail (ikon mata), dan modal hapus.
- [x] Menghubungkan menu sidebar "Kelola Pengurus" ke `pengurus.index` dan
      menyembunyikan tombol aksi sesuai permission.
- [x] Menambahkan `HasFactory` pada model dan `PengurusFactory`.
- [x] Menambahkan test HTTP CRUD (`tests/Feature/PengurusTest.php`) dengan
      SQLite memory.
- [x] Menjalankan Pint, PHPStan, type-check, lint/format, build, dan test.
- [x] Memastikan `.env` tidak ikut di-commit dan `.env.example` tidak diubah.

## Struktur data (sesuai ERD)

Tabel `penguruses` sudah ada; **tidak ada perubahan migration** pada rencana ini.

| Kolom                       | Tipe          | Keterangan                                                                                                                                  |
| --------------------------- | ------------- | ------------------------------------------------------------------------------------------------------------------------------------------- |
| `id`                        | bigint        | Primary key                                                                                                                                 |
| `nama`                      | varchar(255)  | Wajib                                                                                                                                       |
| `jabatan`                   | varchar(255)  | Wajib, contoh: Ketua Umum, Koordinator Divisi                                                                                               |
| `periode`                   | varchar(255)  | Wajib, format `YYYY/YYYY` (contoh `2025/2026`)                                                                                              |
| `foto`                      | varchar(255)? | Path file di disk `public`, opsional                                                                                                        |
| `sosmed`                    | json?         | Objek `{instagram, linkedin, github}`, opsional                                                                                             |
| `urutan`                    | int           | Urutan tampil di Tentang Kami (1: Ketum, 2: Waketum, 3: Sekre 1, 4: Sekre 2, 5: Bendahara 1, 6: Bendahara 2, 7: Kadiv/Kasubdiv, 8: Anggota) |
| `aktif`                     | tinyint(1)    | Status tampil, default `true`                                                                                                               |
| `created_at` / `updated_at` | timestamp?    |                                                                                                                                             |
| `deleted_at`                | timestamp?    | Soft delete                                                                                                                                 |

## Perilaku

| Route                            | Permission                            |
| -------------------------------- | ------------------------------------- |
| `GET /pengurus`                  | `view_committee`                      |
| `POST /pengurus`                 | `view_committee` + `create_committee` |
| `PUT/PATCH /pengurus/{pengurus}` | `view_committee` + `edit_committee`   |
| `DELETE /pengurus/{pengurus}`    | `view_committee` + `delete_committee` |

Semua route memerlukan login. Super Admin mengikuti bypass Gate proyek.

Daftar menampilkan 10 record per halaman, diurutkan berdasarkan `periode`
terbaru, lalu `urutan` naik, lalu `nama`. Pencarian mencocokkan `nama` atau
`jabatan`. Filter yang tersedia:

- **Periode**: opsi diambil dari nilai `periode` unik di database.
- **Jabatan**: opsi diambil dari nilai `jabatan` unik di database.
- **Status**: Semua / Aktif / Nonaktif.

Halaman yang melampaui halaman terakhir diarahkan ke halaman valid sambil
mempertahankan query filter (pola yang sama dengan Shortlink).

## Validasi form

| Field        | Aturan                                                           |
| ------------ | ---------------------------------------------------------------- |
| `nama`       | required, string, max 255, di-trim                               |
| `jabatan`    | required, string, max 255, di-trim                               |
| `periode`    | required, regex `^\d{4}/\d{4}$`, tahun kedua = tahun pertama + 1 |
| `foto`       | nullable, image (jpg, jpeg, png, webp), max 2 MB                 |
| `hapus_foto` | boolean opsional, untuk mengosongkan foto saat update            |
| `sosmed`     | nullable array, hanya key `instagram`, `linkedin`, `github`      |
| `sosmed.*`   | nullable, url http/https, max 255                                |
| `urutan`     | required, integer, min 0, max 9999                               |
| `aktif`      | required, boolean                                                |

Catatan teknis:

- Upload foto saat update dikirim dengan `POST` + `_method=PUT` karena
  `multipart/form-data` tidak didukung oleh `PUT` biasa (pakai `forceFormData`
  di `useForm` Inertia).
- File foto disimpan di `storage/app/public/pengurus` dan diakses lewat
  `Storage::url()`. Link symlink `public/storage` telah terhubung via
  `php artisan storage:link`.
- Key sosmed yang kosong dibuang sebelum disimpan; jika semua kosong, `sosmed`
  disimpan sebagai `null`.
- Hapus memakai **soft delete** sesuai model (`SoftDeletes`). File foto tidak
  dihapus saat soft delete agar data masih bisa dipulihkan. Record yang tidak
  ditemukan menghasilkan 404.

## UI (mengacu mockup)

Header: judul **Kelola Pengurus**, subjudul struktur kepengurusan UKM LAOS
Fasilkom UNEJ, tombol hijau **+ Tambah Pengurus** (hanya jika
`create_committee`).

Toolbar: input **Cari Pengurus**, dropdown **Semua Periode**, **Semua Jabatan**,
dan **Semua Status**.

Kolom tabel:

| Kolom             | Isi                                                          |
| ----------------- | ------------------------------------------------------------ |
| Pengurus          | Avatar (foto atau inisial berwarna), nama, ikon sosmed kecil |
| Jabatan & Periode | Badge jabatan (hijau untuk Ketua Umum), teks periode         |
| Urutan            | Angka urutan tampil                                          |
| Status            | Badge **Aktif** (hijau) / **Nonaktif** (abu-abu)             |
| Aksi              | Lihat (detail), Ubah, Hapus — mengikuti permission           |

Footer: teks "Menampilkan X-Y dari Z Pengurus" dan komponen `Pagination`
bersama.

Komponen yang dipakai ulang: `DataTable`, `Pagination`, `SearchInput`,
`Modal`, `AppButton`, `AppIcon`, `InputError`, dan `usePermission`.

> **Perbedaan mockup vs ERD.** Mockup menampilkan **NIM**, **email**, kolom
> **Divisi**, dan ikon verifikasi. Kolom tersebut **tidak ada** di tabel
> `penguruses` pada ERD. Implementasi ini mengikuti ERD: kolom Divisi diganti kolom
> Urutan, sedangkan NIM/email tidak ditampilkan.

## File yang diimplementasikan

| File                                                           | Status                                 |
| -------------------------------------------------------------- | -------------------------------------- |
| `app/Http/Controllers/PengurusController.php`                  | baru                                   |
| `app/Http/Requests/PengurusRequest.php`                        | baru                                   |
| `app/Models/Pengurus.php`                                      | ubah (HasFactory, accessor `foto_url`) |
| `database/factories/PengurusFactory.php`                       | baru                                   |
| `routes/web.php`                                               | ubah (route resource `pengurus`)       |
| `resources/js/pages/Pengurus/Index.vue`                        | baru                                   |
| `resources/js/pages/Pengurus/Partials/PengurusFormModal.vue`   | baru                                   |
| `resources/js/pages/Pengurus/Partials/PengurusDetailModal.vue` | baru                                   |
| `resources/js/pages/Pengurus/Partials/DeletePengurusModal.vue` | baru                                   |
| `resources/js/pages/Pengurus/types.ts`                         | baru                                   |
| `resources/js/Components/AppIcon.vue`                          | ubah (tambah ikon `eye`)               |
| `resources/js/Components/Sidebar/AppSidebar.vue`               | ubah (link menu)                       |
| `tests/Feature/PengurusTest.php`                               | baru                                   |
| `docs/pengurus-crud.md`                                        | baru (dokumen ini)                     |

## Check

Hasil verifikasi:

- `php artisan test tests/Feature/PengurusTest.php`: 28 test, 121 assertion lulus 100%.
- `php artisan route:list --path=pengurus -v`: 4 route resource dan middleware sesuai (`view_committee`, `create_committee`, `edit_committee`, `delete_committee`).
- `vendor/bin/pint`: lulus (tidak ada styling issue).
- `npm run build`: lulus.
- `git diff --check`: lulus.

## Optimasi Performa

1. **Database Indexing**: Diterapkan migration `add_indexes_to_penguruses_table` dengan composite index `(periode, aktif, urutan)` serta single index `(jabatan)` dan `(nama)`.
2. **Options Caching**: Opsi dropdown filter (`periode` dan `jabatan`) di-cache dengan `Cache::remember('pengurus_filter_options', 3600, ...)` dan di-invalidate otomatis saat operasi `store`, `update`, atau `destroy`.
3. **Column Projection**: Query pengurus hanya mengambil kolom spesifik yang dibutuhkan (`id, nama, jabatan, periode, foto, sosmed, urutan, aktif`) untuk memangkas memori dan transfer data.
4. **Inertia Partial Reloads**: Pencarian dan filter dropdown menggunakan `only: ['penguruses', 'filters']` sehingga respon request tidak perlu mengangkut payload yang tidak berubah.

## Review

Fitur Kelola Pengurus (CRUD) diimplementasikan secara end-to-end:

1. **Backend**: Controller resource, Form Request bersama, auto storage deletion saat ganti/hapus foto, soft delete aman, serta query builder dengan search (nama/jabatan) dan filter (periode, jabatan, status).
2. **Frontend**: Mengadaptasi layout dan desain mockup UKM LAOS dengan Inertia Vue 3. Dilengkapi avatar dinamis (foto atau inisial warna), badge jabatan & status, modal form dengan file preview & client-side size check (2 MB), modal detail interaktif (ikon mata), dan modal konfirmasi hapus.
3. **Keamanan & Otorisasi**: Otorisasi granular via Spatie permission (`view_committee`, `create_committee`, `edit_committee`, `delete_committee`), dengan tombol aksi dan menu otomatis disesuaikan hak akses pengguna.
4. **Kualitas Kode**: Seluruh standar CI/CD proyek (Pint, PHPStan, Vue-TSC, VP Check, dan Vite Build) berhasil dilewati dengan 100% kelulusan.
