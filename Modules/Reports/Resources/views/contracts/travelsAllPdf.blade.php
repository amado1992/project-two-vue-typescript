<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>Retiro de productos</title>

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

        .footer-div div {
            display: inline;
        }

        .line-horizontal div span:after {
            content: "";
            display: inline-block;
            height: 0.5em;
            width: 20%;
            margin-left: 5px;
            border-bottom: 1px solid black;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
<div class="header marginb">
    @include('reports::partials.new_header')
</div>

<div class="footer fixed-section">
    <div class="text-center">
        <div class="footer-div line-horizontal">
            <div><span>Recibido por:</span></div>
            <div><span style="margin-left: 20px;">Entregado por:</span></div>
            <div><span style="margin-left: 20px;">Fecha:</span></div>
        </div>
        <span class="page-number">{{ __('reports::pdf.page') }} </span>
    </div>
</div>

@php
use  Carbon\Carbon;
@endphp

@foreach ($travels as $key => $travel)

    <table style="width: 100%">
        <tbody>
        <tr>
            <td>
                <strong class="marginb" style="font-size: 16px">CONTRATO: {{ $contract_id }} </strong>
            </td>
        </tr>

        <tr>
            <td>
                <strong class="marginb" style="font-size: 16px">VIAJE: {{$travel->id}}</strong>
            </td>
        </tr>

        <tr>
            <td>
                <strong class="marginb" style="font-size: 16px">FECHA DE VIAJE: {{(new Carbon($travel->travel_date))->format('d/m/y')}}</strong>
            </td>
        </tr>

        <tr>
            <td>
                <strong class="marginb" style="font-size: 16px">CLIENTE: {{ $client_name }} </strong>
            </td>
        </tr>

        <tr>
            <td>
                <strong class="marginb" style="font-size: 16px"> PROYECTO: {{ $project->name ?? "" }} </strong>
            </td>
        </tr>

        <tr>
            <td>
                <strong class="marginb" style="font-size: 16px">DIRECCIÓN: {{ $project->address ?? "" }} </strong>
            </td>
        </tr>
        </tbody>
    </table>

    <br />

    <div class="marginb" style="font-size: 16px"><strong>LISTADO DE PRODUCTOS</strong></div>

    <table class="marginb">
        <thead>
        <tr>
            <td class="text-left" style="width: 50%;">Nombre</td>
            <td class="text-right" style="width: 25%;">Retirado cliente</td>
        </tr>
        </thead>
        <tbody>

        @foreach ($travel->products as $product)

            @if($product->pivot->carried_by_client > 0)
            <tr>
                <td>{{ $product->name ?? "" }}</td>
                <td class="text-right">{{ $product->pivot->carried_by_client }}</td>
            </tr>
            @endif

            <div style="height: 10px"></div>
        @endforeach

        </tbody>
    </table>
    @if($key < count($travels) - 1)
    <div class="page-break"></div>
    @endif
@endforeach
</body>
</html>
