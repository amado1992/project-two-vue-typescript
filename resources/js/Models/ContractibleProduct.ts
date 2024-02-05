/**
 * @author Abel David.
 */
export interface ContractibleProduct {
    id: number
    name: string
    price: number
    period_prices: Record<number, number>
    quantity: number
    discount: number
    discount_value: number
    subtotal: number
    tax: number
    product_tax: number
    total: number
   
}
