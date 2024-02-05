<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>REPORTE DE GANANCIAS</title>

    <style>
        body {
            font-size: 12px;
        }

        body > *{
            margin-bottom: 3.5px;
        }

        table {
            width: 100%;
            table-layout: fixed;
            border-spacing: 0;
            font-size: 15px;

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

        .margin-fit {
            margin: 0 50px 0 50px;
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

        .margin-b{
            margin-bottom: 20px;
            height: 10px;
        }

        .margin-t{
            margin-top: 40px;
            height: 10px;
        }

        .margin-ta{
            margin-top: 40px;
            height: 25px;
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

        /*     td {
            overflow-wrap: break-word;
            border: solid 1px;
        }

        td.fitwidth {
            width: 1px;
            white-space: nowrap;
        }
 */
        @page {
            margin: 100px 70px;
        }
    </style>
</head>

<body>

    <div class="header">
        @include('reports::partials.new_header')
    </div>

    </br>

    <table class="tw-shadow-none tw-mt-2 fixed-layout-table">
        <tr>
            <td class="text-left tw-font-bold" width="50%" style="vertical-align:top">Clientes:</td>
            @if ($data['all_clients'] == 1)
                <td class="text-right" width="60%"> Todos </td>
            @else
            @foreach ($data['clients'] as $client)
                <div class="text-right margin-ta">

                        <div class="margin-b"> {{ ucfirst($client) }}</div>

                </div>
            @endforeach
            @endif
        </tr>
        <tr class="margin-t">
            <td class="text-left tw-font-bold" width="40%">Contratos activos a la fecha:</td>
            <td class="text-right">{{ $data['qty_active_contracts'] }}</td>
        </tr>
        <tr>
            <td class="text-left tw-font-bold" width="40%">Total descuentos de los contratos activos:</td>
            <td class="text-right">{{ $data['discount_total'] }}</td>
        </tr>
        <tr>
            <td class="text-left tw-font-bold" width="40%">Subtotal de los contratos activos:</td>
            <td class="text-right">{{ $data['subtotal'] }}</td>
        </tr>
        <tr>
            <td class="text-left tw-font-bold" width="40%">Total impuestos de los contratos activos:</td>
            <td class="text-right">{{ $data['total_taxes'] }}</td>
        </tr>
        <tr>
            <td class="text-left tw-font-bold" width="40%">Total:</td>
            <td class="text-right">{{ $data['active_contracts_total'] }}</td>
        </tr>

    </table>


</body>

</html>
