<?php

namespace App\Enums;

enum Permission: string
{
    // Dashboard & Umum
    case VIEW_DASHBOARD = 'view_dashboard';

    // CMS (Divisi, Program Kerja, Berita, Pengurus)
    case MANAGE_DIVISIONS = 'manage_divisions';
    case MANAGE_WORK_PROGRAMS = 'manage_work_programs';
    case MANAGE_NEWS = 'manage_news';
    case MANAGE_COMMITTEE = 'manage_committee';

    // Sistem
    case MANAGE_USERS = 'manage_users';
    case MANAGE_ROLES = 'manage_roles';

    // Fitur
    case MANAGE_SHORTLINKS = 'manage_shortlinks';

    // General Content Management
    case VIEW_CONTENT = 'view_content';
    case CREATE_CONTENT = 'create_content';
    case EDIT_CONTENT = 'edit_content';
    case DELETE_CONTENT = 'delete_content';
    case PUBLISH_CONTENT = 'publish_content';
    case MANAGE_SETTINGS = 'manage_settings';

    /**
     * Get human-readable label.
     */
    public function label(): string
    {
        return match ($this) {
            self::VIEW_DASHBOARD => 'Melihat Dashboard',
            self::MANAGE_DIVISIONS => 'Kelola Divisi',
            self::MANAGE_WORK_PROGRAMS => 'Kelola Program Kerja',
            self::MANAGE_NEWS => 'Kelola Berita',
            self::MANAGE_COMMITTEE => 'Kelola Pengurus',
            self::MANAGE_USERS => 'Kelola Pengguna',
            self::MANAGE_ROLES => 'Hak Akses & Role',
            self::MANAGE_SHORTLINKS => 'Kelola Shortlink',
            self::VIEW_CONTENT => 'Melihat Konten',
            self::CREATE_CONTENT => 'Membuat Konten',
            self::EDIT_CONTENT => 'Mengubah Konten',
            self::DELETE_CONTENT => 'Menghapus Konten',
            self::PUBLISH_CONTENT => 'Mempublikasikan Konten',
            self::MANAGE_SETTINGS => 'Mengelola Pengaturan Sistem',
        };
    }
}
