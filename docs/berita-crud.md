# Rencana Implementasi Fitur Kelola Berita (CRUD)

## 1. Analisis UI & Kebutuhan Data (Berdasarkan Screenshot)

### Tampilan Halaman (Index)
- **Header**: Judul "Kelola Berita" dengan deskripsi "Kelola artikel publikasi, tutorial terbuka, dan dokumentasi warta UKM LAOS Fasilkom UNEJ."
- **Tombol Aksi Utama**: "+ Tambah Berita" (warna hijau/primary).
- **Pencarian**: Input teks dengan placeholder "Cari berdasarkan judul berita, kategori, penulis...". Terdapat tombol *refresh* di sebelahnya.
- **Tabel Data**:
  - **JUDUL & SLUG**: Menampilkan judul artikel (teks tebal) dan *slug* artikel di bawahnya dengan warna abu-abu.
  - **KATEGORI**: Menampilkan kategori dalam bentuk *badge* (pill) dengan warna latar yang dinamis.
  - **PENULIS**: Menampilkan inisial avatar (lingkaran), nama penulis (teks tebal), dan nama divisi penugasan di bawahnya.
  - **TANGGAL RILIS**: Menampilkan tanggal rilis/publikasi.
  - **STATUS**: Menampilkan *badge* dengan indikator titik hijau/abu-abu (Terbit / Draf).
  - **AKSI**: Tombol Edit (ikon pensil) dan Hapus (ikon tempat sampah).
- **Pagination**: Menampilkan informasi "Menampilkan 1-5 dari X berita" dan navigasi halaman.

### Kebutuhan Data dari Backend
Untuk memenuhi tampilan tabel, `BlogController@index` harus mengembalikan data `Blog` beserta relasinya:
- `judul`, `slug`
- `kategori`
- `status` (akan dipetakan menjadi 'Terbit' / 'Draf')
- `published_at` (diformat menjadi tanggal)
- Relasi `author` (untuk mengambil `name` penulis)
- Relasi `divisi` (untuk menampilkan nama divisi di bawah nama penulis)

## 2. Struktur File yang Akan Dibuat/Diubah

### Backend (Laravel)
1. **Controller**: `app/Http/Controllers/BlogController.php`
   - `index`: Menampilkan daftar berita dengan filter pencarian dan paginasi.
   - `create`: Menampilkan halaman form tambah berita.
   - `store`: Menyimpan data berita baru.
   - `edit`: Menampilkan halaman form edit berita.
   - `update`: Memperbarui data berita.
   - `destroy`: Menghapus data berita (soft delete).
2. **Form Request**: `app/Http/Requests/BlogRequest.php`
   - Validasi input form (judul, kategori, konten, meta_description, dll).
3. **Route**: `routes/web.php`
   - Menambahkan `Route::resource('blogs', BlogController::class);` di dalam *middleware group* yang sesuai (`auth` & `can:view_news`).

### Frontend (Inertia + Vue 3)
1. **Dependency Editor**: Menginstal Tiptap (`@tiptap/vue-3`, `@tiptap/starter-kit`, dsb.) untuk komponen Rich Text Editor.
2. **Komponen Reusable**: 
   - `resources/js/Components/Common/TiptapEditor.vue`: Komponen editor teks yang mendukung upload gambar & *embed* YouTube.
3. **Pages**:
   - `resources/js/Pages/Blogs/Index.vue`: Menampilkan tabel sesuai *screenshot* UI.
   - `resources/js/Pages/Blogs/Create.vue`: Halaman form untuk menambahkan artikel.
   - `resources/js/Pages/Blogs/Edit.vue`: Halaman form untuk menyunting artikel.
4. **Navigasi Sidebar**: Memastikan menu "Kelola Berita" di *Sidebar* mengarah ke `route('blogs.index')` dan menyala ketika aktif.

## 3. Langkah-Langkah Pengerjaan

1. **Instalasi Tiptap**
   - Jalankan `npm install @tiptap/vue-3 @tiptap/starter-kit @tiptap/extension-image @tiptap/extension-youtube`.
2. **Implementasi Backend**
   - Buat `BlogRequest.php` untuk validasi.
   - Buat `BlogController.php` dengan logika *Query Builder* dan Eloquent yang efisien (menggunakan *Eager Loading* `with(['author', 'divisi'])`).
   - Daftarkan route di `web.php`.
3. **Pembuatan UI `Index.vue`**
   - Buat file `resources/js/Pages/Blogs/Index.vue`.
   - Gunakan komponen `DataTable`, `AppButton`, `AppIcon` yang sudah ada.
   - Sesuaikan *slot* tabel untuk menampilkan Badge, Avatar, dan informasi bertingkat sesuai desain.
4. **Pembuatan Komponen Editor & Form**
   - Buat `TiptapEditor.vue`.
   - Buat halaman `Create.vue` dan `Edit.vue` dengan layout yang rapi untuk penulisan artikel.
5. **Testing & Penyesuaian**
   - Uji coba proses CRUD.
   - Periksa konsistensi desain UI dengan tema sistem.

## 4. To-Do List
- [x] **Persiapan**
  - [x] Memastikan `Blog` model dan migration sudah sesuai standar.
  - [x] Menjalankan `npm install @tiptap/vue-3 @tiptap/starter-kit @tiptap/extension-image @tiptap/extension-youtube` (saat tahap eksekusi dimulai).
- [x] **Backend**
  - [x] Membuat `app/Http/Requests/BlogRequest.php`.
  - [x] Membuat `app/Http/Controllers/BlogController.php`.
  - [x] Mendaftarkan route `blogs` di `routes/web.php` (`auth` & `can:view_news`).
- [x] **Frontend - Komponen**
  - [x] Membuat komponen `resources/js/Components/Common/TiptapEditor.vue`.
- [x] **Frontend - Pages**
  - [x] Membuat `resources/js/Pages/Blogs/Index.vue` (tabel, filter, pagination).
  - [x] Membuat `resources/js/Pages/Blogs/Form.vue` (form gabungan Create & Edit + integrasi Tiptap).
- [x] **Finalisasi**
  - [x] Menguji alur pembuatan artikel baru (Web & Pest).
  - [x] Menguji alur penyuntingan dan update status (Web & Pest).
  - [x] Menguji penghapusan (Soft Delete) beserta otorisasinya (Web & Pest).
  - [x] Menghubungkan menu Sidebar dengan rute `/blogs`.
