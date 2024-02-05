/**
 * Permission.
 */
import {Applicable} from "../Common/Applicable";

export class Permission extends Applicable<Permission> {

    /**
     * Name
     */
    name: string

    /**
     * Translate name.
     */
    translate_name: string

    /**
     * Active.
     */
    active: boolean
}
