<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   {{--  <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>PRODUCTOS</title>

    <style>
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border: 1px solid;
            border-spacing: 0;

        }

        th {
            overflow: auto;
            overflow-wrap: break-word;
            border: solid 1px;
        }

        h3 {
            font-size: large;
            width: 100%;
        }

        .left-sign {
            min-width: 50%;
            float: left;
        }

        .right-sign {
            min-width: 50%;
            float: right;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .page-break {
            page-break-after: always;
        }

        .header {
            position: fixed;
            left: 0px;
            top: -80px;
            right: 0px;
            height: 100px;
            text-align: left;
            width: 100%;

        }

        .header .page:after {
            content: counter(page);
        }

        td {
            /*   text-align: center; */
            overflow-wrap: break-word;
            border: solid 1px;
        }

        td.fitwidth {
            width: 1px;
            white-space: nowrap;
        }

        @page {
            margin: 100px 70px;
        }
    </style>
</head>

<body>

    <div class="header">
        @include('reports::partials.header')
    </div>

    </br>
    <div class="text-center">
        <h4>INFORMACIÓN GENERAL DE LOS PRODUCTOS</h4>
    </div>
    <table>

        <thead>
            <tr>
                <th class="text-center" width="35%">Nombre</th>
                <th class="text-center">Cantidad alquilada</th>
                <th class="text-center">Cantidad en almacén</th>
            </tr>
        </thead>

        <tbody>

            @foreach ($products as $product)
                <tr>
               
                    <td width="35%">{{ $product['name'] }}</td>
                    <td class="text-center">{{ $product['quantity'] ?? 0 }}</td>
                    <td class="text-center">{{ $product['stock'] ?? 0 }}</td>

                </tr>
            @endforeach

        </tbody>

    </table>


    @foreach ($products as $product)
        @if ($product['quantity'] != 0) 
            <div class="page-break"> </div>

            </br>
            <div class="text-center">
                <h4> INFORMACIÓN DETALLADA DEL PRODUCTO {{ strtoupper($product['name']) }} </h4>
            </div>

            <table>

                <thead>
                    <tr>

                        <th class="text-center">Cliente</th>
                        <th class="text-center">Cantidad alquilada</th>

                    </tr>
                </thead>

                <tbody>
                    @foreach ($arrs($product['id'], $clients) as $arr)
                        <tr>

                            <td class="text-center">{{ $arr['name'] ?? null }}</td>
                            <td class="text-center">{{ $arr['quantity'] ?? 0 }}</td>

                        </tr>
                    @endforeach
                </tbody>

            </table>
        @endif
    @endforeach
</body>

</html>
