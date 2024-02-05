/**
 * @author Abel David.
 */
export interface Product {
    id: number
    name: string
    active: boolean
    type: "product"|"service"
    cost_price: number
    daily_price: number
    weekly_price: number
    biweekly_price: number
    monthly_price: number
    replacement_price: number
    period_prices: Record<number, number>
    tax: number
    inventory: {
        rented: 0,
        id: 0,
        quantity: 0,
        stock: 0
    }     
}
