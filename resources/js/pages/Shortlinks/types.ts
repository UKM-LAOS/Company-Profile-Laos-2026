import type { PaginationLink } from '@/Components/Common/Pagination.vue';

export interface ShortlinkItem {
    id: number;
    user_id: number | null;
    destination_url: string;
    short_code: string;
    click_count: number;
    is_active: boolean;
    expires_at: string | null;
    created_at: string;
    user: { id: number; name: string } | null;
}

export interface PaginatedShortlinks {
    data: ShortlinkItem[];
    from: number | null;
    to: number | null;
    total: number;
    links: PaginationLink[];
}
