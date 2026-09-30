import type { User } from './auth';

export * from './auth';

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User;
    };
    ziggy?: {
        location: string;
        [key: string]: unknown;
    };
    [key: string]: unknown;
};
