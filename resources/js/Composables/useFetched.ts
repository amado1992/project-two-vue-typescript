import axios from "axios";
import {ref} from "vue";

export function useList(url:string) {

    const data = ref(null)
    const loading = ref(false)
    const errors = ref(null)

    loading.value = true;

    const callEndpoint = (params:any={})=>{
        axios
        .get(url,params)
        .then((response) => {
            data.value = response.data
            loading.value = false;
        })
        .catch(function (error) {
            errors.value = error
            loading.value = false;
            console.log(error);
        });
    }

    return {
        data,
        errors,
        callEndpoint
    }
}
