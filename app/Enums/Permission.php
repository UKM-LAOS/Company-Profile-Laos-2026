<?php

namespace App\Enums;

enum Permission: string
{
    // 1. Modul Utama (Dashboard)
    case VIEW_DASHBOARD = 'view_dashboard';

    // 2. Modul Kelola Divisi (CMS)
    case VIEW_DIVISIONS = 'view_divisions';
    case CREATE_DIVISIONS = 'create_divisions';
    case EDIT_DIVISIONS = 'edit_divisions';
    case DELETE_DIVISIONS = 'delete_divisions';

    // 3. Modul Kelola Program Kerja (CMS)
    case VIEW_WORK_PROGRAMS = 'view_work_programs';
    case CREATE_WORK_PROGRAMS = 'create_work_programs';
    case EDIT_WORK_PROGRAMS = 'edit_work_programs';
    case DELETE_WORK_PROGRAMS = 'delete_work_programs';

    // 4. Modul Kelola Berita (CMS)
    case VIEW_NEWS = 'view_news';
    case CREATE_NEWS = 'create_news';
    case EDIT_NEWS = 'edit_news';
    case DELETE_NEWS = 'delete_news';

    // 5. Modul Kelola Pengurus (CMS)
    case VIEW_COMMITTEE = 'view_committee';
    case CREATE_COMMITTEE = 'create_committee';
    case EDIT_COMMITTEE = 'edit_committee';
    case DELETE_COMMITTEE = 'delete_committee';

    // 6. Modul Kelola Pengguna (Sistem)
    case VIEW_USERS = 'view_users';
    case CREATE_USERS = 'create_users';
    case EDIT_USERS = 'edit_users';
    case DELETE_USERS = 'delete_users';

    // 7. Modul Hak Akses & Role (Sistem)
    case VIEW_ROLES = 'view_roles';
    case CREATE_ROLES = 'create_roles';
    case EDIT_ROLES = 'edit_roles';
    case DELETE_ROLES = 'delete_roles';

    // 8. Modul Kelola Shortlink (Fitur)
    case VIEW_SHORTLINKS = 'view_shortlinks';
    case CREATE_SHORTLINKS = 'create_shortlinks';
    case EDIT_SHORTLINKS = 'edit_shortlinks';
    case DELETE_SHORTLINKS = 'delete_shortlinks';

    /**
     * Get human-readable label for the permission.
     */
    public function label(): string
    {
        return match ($this) {
            self::VIEW_DASHBOARD => 'Lihat Dashboard',

            self::VIEW_DIVISIONS => 'Lihat Data Divisi',
            self::CREATE_DIVISIONS => 'Tambah Divisi',
            self::EDIT_DIVISIONS => 'Ubah Data Divisi',
            self::DELETE_DIVISIONS => 'Hapus Divisi',

            self::VIEW_WORK_PROGRAMS => 'Lihat Program Kerja',
            self::CREATE_WORK_PROGRAMS => 'Tambah Program Kerja',
            self::EDIT_WORK_PROGRAMS => 'Ubah Program Kerja',
            self::DELETE_WORK_PROGRAMS => 'Hapus Program Kerja',

            self::VIEW_NEWS => 'Lihat Berita',
            self::CREATE_NEWS => 'Tambah Berita',
            self::EDIT_NEWS => 'Ubah Berita',
            self::DELETE_NEWS => 'Hapus Berita',

            self::VIEW_COMMITTEE => 'Lihat Data Pengurus',
            self::CREATE_COMMITTEE => 'Tambah Pengurus',
            self::EDIT_COMMITTEE => 'Ubah Data Pengurus',
            self::DELETE_COMMITTEE => 'Hapus Pengurus',

            self::VIEW_USERS => 'Lihat Data Pengguna',
            self::CREATE_USERS => 'Tambah Pengguna',
            self::EDIT_USERS => 'Ubah Data Pengguna',
            self::DELETE_USERS => 'Hapus Pengguna',

            self::VIEW_ROLES => 'Lihat Peran & Hak Akses',
            self::CREATE_ROLES => 'Tambah Peran Baru',
            self::EDIT_ROLES => 'Ubah Peran & Hak Akses',
            self::DELETE_ROLES => 'Hapus Peran',

            self::VIEW_SHORTLINKS => 'Lihat Data Shortlink',
            self::CREATE_SHORTLINKS => 'Tambah Shortlink',
            self::EDIT_SHORTLINKS => 'Ubah Shortlink',
            self::DELETE_SHORTLINKS => 'Hapus Shortlink',
        };
    }

    /**
     * Get functional module category name for grouping in UI.
     */
    public function module(): string
    {
        return match ($this) {
            self::VIEW_DASHBOARD => 'Modul Utama (Dashboard)',
            self::VIEW_DIVISIONS,
            self::CREATE_DIVISIONS,
            self::EDIT_DIVISIONS,
            self::DELETE_DIVISIONS => 'Kelola Divisi',

            self::VIEW_WORK_PROGRAMS,
            self::CREATE_WORK_PROGRAMS,
            self::EDIT_WORK_PROGRAMS,
            self::DELETE_WORK_PROGRAMS => 'Kelola Program Kerja',

            self::VIEW_NEWS,
            self::CREATE_NEWS,
            self::EDIT_NEWS,
            self::DELETE_NEWS => 'Kelola Berita',

            self::VIEW_COMMITTEE,
            self::CREATE_COMMITTEE,
            self::EDIT_COMMITTEE,
            self::DELETE_COMMITTEE => 'Kelola Pengurus',

            self::VIEW_USERS,
            self::CREATE_USERS,
            self::EDIT_USERS,
            self::DELETE_USERS => 'Kelola Pengguna',

            self::VIEW_ROLES,
            self::CREATE_ROLES,
            self::EDIT_ROLES,
            self::DELETE_ROLES => 'Hak Akses & Role',

            self::VIEW_SHORTLINKS,
            self::CREATE_SHORTLINKS,
            self::EDIT_SHORTLINKS,
            self::DELETE_SHORTLINKS => 'Kelola Shortlink',
        };
    }

    /**
     * Get detailed explanation of what this permission grants.
     */
    public function description(): string
    {
        return match ($this) {
            self::VIEW_DASHBOARD => 'Mengakses dan melihat ringkasan statistik, aktivitas, dan metrik operasional organisasi.',

            self::VIEW_DIVISIONS => 'Melihat daftar divisi, profil divisi, serta informasi anggota dalam divisi.',
            self::CREATE_DIVISIONS => 'Menambahkan struktur divisi atau departemen baru ke dalam organisasi UKM.',
            self::EDIT_DIVISIONS => 'Mengubah data profil divisi, visi-misi, deskripsi, dan logo departemen.',
            self::DELETE_DIVISIONS => 'Menghapus data divisi atau departemen dari sistem CMS.',

            self::VIEW_WORK_PROGRAMS => 'Melihat agenda program kerja divisi, jadwal kegiatan, dan status pelaksanaan.',
            self::CREATE_WORK_PROGRAMS => 'Mendaftarkan program kerja baru beserta PIC dan target pelaksanaan.',
            self::EDIT_WORK_PROGRAMS => 'Memperbarui rincian kegiatan, jadwal, progres kerja, dan status proker.',
            self::DELETE_WORK_PROGRAMS => 'Menghapus program kerja yang telah dibatalkan atau tidak aktif.',

            self::VIEW_NEWS => 'Melihat katalog artikel, berita kegiatan, dan arsip rilis pers organisasi.',
            self::CREATE_NEWS => 'Membuat draf dan menulis artikel atau rilis berita baru.',
            self::EDIT_NEWS => 'Menyunting isi konten berita, judul, kategori, thumbnail, dan status artikel.',
            self::DELETE_NEWS => 'Menghapus berita atau artikel publikasi dari portal sistem.',

            self::VIEW_COMMITTEE => 'Melihat struktur susunan pengurus, bagan kepengurusan, dan profil pengurus aktif.',
            self::CREATE_COMMITTEE => 'Menambahkan pengurus baru ke dalam struktur periode kepengurusan.',
            self::EDIT_COMMITTEE => 'Mengubah posisi jabatan, divisi penugasan, dan biodata pengurus.',
            self::DELETE_COMMITTEE => 'Menghapus data anggota dari susunan kepengurusan aktif.',

            self::VIEW_USERS => 'Melihat daftar seluruh akun pengguna, email, peran, dan status verifikasi akun.',
            self::CREATE_USERS => 'Menambahkan akun pengguna baru dan menetapkan peran akun secara langsung.',
            self::EDIT_USERS => 'Mengubah data pengguna, mengganti kata sandi, dan memperbarui peran akun.',
            self::DELETE_USERS => 'Menghapus akun pengguna dari database sistem.',

            self::VIEW_ROLES => 'Melihat daftar peran pengguna dan katalog master hak akses sistem.',
            self::CREATE_ROLES => 'Membuat peran organisasi baru untuk penugasan hak akses pengguna.',
            self::EDIT_ROLES => 'Mengubah nama peran dan mengatur konfigurasi izin akses granular per modul.',
            self::DELETE_ROLES => 'Menghapus peran kustom yang tidak lagi digunakan dalam organisasi.',

            self::VIEW_SHORTLINKS => 'Melihat daftar tautan pintas (URL shortener) dan statistik jumlah kunjungan/klik.',
            self::CREATE_SHORTLINKS => 'Membuat tautan pintas baru dengan alias kustom menuju URL tujuan.',
            self::EDIT_SHORTLINKS => 'Mengubah URL tujuan atau mengedit alias tautan pintas yang telah dibuat.',
            self::DELETE_SHORTLINKS => 'Menonaktifkan atau menghapus tautan pintas dari sistem.',
        };
    }

    /**
     * Get grouped catalog for frontend display and permission configuration modals.
     *
     * @return array<string, array<int, array{name: string, label: string, description: string, module: string}>>
     */
    public static function groupedCatalog(): array
    {
        $grouped = [];

        foreach (self::cases() as $case) {
            $module = $case->module();
            if (! isset($grouped[$module])) {
                $grouped[$module] = [];
            }

            $grouped[$module][] = [
                'name' => $case->value,
                'label' => $case->label(),
                'description' => $case->description(),
                'module' => $module,
            ];
        }

        return $grouped;
    }
}
