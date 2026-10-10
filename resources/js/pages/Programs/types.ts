import type { PaginationLink } from '@/Components/Common/Pagination.vue';

export interface DivisiOption {
    id: number;
    nama: string;
    slug: string;
}

export interface PengurusOption {
    id: number;
    nama: string;
    jabatan: string;
    foto?: string | null;
    foto_url?: string | null;
}

export type RegistrationStatus =
    | 'none'
    | 'upcoming'
    | 'open'
    | 'closed';

export type ProgramExecutionStatusKey =
    | 'mendatang'
    | 'berjalan'
    | 'selesai';

export interface ProgramItem {
    id: number;
    divisi_id: number;
    divisi?: DivisiOption;
    pengurus_id?: number | null;
    pengurus?: PengurusOption | null;

    judul_program: string;
    slug: string;
    location_name: string;
    deskripsi?: string | null;

    foto: string | null;
    foto_url: string | null;

    open_regis_panitia: string | null;
    close_regis_panitia: string | null;
    gform_panitia: string | null;

    open_regis_peserta: string;
    close_regis_peserta: string;
    gform_peserta: string | null;

    status_panitia: RegistrationStatus;
    status_peserta: RegistrationStatus;

    created_at: string;
    updated_at: string;
}

export interface PaginatedPrograms {
    data: ProgramItem[];
    from: number | null;
    to: number | null;
    total: number;
    current_page: number;
    last_page: number;
    per_page: number;
    links: PaginationLink[];
}

export interface ProgramFilters {
    search: string;
    divisi_id: string;
    status: string;
}

export interface ProgramStats {
    total: number;
    panitia_open: number;
    peserta_open: number;
}

export interface ProgramPIC {
    nama: string;
    jabatan: string;
    initials: string;
    avatarBg: string;
}

export interface ExecutionStatus {
    key: ProgramExecutionStatusKey;
    label: 'Mendatang' | 'Berjalan' | 'Selesai';
    progress: number;
    textClass: string;
    barClass: string;
    badgeClass: string;
    dotClass: string;
}
