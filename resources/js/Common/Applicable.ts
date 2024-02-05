export abstract class Applicable<T extends Applicable<T>> {

    /**
     * @param callback
     */
    apply(callback: (object: T) => void): T {

        callback(this)

        //@ts-ignore
        return this
    }
}

