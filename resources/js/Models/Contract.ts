/**
 * @author Abel David.
 */
export interface Contract {
    id: number
    date: string
    period: number
    tax_exempt: boolean
    quantity: number
    client: {
        id: number
        name: string
    }
    products: []
}
