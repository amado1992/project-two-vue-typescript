<table>
    <thead>
        <tr>
            <th colspan="3" style="text-align:center; font-size:xx-large;font-weight: bold;background-color:darkgrey">
                INFORMACIÓN GENERAL DEL PRODUCTO
            </th>
        </tr>
        <tr>
            <th style="text-align:center; font-size:x-large;font-weight: bold">Nombre</th>
            <th style="text-align:center; font-size:x-large;font-weight: bold">Cantidad alquilada</th>
            <th style="text-align:center; font-size:x-large;font-weight: bold">Cantidad en almacén</th>

        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
          
                <tr>
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['quantity'] ?? 0 }}</td>
                    <td>{{ $product['stock'] ?? 0 }}</td>
                </tr>
         
        @endforeach

    </tbody>
</table>
<table>
