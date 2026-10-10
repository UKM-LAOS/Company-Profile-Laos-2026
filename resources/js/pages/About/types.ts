export interface PengurusDivisi {
    id: number;
    nama: string;
}

export interface PengurusItem {
    id: number;
    nama: string;
    jabatan: string;
    periode: string;
    foto: string | null;
    foto_url: string | null;
    divisi?: PengurusDivisi | null;
    sosmed?: Record<string, string> | string | null;
    urutan: number;
    aktif: boolean;
}

export interface SosmedLink {
    key: string;
    url: string | null;
    icon: string;
    label: string;
    active: boolean;
}

export function normalizePeriode(value: string): string {
    return value.trim().replace(/-/g, "/");
}

export function periodeDisplay(periode: string): string {
    return periode.replace("/", "-");
}

export function photoUrl(item: PengurusItem): string | null {
    if (item.foto) return `/storage/${item.foto}`;
    return item.foto_url;
}

export function divisionLabel(item: PengurusItem): string {
    return item.divisi?.nama ?? item.jabatan;
}

export function initials(nama: string): string {
    return nama
        .split(/\s+/)
        .filter(Boolean)
        .slice(0, 2)
        .map((w) => w[0]?.toUpperCase() ?? "")
        .join("");
}

const SOSMED_ORDER = ["github", "linkedin", "instagram"] as const;

const SOSMED_ICONS: Record<string, { icon: string; label: string }> = {
    instagram: { icon: "/assets/instagram.svg", label: "Instagram" },
    linkedin: { icon: "/assets/linkedin.svg", label: "LinkedIn" },
    github: { icon: "/assets/github.svg", label: "GitHub" },
};

function sosmedMap(item: PengurusItem): Record<string, unknown> {
    if (typeof item.sosmed === "string") {
        try {
            const parsed: unknown = JSON.parse(item.sosmed);
            if (
                parsed &&
                typeof parsed === "object" &&
                !Array.isArray(parsed)
            ) {
                return parsed as Record<string, unknown>;
            }
        } catch {
            return {};
        }
        return {};
    } else if (item.sosmed && typeof item.sosmed === "object") {
        return item.sosmed as Record<string, unknown>;
    }
    return {};
}

export function sosmedLinks(item: PengurusItem): SosmedLink[] {
    const raw = sosmedMap(item);
    return SOSMED_ORDER.map((key) => {
        const value = raw[key];
        const url =
            typeof value === "string" && value.trim() !== ""
                ? value.trim()
                : null;
        return {
            key,
            url,
            icon: SOSMED_ICONS[key]!.icon,
            label: SOSMED_ICONS[key]!.label,
            active: url !== null,
        };
    });
}
