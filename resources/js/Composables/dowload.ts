import axios from "axios";

export const exportPdf = (url:string,data:any=null,filename:string="") => {
    axios.post(url,data,
    {
        responseType: 'blob'
    }).then((response) => {
        console.log(response);
        const url = URL.createObjectURL(new Blob([response.data], {
            type: 'application/pdf'
        }))
        const link = document.createElement('a')
        link.href = url
        let fileName = filename
        link.setAttribute('download', fileName)
        document.body.appendChild(link)
        link.click()
    }).catch((error) => {
        console.log(error)
    })
}

export const exportPdfGet = (url:string,filename:string="") => {
    axios.get(url,
        {
            responseType: 'blob'
        }).then((response) => {

        const url = URL.createObjectURL(new Blob([response.data], {
            type: 'application/pdf'
        }))
        const link = document.createElement('a')
        link.href = url
        let fileName = filename
        link.setAttribute('download', fileName)
        document.body.appendChild(link)
        link.click()
    }).catch((error) => {
        console.log(error)
    })
}

export const exportExcel = (fileName:string,url:string,data:any=null) => {
    axios.post(url,data, {
        responseType: 'blob'
    }).then((response) => {
        const url = URL.createObjectURL(new Blob([response.data], {
            type: 'application/vnd.ms-excel'
        }))
        const link = document.createElement('a')
        link.href = url
        link.setAttribute('download', fileName)
        document.body.appendChild(link)
        link.click()
    }).catch((error) => {
        console.log(error)
    })
}
