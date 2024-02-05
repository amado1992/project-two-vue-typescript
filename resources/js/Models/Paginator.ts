import {router} from "@inertiajs/vue3";
import {Applicable} from "../Common/Applicable";
import {Pagination} from "./Pagination";
import {ServerPaginator} from "./ServerPaginator";
import {GlobalEventCallback, Visit} from "@inertiajs/core/types/types";

/**
 * Table paginator.
 *
 * @author Abel David.
 */
export class Paginator<T> extends Applicable<Paginator<T>> implements Pagination
{
    /**
     * @type {number}
     */
    page: number

    /**
     * @type {number}
     */
    rowsPerPage: number

    /**
     * @type {number}
     */
    rowsNumber: number

    /**
     * @type {string}
     */
    sortBy: string

    /**
     * @type {boolean}
     */
    descending: boolean

    /**
     * @type {[]}
     */
    data: T

    /**
     * @type {string}
     */
    path: string

    /**
     * @type {string}
     */
    filter: string

    /**
     * @type {[]}
     */
    columns: []

    /**
     * @type {Map<string, any>}
     * @private
     */
    private readonly args: Map<string, any>

    /**
     *
     * @param serverData
     * @param columns
     */
    constructor(serverData: ServerPaginator<T>, columns) {
        super();

        this.columns = columns
        this.args = new Map<string, any>()

        this.fillData(serverData)
    }

    /**
     * Request table.
     *
     * @param props
     * @param args
     * @param options
     */
    onRequest(
        props: { filter: string, pagination: Pagination },
        args?: Map<string, any>,
        options?: Exclude<Partial<Visit & {
            onCancelToken: {
                ({cancel}: {
                    cancel: VoidFunction;
                }): void;
            };
            onBefore: GlobalEventCallback<'before'>;
            onStart: GlobalEventCallback<'start'>;
            onProgress: GlobalEventCallback<'progress'>;
            onFinish: GlobalEventCallback<'finish'>;
            onCancel: GlobalEventCallback<'cancel'>;
            onSuccess: GlobalEventCallback<'success'>;
            onError: GlobalEventCallback<'error'>;
        }>, "method" | "data">
    ) {

        const routerArgs = this.buildRouterArgs(props, args)

        if (! options) {

            options = {
                preserveScroll: true
            }
        }

        router.get(this.path, routerArgs, options)
    }

    /**
     * Fill data.
     *
     * @param serverData
     */
    fillData(serverData: ServerPaginator<T>): void {

        let desc = serverData.descending

        if (desc == null) {

            desc = false
        }

        this.page = serverData.pagination.current_page
        this.rowsPerPage = serverData.pagination.per_page
        this.rowsNumber = serverData.pagination.total
        this.filter = serverData.filter
        this.sortBy = serverData.sortBy
        this.descending = desc
        this.data = serverData.pagination.data
        this.path = serverData.pagination.path

        for (const i in serverData.args) {

            this.args.set(i, serverData.args[i])
        }
    }

    /**
     * @return {Map<string, any>}
     */
    getArgs(): Map<string, any> {
        return this.args
    }

    /**
     *
     * @param props
     * @param args
     * @private
     */
    private buildRouterArgs(props: { filter: string, pagination: Pagination }, args?: Map<string, any>) {

        let { page, sortBy, descending, rowsPerPage } = props.pagination

        const cols = []

        for (const column of this.columns) {

            let name = this.getColumnName(column)


            if (name) {

                cols.push({
                    name: name,
                    filterable: column.filterable != null ? column.filterable : false
                })
            }
        }

        const columnSort = this.columns.find(c => c.name == sortBy)

        if (columnSort != undefined) {

            sortBy = this.getColumnName(columnSort)
        }

        const routerArgs: Record<string, any> = {}

        routerArgs.page = page
        routerArgs.filter = props.filter
        routerArgs.sortBy = sortBy
        routerArgs.descending = descending
        routerArgs.perPage = rowsPerPage
        routerArgs.columns = JSON.stringify(cols)

        this.args.forEach((value, key) => {

            routerArgs[key] = value
        })

        if (args) {

            args.forEach((value, key) => {

                routerArgs[key] = value
            })
        }

        return routerArgs
    }

    private getColumnName(column): string {
        return column.data ? column.data : (column?.field ?? null)
    }
}
