/**
 * Row action.
 */
import {Applicable} from "../Common/Applicable";
import {Composer} from "vue-i18n";

export class RowAction extends Applicable<RowAction>
{
    /**
     * @type {string}
     */
    label: string

    /**
     * @type {string}
     */
    route: string

    /**
     * @type {any|null}
     */
    args: any|undefined = undefined

    /**
     * @type {string|null}
     */
    icon: string|null = null

    /**
     * @type {string}
     */
    method: string = 'get'

    /**
     * @type {() => boolean|null}
     */
    callback: () => boolean|null = null

    /**
     * @type {(result: any) => void|null}
     */
    onSuccess: (result: any) => void|null

    /**
     * @type {() => void|null}
     */
    onError: () => void|null

    /**
     * Build commons actions.
     *
     * @param editRoute
     * @param deleteRoute
     * @param canEdit
     * @param canDelete
     * @param editIcon
     * @param deleteIcon
     * @param args
     * @param i18n
     * @param onDelete
     */
    public static buildCommonsActions(
        editRoute: string,
        deleteRoute: string,
        canEdit: boolean,
        canDelete: boolean,
        editIcon: string,
        deleteIcon: string,
        args: any,
        i18n: Composer,
        onDelete: () => void): RowAction[]
    {
        const actions = []

        if (canEdit) {

            actions.push(new RowAction().apply(action => {
                action.label = i18n.t('actions.edit')
                action.route = editRoute
                action.args = args
                action.icon = editIcon
            }))
        }

        if (canDelete) {

            actions.push(new RowAction().apply(action => {
                action.label = i18n.t('actions.delete')
                action.route = deleteRoute
                action.args = args
                action.method = 'delete'
                action.icon = deleteIcon
                action.onSuccess = () => {
                    onDelete()
                }
            }))
        }

        return actions

    }

    public static buildCommonsActionsTwo(
        editRoute: string,
        deleteRoute: string,
        detailsRoute: string,
        canEdit: boolean,
        canDelete: boolean,
        canDetails: boolean,
        editIcon: string,
        deleteIcon: string,
        detailsIcon: string,
        args: any,
        i18n: Composer,
        onDelete: () => void): RowAction[]
    {
        const actions = []

        if (canEdit) {

            actions.push(new RowAction().apply(action => {
                action.label = i18n.t('actions.edit')
                action.route = editRoute
                action.args = args
                action.icon = editIcon
            }))
        }

        if (canDelete) {

            actions.push(new RowAction().apply(action => {
                action.label = i18n.t('actions.delete')
                action.route = deleteRoute
                action.args = args
                action.method = 'delete'
                action.icon = deleteIcon
                action.onSuccess = () => {
                    onDelete()
                }
            }))
        }

        if (canDetails) {

            actions.push(new RowAction().apply(action => {
                action.label = i18n.t('actions.details')
                action.route = detailsRoute
                action.args = args
                action.icon = detailsIcon
            }))
        }

        return actions

    }
}
