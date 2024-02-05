<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">



    <style>
        body {
            font-size: 14px;
            margin: 5px;
        }

        @page {
            margin: 120px 100px;
           /*  margin-top: 100px */
            margin-left: 35px;
            margin-right: 35px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            font-size: 16px;
        }

        th {
            overflow: auto;
            overflow-wrap: break-word;
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

        .header {
            position: fixed;
            left: 0px;
            top: -110px;
            right: 0px;
            height: 100px;
            text-align: left;
            width: 100%;

        }

        .footer {
            position: fixed;
            bottom: -60px;
            left: 0px;
            right: 0px;
            height: 50px;

            /** personal styles **/
            text-align: center;
            line-height: 35px;
        }

        .footer .page-number:after {
            content: counter(page);
        }

        .marginb{
            margin-bottom: 12px;
        }
    </style>
</head>

<body>

    <strong class="marginb">PROVEEDOR {{ $provider->name }} </strong>
    <table class="marginb">
        <thead>
            <tr>
                <td class="text-left" width="70%">Nombre</td>
                <td class="text-left" width="10%">Realquilados</td>
                <td class="text-left" width="10%">Alquilados</td>
                <td class="text-left" width="10%">Disponibles</td>
                <td class="text-left" width="10%">Costo</td>
            </tr>
        </thead>
        <tbody>

                <tr>
                    <td>{{ $provider->name }}</td>
                    <td> {{ $provider->rented + $provider->disponible }}</td>
                    <td> {{ $provider->rented }}</td>
                    <td>
                       {{ $provider->disponible }}
                    </td>
                    <td> {{ $provider->cost }}</td>
                </tr>


        </tbody>
    </table>

        <br />
        <div class="tw-mt-3 marginb" style="font-size: 16px"><strong>PRODUCTOS POR PROVEEDOR</strong></div>

        <table class="marginb">
            <thead>
                <tr>
                    <td class="text-left" width="70%">Nombre</td>
                    <td class="text-left" width="10%">Realquilados</td>
                    <td class="text-left" width="10%">Alquilados</td>
                    <td class="text-left" width="10%">Disponibles</td>
                    <td class="text-left" width="10%">Costo</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td> {{ $product->rented + $product->disponible }}</td>
                        <td> {{ $product->rented }}</td>
                        <td>
                           {{ $product->disponible }}
                        </td>


                        <td> {{ $product->cost }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>

</body>

</html>
