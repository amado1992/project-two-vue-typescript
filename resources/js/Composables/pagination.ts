import {Paginator} from "../Models/Paginator";
import {ref} from "vue";

/**
 * @author Abel David.
 * @param data
 * @param columns
 */
export function usePagination(data, columns) {

    const paginationService = new Paginator(data, columns)

    const pagination = ref(paginationService)

    const filter = ref(paginationService.filter)

    const onRequest = (props) => {
        paginationService.onRequest(props)
    }

    return {
        pagination,
        filter,
        onRequest
    }
}
