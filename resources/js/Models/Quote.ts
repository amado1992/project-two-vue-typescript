/**
 * @author Abel David.
 */
export interface Quote {
    id: number
    date: string
    period: number
    tax_exempt: boolean
    observations: string
    total: number
    commercial: {
        id: number
        name: string
    }
    project: {
        id: number,
        name: string,
        client: {
            id: number
            name: string
        }
    }
    products: []
}
