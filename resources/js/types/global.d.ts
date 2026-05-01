import type { Auth } from '@/types/auth';

declare global {
    function route(
        name: string,
        params?: Record<string, unknown> | unknown[],
        absolute?: boolean,
    ): string;
}

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            environment: string;
            auth: Auth;
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
    }
}
