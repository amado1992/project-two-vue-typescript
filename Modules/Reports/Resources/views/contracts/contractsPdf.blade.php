<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATOS</title>

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
            top: -100px;
            right: 0px;
            height: 100px;
            text-align: left;
            width: 100%;
            margin-bottom: 40px;
        }

        .header .page:after {
            content: counter(page);
        }

        td {
            text-align: center;
        }

        table,
        td {
            border: 1px solid;
        }

        .cell-breakWord {
            word-wrap: break-word;
        }

        @page {
            margin: 100px 90px;
            margin-top: 120px;
        }
    </style>
</head>

<body>

    <div class="header">
        @include('reports::partials.header')
    </div>

    </br>

    <div class="text-center">
        <h4>INFORMACIÓN GENERAL DE LOS CONTRATOS</h4>
    </div>
    <table>

        <thead>
            <tr>
                <th class="text-center">Cliente</th>
                <th class="text-center">Cantidad de contratos</th>
                <th class="text-center">Productos alquilados</th>
                <th class="text-center">Alquiler mensual</th>
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

                        <td class="text-right">
                            {{ $client->total_month_price }}
                        </td>

                    </tr>
                @endif
            @endforeach

        </tbody>
    </table>
    {{-- todo: pasar proyectos getContractsProjectsUseCase --}}
    @foreach ($clients as $client)
        @if ($client->projects->count() != 0)
            <div class="page-break">
            </div>
            </br>
            <div class="text-center">
                <h4>INFORMACIÓN DETALLADA DE LOS CONTRATOS DEL CLIENTE: {{ strtoupper($client->name) }} </h4>
            </div>
            </br>
            <table>

                <thead>
                    <tr>
                        <th rowspan="2" style="text-align:center">Contratos (ID)</th>
                        <th rowspan="2" style="text-align:center">Proyecto </th>
                        <th rowspan="2" style="text-align:center">Alquiler mensual por proyecto</th>
                        <th rowspan="2" style="text-align:center">Alquiler total</th>
                        <th colspan="3" style="text-align:center">Productos</th>
                    </tr>

                    <tr>
                        <th style="text-align:center">Nombre</th>
                        <th style="text-align:center">Alquilados</th>
                        <th style="text-align:center">Alquiler mensual por producto</th>
                    </tr>

                </thead>

                @foreach ($client->projects as $project)
                    @foreach ($project->contracts as $contract)
                        @if (in_array($contract->project->id, $projects) && $contract->status == "active")
                            <tr>

                                <td style="text-align:center">{{ $contract->id }}</td>
                                <td class="cell-breakWord" style="text-align:center;">{{ $contract->project->name }}
                                </td>
                                <td style="text-align:center"> {{ getMonthlyRented($contract, $products) }} </td>
                                <td> {{ getMonthlyTotalRented($project->contracts, $products) }} </td>

                                <td colspan="3">

                                    @foreach ($contract->products as $product)
                                        @if (in_array($product->id, $products))
                                            <table style="border: hidden !important">

                                                <tr>
                                                    <td class="text-left" style="border: solid 1px !important"> {{ $product->name }}
                                                    </td>

                                                    <td style="border: solid 1px !important">
                                                        {{ $product->pivot->quantity }}
                                                    </td>

                                                    <td class="text-right" style="border: solid 1px !important">
                                                        {{ money($product->pivot->subtotal) }}
                                                    </td>

                                                </tr>
                                            </table>
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                    @endforeach
                @endforeach
            </table>
        @endif
    @endforeach
</body>

</html>
