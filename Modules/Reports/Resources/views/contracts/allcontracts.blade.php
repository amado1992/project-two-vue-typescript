<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATOS</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="5" style="text-align:center; font-size:xx-large;font-weight: bold;bg-color:darkgrey">
                    INFORMACIÓN GENERAL DE LOS CONTRATOS
                </th>
            </tr>
        </thead>
        <thead>
            <tr>
                <th style="text-align:center; font-size:x-large;font-weight: bold">Cliente</th>
                <th style="text-align:center; font-size:x-large;font-weight: bold">Cantidad de contratos</th>
                <th style="text-align:center; font-size:x-large;font-weight: bold">Productos alquilados</th>
                <th style="text-align:center; font-size:x-large;font-weight: bold">Alquiler mensual</th>
              
            </tr>
        </thead>
        <tbody>

            @foreach ($clients as $client)
                @if ($client->projects->count() != 0)
                    <tr>
                        <td>
                            {{ $client->name }}
                        </td>

                        <td>
                            {{ $client->contracts_quantity }}
                        </td>

                        <td>
                            {{ $client->quantity }}
                        </td>

                        <td style="text-align: right">
                            {{ $client->total_month_price }}
                        </td>
                       
                    </tr>
                @endif
            @endforeach

        </tbody>
    </table>
</body>

</html>
