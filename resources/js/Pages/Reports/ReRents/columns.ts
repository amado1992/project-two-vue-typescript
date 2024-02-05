
export const columnsReport = [
    {
      name: 'name',
      required: true,
      label: 'Proveedor',
      align: 'left',
      field: row => row.name,
      format: val => `${val}`,
      sortable: true
    },
    {
        name: 'rerents',
        required: true,
        label: 'Realquilados',
        align: 'left',
        field: row => Number.parseFloat(row.disponible) + Number.parseFloat(row.rented),
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'rented',
        required: true,
        label: 'Alquilados',
        align: 'left',
        field: row => row.rented,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'disponible',
        required: true,
        label: 'Disponibles',
        align: 'left',
        field: row => row.disponible,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'cost',
        required: false,
        label: 'Costo',
        align: 'left',
        field: row => row.cost,
        format: val => '$ '+`${Number.parseFloat(val).toFixed(2)}`,
        sortable: true
    },
    {
        name: 'actions',
        label: ''
    }
]

export const columnsReportProducts = [
    {
      name: 'name',
      required: true,
      label: 'Productos',
      align: 'left',
      field: row => row.name,
      format: val => `${val}`,
      sortable: true
    },
    {
        name: 'rerents',
        required: true,
        label: 'Realquilados',
        align: 'left',
        field: row => Number.parseFloat(row.disponible) + Number.parseFloat(row.rented),
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'rented',
        required: true,
        label: 'Alquilados',
        align: 'left',
        field: row => row.rented,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'disponible',
        required: true,
        label: 'Disponibles',
        align: 'left',
        field: row => row.disponible,
        format: val => `${val}`,
        sortable: true
    },
    {
        name: 'cost',
        required: false,
        label: 'Costo',
        align: 'left',
        field: row => row.cost,
        format: val => '$ '+`${Number.parseFloat(val).toFixed(2)}`,
        sortable: true
    },
    {
        name: 'actions',
        label: ''
    }
]
