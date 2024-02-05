import {Permission} from "./Permission";

/**
 * Permissions module
 */
export class Module {

    /**
     * Name.
     */
    name: string

    /**
     * Permissions
     */
    permissions: Permission[]

    constructor(name: string, permissions: Permission[]) {

        this.name = name
        this.permissions = permissions
    }

    /**
     * Indicate if all permissions are actives.
     *
     * @return {boolean}
     */
    public get active(): boolean {

        for (const permission of this.permissions) {

            if (! permission.active) {

                return false
            }
        }

        return true
    }

    /**
     * Set all permissions as actives.
     *
     * @param val
     */
    public set active(val: boolean) {

        for (const permission of this.permissions) {

            permission.active = val
        }
    }

    /**
     * Get modules from data server.
     *
     * @param serverModules
     */
    static fromDataServer(serverModules: any): Module[] {

        const collection = []

        for (const module of serverModules) {

            const permissions = []

            for (const permission of module.permissions) {

                permissions.push(new Permission().apply(p => {

                    p.name = permission.name
                    p.translate_name = permission.translate_name
                    p.active = permission.active
                }))
            }

            collection.push(new Module(module.name, permissions))
        }

        return collection
    }
}
