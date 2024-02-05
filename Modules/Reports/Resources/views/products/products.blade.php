
<table>
    <tr>  
        
        <th colspan="2" style="text-align:center; font-size:xx-large;font-weight: bold;bg-color:darkgrey; vertical-align: inherit; white-space:normal;">
            INFORMACIÓN DETALLADA DEL PRODUCTO {{ strtoupper($product['name']) }}
        </th>
    </tr>
    <thead><strong>
            <tr>
                <th style="text-align:center; font-size:x-large;font-weight: bold">Cliente</th>
                <th style="text-align:center; font-size:x-large;font-weight: bold">Cantidad alquilada</th>

            </tr>
        </strong></thead>
    <tbody>
        @foreach ($arrs as $arr)
            <tr>
                <td>{{ $arr['name'] ?? null }}</td>
                <td>{{ $arr['quantity'] ?? 0 }}</td>
            </tr>
        @endforeach
    </tbody>

</table>
