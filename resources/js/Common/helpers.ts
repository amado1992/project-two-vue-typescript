import { Notify } from "quasar";
import { PageProps } from "@inertiajs/core";
import { ContractibleProduct } from "../Models/ContractibleProduct";
import { Composer } from "vue-i18n";

/**
 * Launch a Quasar Notification.
 */

export function showNotification(
    color: string,
    message: string,
    position: string = "bottom-right"
): void {
    // @ts-ignore
    Notify.create({
        color,
        message,
        position,
    });
}

/**
 * Indicate if user has the given permission.
 *
 * @param permission
 * @param props
 */
export function can(
    permission: string[] | string | ((props: PageProps) => boolean) | null,
    props: PageProps
): boolean {
    if (permission) {
        if (typeof permission == "function") {
            return permission(props);
        }

        if (typeof permission == "string") {
            return props.auth.can.findIndex((p) => p.name == permission) != -1;
        }

        for (const permissionElement of permission) {
            if (
                props.auth.can.findIndex((p) => p.name == permissionElement) !=
                -1
            ) {
                return true;
            }
        }

        return false;
    }

    return true;
}

export function getUpdatedAt(row: any): string | null {
    if (row.updated_by || row.updated_at) {
        return row.updated_at != row.created_at ? row.updated_at : " ";
    }

    return null;
}

/**
 *
 * @param products
 */
export function buildContractibleProducts(
    products: any[]
): ContractibleProduct[] {
    const list: ContractibleProduct[] = [];

    products.forEach((p) => {
        list.push({
            id: p.id,
            name: p.name,
            discount: p.pivot.percent_discount,
            discount_value: p.pivot.discount,
            period_prices: p.period_prices,
            price: p.pivot.price,
            product_tax: p.tax,
            tax: p.pivot.tax,
            quantity: p.pivot.quantity,
            subtotal: p.pivot.subtotal,
            total: 0,
        });
    });

    return list;
}

/**
 *
 * @param value
 */
export function money(value: number): string {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
        minimumFractionDigits: 2,
    }).format(value);
}

/**
 *
 * @param value
 */
export function percentage(value: number): string {
    return value + "%";
}

/**
 *
 * @param i18n
 */
export function periods(i18n: Composer): { label: string; value: number }[] {
    return [
        {
            label: i18n.t("quotes.periods.1_day"),
            value: 1,
        },
        {
            label: i18n.t("quotes.periods.7_days"),
            value: 7,
        },
        {
            label: i18n.t("quotes.periods.15_days"),
            value: 15,
        },
        {
            label: i18n.t("quotes.periods.30_days"),
            value: 30,
        },
    ];
}

/**
 *
 * @param status
 * @param i18n
 */
export function status(status: string, i18n: Composer): string[] {
    switch (status) {
        case 'active':
            return [
                i18n.t('messages.active'),
                'bg-green'
            ]
        case 'pending':
            return [
                i18n.t('messages.pending'),
                'bg-yellow'
            ]
        case 'defeated':
            return [
                i18n.t('messages.defeated'),
                'bg-orange'
            ]
        case 'finished':
            return [
                i18n.t('messages.finished'),
                'bg-black'
            ]
        case 'renovated':
            return [
                i18n.t('messages.renovated'),
                'bg-primary'
            ]
        case 'cancelled':
            return [
                i18n.t('messages.annulled'),
                'bg-negative'
            ]
    }
}

/**
 *
 * @param i18n
 */
export function movementsTypes(i18n: Composer): Map<string, string> {
    return new Map<string, string>([
        ["increment", i18n.t("inventories.movements.types.increment")],
        ["decrement", i18n.t("inventories.movements.types.decrement")],
    ]);
}

export function getContractibleProducts(
    products: any[],
    products_selected: []
) {

    let sum = 0;

    products.forEach((p) => {

        if (products_selected.includes(p.id)) {
            sum = sum + p.pivot.subtotal;
        }

    });

    return sum;
}


