<script setup lang="ts">
defineProps<{
    name:
        | 'dashboard'
        | 'divisi'
        | 'proker'
        | 'berita'
        | 'pengurus'
        | 'pengguna'
        | 'roles'
        | 'shortlink'
        | 'dots-vertical'
        | 'user-group'
        | 'check-circle'
        | 'nodes'
        | 'book'
        | 'menu'
        | 'close'
        | 'logout'
        | 'profile'
        | 'sun'
        | 'moon'
        | 'plus'
        | 'key'
        | 'shield'
        | 'check'
        | 'lock'
        | 'mail'
        | 'trash'
        | 'edit'
        | 'refresh'
        | 'chevron-left'
        | 'chevron-right'
        | 'spinner'
        | 'search'
        | 'check-check'
        | 'sliders';
    className?: string;
}>();
</script>

<template>
    <!-- Dashboard: 4 squares grid -->
    <svg
        v-if="name === 'dashboard'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <rect x="3" y="3" width="7" height="7" rx="1.5" />
        <rect x="14" y="3" width="7" height="7" rx="1.5" />
        <rect x="14" y="14" width="7" height="7" rx="1.5" />
        <rect x="3" y="14" width="7" height="7" rx="1.5" />
    </svg>

    <!-- Kelola Divisi: Archive / Box with drawer handle -->
    <svg
        v-else-if="name === 'divisi'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M4 4h16c1.1 0 2 .9 2 2v2H2V6c0-1.1.9-2 2-2z" />
        <path d="M2 8v10c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8H2z" />
        <line x1="10" y1="12" x2="14" y2="12" />
    </svg>

    <!-- Kelola Program Kerja: Calendar with check/lines -->
    <svg
        v-else-if="name === 'proker'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
        <line x1="16" y1="2" x2="16" y2="6" />
        <line x1="8" y1="2" x2="8" y2="6" />
        <line x1="3" y1="10" x2="21" y2="10" />
        <path d="m9 16 2 2 4-4" />
    </svg>

    <!-- Kelola Berita: News / Article card -->
    <svg
        v-else-if="name === 'berita'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <rect width="18" height="18" x="3" y="3" rx="2" />
        <path d="M7 7h10" />
        <path d="M7 12h10" />
        <path d="M7 17h6" />
    </svg>

    <!-- Kelola Pengurus: Hierarchy / Users group -->
    <svg
        v-else-if="name === 'pengurus'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
    </svg>

    <!-- Kelola Pengguna: User single -->
    <svg
        v-else-if="name === 'pengguna'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
    </svg>

    <!-- Hak Akses & Role: Shield with check -->
    <svg
        v-else-if="name === 'roles'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
        <path d="m9 12 2 2 4-4" />
    </svg>

    <!-- Shortlink: Chain link -->
    <svg
        v-else-if="name === 'shortlink'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
        <path
            d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"
        />
    </svg>

    <!-- Stat: User Group (Total Pengurus) -->
    <svg
        v-else-if="name === 'user-group'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
        <circle cx="9" cy="7" r="4" />
        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
    </svg>

    <!-- Stat: Check Circle (Program Kerja) -->
    <svg
        v-else-if="name === 'check-circle'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
        <polyline points="22 4 12 14.01 9 11.01" />
    </svg>

    <!-- Stat: Organization Nodes (Divisi Organisasi) -->
    <svg
        v-else-if="name === 'nodes'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <circle cx="12" cy="12" r="3" />
        <circle cx="6" cy="6" r="2" />
        <circle cx="18" cy="6" r="2" />
        <circle cx="6" cy="18" r="2" />
        <circle cx="18" cy="18" r="2" />
        <line x1="7.5" y1="7.5" x2="10" y2="10" />
        <line x1="16.5" y1="7.5" x2="14" y2="10" />
        <line x1="7.5" y1="16.5" x2="10" y2="14" />
        <line x1="16.5" y1="16.5" x2="14" y2="14" />
    </svg>

    <!-- Stat: Book (Materi & Publikasi) -->
    <svg
        v-else-if="name === 'book'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
        <path
            d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"
        />
        <line x1="8" y1="7" x2="16" y2="7" />
        <line x1="8" y1="11" x2="14" y2="11" />
    </svg>

    <!-- Three Dots Vertical -->
    <svg
        v-else-if="name === 'dots-vertical'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <circle cx="12" cy="12" r="1" />
        <circle cx="12" cy="5" r="1" />
        <circle cx="12" cy="19" r="1" />
    </svg>

    <!-- Menu Hamburger -->
    <svg
        v-else-if="name === 'menu'"
        :class="className || 'h-6 w-6'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <line x1="4" y1="12" x2="20" y2="12" />
        <line x1="4" y1="6" x2="20" y2="6" />
        <line x1="4" y1="18" x2="20" y2="18" />
    </svg>

    <!-- Close Icon -->
    <svg
        v-else-if="name === 'close'"
        :class="className || 'h-6 w-6'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <line x1="18" y1="6" x2="6" y2="18" />
        <line x1="6" y1="6" x2="18" y2="18" />
    </svg>

    <!-- Profile -->
    <svg
        v-else-if="name === 'profile'"
        :class="className || 'h-4 w-4'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
        <circle cx="12" cy="7" r="4" />
    </svg>

    <!-- Logout -->
    <svg
        v-else-if="name === 'logout'"
        :class="className || 'h-4 w-4'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
        <polyline points="16 17 21 12 16 7" />
        <line x1="21" y1="12" x2="9" y2="12" />
    </svg>

    <!-- Sun (Light Mode) -->
    <svg
        v-else-if="name === 'sun'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <circle cx="12" cy="12" r="5" />
        <line x1="12" y1="1" x2="12" y2="3" />
        <line x1="12" y1="21" x2="12" y2="23" />
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64" />
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78" />
        <line x1="1" y1="12" x2="3" y2="12" />
        <line x1="21" y1="12" x2="23" y2="12" />
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36" />
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22" />
    </svg>

    <!-- Moon (Dark Mode) -->
    <svg
        v-else-if="name === 'moon'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
    </svg>

    <!-- Plus Icon (e.g. + Tambah Role) -->
    <svg
        v-else-if="name === 'plus'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <line x1="12" y1="5" x2="12" y2="19" />
        <line x1="5" y1="12" x2="19" y2="12" />
    </svg>

    <!-- Key (Hak Akses) -->
    <svg
        v-else-if="name === 'key'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path
            d="M21 2l-2 2m-1.5 1.5L16 7l-1.5-1.5L13 7l-1.5-1.5L10 7 7.5 4.5a5 5 0 1 0 7 7L22 4l-1-2z"
        />
        <circle cx="7.5" cy="16.5" r="1.5" />
    </svg>

    <!-- Shield (Roles / Keamanan) -->
    <svg
        v-else-if="name === 'shield'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
    </svg>

    <!-- Check (Success / Checklist) -->
    <svg
        v-else-if="name === 'check'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <polyline points="20 6 9 17 4 12" />
    </svg>

    <!-- Lock (Password) -->
    <svg
        v-else-if="name === 'lock'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
        <path d="M7 11V7a5 5 0 0 1 10 0v4" />
    </svg>

    <!-- Mail (Email) -->
    <svg
        v-else-if="name === 'mail'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path
            d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"
        />
        <polyline points="22,6 12,13 2,6" />
    </svg>

    <!-- Trash (Delete / Zona Bahaya) -->
    <svg
        v-else-if="name === 'trash'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <polyline points="3 6 5 6 21 6" />
        <path
            d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"
        />
        <line x1="10" y1="11" x2="10" y2="17" />
        <line x1="14" y1="11" x2="14" y2="17" />
    </svg>

    <!-- Edit (Pencil) -->
    <svg
        v-else-if="name === 'edit'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7" />
        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z" />
    </svg>

    <!-- Refresh (Reload) -->
    <svg
        v-else-if="name === 'refresh'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <polyline points="23 4 23 10 17 10" />
        <polyline points="1 20 1 14 7 14" />
        <path
            d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"
        />
    </svg>

    <!-- Chevron Left -->
    <svg
        v-else-if="name === 'chevron-left'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <polyline points="15 18 9 12 15 6" />
    </svg>

    <!-- Chevron Right -->
    <svg
        v-else-if="name === 'chevron-right'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <polyline points="9 18 15 12 9 6" />
    </svg>

    <!-- Spinner (Loading) -->
    <svg
        v-else-if="name === 'spinner'"
        :class="[className || 'h-5 w-5', 'animate-spin']"
        viewBox="0 0 24 24"
        fill="none"
    >
        <circle
            class="opacity-25"
            cx="12"
            cy="12"
            r="10"
            stroke="currentColor"
            stroke-width="4"
        />
        <path
            class="opacity-75"
            fill="currentColor"
            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
        />
    </svg>

    <!-- Search -->
    <svg
        v-else-if="name === 'search'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <circle cx="11" cy="11" r="8" />
        <line x1="21" y1="21" x2="16.65" y2="16.65" />
    </svg>

    <!-- Check-Check (Double Check / Select All) -->
    <svg
        v-else-if="name === 'check-check'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <path d="M18 6L7 17l-5-5" />
        <path d="M22 10l-7.5 7.5L13 16" />
    </svg>

    <!-- Sliders (Configure / Settings / Filters) -->
    <svg
        v-else-if="name === 'sliders'"
        :class="className || 'h-5 w-5'"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2"
        stroke-linecap="round"
        stroke-linejoin="round"
    >
        <line x1="4" y1="21" x2="4" y2="14" />
        <line x1="4" y1="10" x2="4" y2="3" />
        <line x1="12" y1="21" x2="12" y2="12" />
        <line x1="12" y1="8" x2="12" y2="3" />
        <line x1="20" y1="21" x2="20" y2="16" />
        <line x1="20" y1="12" x2="20" y2="3" />
        <line x1="1" y1="14" x2="7" y2="14" />
        <line x1="9" y1="8" x2="15" y2="8" />
        <line x1="17" y1="16" x2="23" y2="16" />
    </svg>
</template>
