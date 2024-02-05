<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>LISTA DE DESPIECE</title>

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
<div class="header marginb">
    @include('reports::partials.new_header')
</div>

<div class="footer fixed-section">
    <div class="text-center">
        <span class="page-number">{{ __('reports::pdf.page') }} </span>
    </div>
</div>
<table style="width: 100%">
    <tbody>
    <tr>
        <td>
            <strong class="marginb" style="font-size: 16px">CLIENTE: {{ $client_name }} </strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong class="marginb" style="font-size: 16px"> PROYECTO: {{ $project_name }} </strong>
        </td>
    </tr>
    <tr>
        <td>
            <strong class="marginb" style="font-size: 16px">DIRECCIÓN: {{ $project_address }} </strong>
        </td>
    </tr>
    </tbody>
</table>

    <br />
    <div class="tw-mt-3 marginb" style="font-size: 16px"><strong>LISTADO DE PRODUCTOS</strong></div>

    <table class="marginb">
        <thead>
        <tr>
            <td class="text-left" width="40%">Nombre</td>
            <td class="text-right" width="10%">Cantidad</td>
        </tr>
        </thead>
        <tbody>
        @foreach ($products as $product)
            <tr>
                <td>{{ $product['name'] }}</td>
                <td class="text-right"> {{ $product['quantity'] }}</td>
            </tr>

            <div style="height: 10px"></div>
        @endforeach
        </tbody>
    </table>

</body>

</html>
