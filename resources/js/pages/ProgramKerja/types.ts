export interface DivisiItem {
    id: number;
    nama: string;
    slug: string;
}

export interface PengurusPIC {
    id: number;
    nama: string;
    jabatan?: string | null;
    foto?: string | null;
}

export interface ProgramItem {
    id: number;
    divisi_id: number;
    pengurus_id?: number | null;
    judul_program: string;
    slug: string;
    location_name?: string | null;
    deskripsi?: string | null;
    foto?: string | null;
    foto_url?: string | null;
    open_regis_panitia?: string | null;
    close_regis_panitia?: string | null;
    gform_panitia?: string | null;
    open_regis_peserta?: string | null;
    close_regis_peserta?: string | null;
    gform_peserta?: string | null;
    status_panitia?: string;
    status_peserta?: string;
    divisi?: DivisiItem | null;
    pengurus?: PengurusPIC | null;
}

export interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

export interface PaginatedPrograms {
    data: ProgramItem[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number | null;
    to: number | null;
    links: PaginationLink[];
}
