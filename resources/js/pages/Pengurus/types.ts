import type { PaginationLink } from '@/Components/Common/Pagination.vue';

export type SosmedKey = 'instagram' | 'linkedin' | 'github';

export const SOSMED_KEYS: SosmedKey[] = ['instagram', 'linkedin', 'github'];

export const SOSMED_LABELS: Record<SosmedKey, string> = {
    instagram: 'Instagram',
    linkedin: 'LinkedIn',
    github: 'GitHub',
};

export interface PengurusItem {
    id: number;
    nama: string;
    jabatan: string;
    periode: string;
    foto: string | null;
    foto_url: string | null;
    sosmed: Partial<Record<SosmedKey, string>> | null;
    urutan: number;
    aktif: boolean;
    created_at: string;
    updated_at: string;
}

export interface PaginatedPengurus {
    data: PengurusItem[];
    from: number | null;
    to: number | null;
    total: number;
    links: PaginationLink[];
}

export interface PengurusFilters {
    search: string;
    periode: string;
    jabatan: string;
    status: '' | 'aktif' | 'nonaktif';
}

export interface PengurusOptions {
    periode: string[];
    jabatan: string[];
}

export function initials(nama: string): string {
    return nama
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((word) => word[0]?.toUpperCase() ?? '')
        .join('');
}
