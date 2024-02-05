export {};
declare global {
    export namespace inertia {
        export interface PageProps {
            user: {
                id: number;
                name: string;
                email: string;
                created_at: Date;
                updated_at: Date;
            };
            errorBags: unknown;
            errors: unknown;
        }
    }
}
