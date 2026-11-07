import type { Auth } from '@/types/auth';
import type { AppLayoutProps, AuthLayoutProps, FlashToast } from '@/types/ui';

declare module '@inertiajs/core' {
    export interface InertiaConfig {
        sharedPageProps: {
            name: string;
            environment: string;
            auth: Auth;
            sidebarOpen: boolean;
            [key: string]: unknown;
        };
        flashDataType: {
            toast?: FlashToast;
        };
        layoutProps: Pick<AppLayoutProps, 'breadcrumbs'> &
            Pick<AuthLayoutProps, 'title' | 'description'>;
    }
}
