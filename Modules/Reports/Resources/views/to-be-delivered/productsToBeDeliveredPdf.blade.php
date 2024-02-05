<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>REPORTE DE PRODUCTOS FALTANTES POR ENTREGAR</title>

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

        .margin-t{
            margin-top: 40px;

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
    </br>
    <table class="tw-shadow-none tw-mt-2 fixed-layout-table">
        <tr>
            <td class="text-left" width="10%">{{ __('reports::pdf.client') }}</td>
            @if ($data['all_clients'] == 1)
                <td class="text-left" width="90%"> {{ __('reports::pdf.all') }} </td>
            @else
                <td class="text-left" width="90%">
                    @foreach ($data['clients'] as $client)
                        <span> {{ $client }} <br /></span>
                    @endforeach
                </td>
            @endif
        </tr>
    </table>

    </br>

    @foreach ($data['all_contracts'] as $key => $contract)
        <table class="mt-3m margin-t ">
            <thead>
                <tr>
                    <th class="text-left" width="30%">{{ __('reports::pdf.contract_no') }}</th>
                    <th class="text-left" width="30%">{{ __('reports::pdf.client') }}</th>
                    <th class="text-right" width="20%">{{ __('reports::pdf.start') }}</th>
                    <th class="text-right" width="20%">{{ __('reports::pdf.end') }}</th>
                    <th class="text-right" width="20%">{{ __('reports::pdf.total') }}</th>
                    <th class="text-right" width="20%">{{ __('reports::pdf.status') }}</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-left" style="color:red">{{ $contract->id }}</td>
                    <td class="text-left" width="50%">{{ $contract->project->client->name }} </td>
                    <td class="text-right">{{ \Carbon\Carbon::parse($contract->active_at)->format('Y-m-d') }}</td>
                    <td class="text-right">{{ $contract->expire_at }}</td>
                    <td class="text-right">{{ money($contract->total) }}</td>
                    <td class="text-right">{{ __("reports::pdf.$contract->status") }}</td>
                </tr>
            </tbody>
        </table>
        <br />
        <div class="mt-3" style="font-size: 16px">{!! __('reports::pdf.address') !!}</div>
        <div class="col-2">
            {{ $contract->project->client->address }}
        </div>

        <br />
        <div class="mt-3 margin-t" style="font-size: 16px"><strong>{!! __('reports::pdf.products rented') !!}</strong></div>

        <table>
            <thead>
                <tr>
                    <td class="text-left" width="10%">#</td>
                    <td class="text-left" width="60%">{{ __('reports::pdf.product') }}</td>
                    <td class="text-right" width="10%">{{ __('reports::pdf.rented') }}</td>
                    <td class="text-right" width="10%">{{ __('reports::pdf.returned') }}</td>
                    <td class="text-right" width="12%">{{ __('reports::pdf.toreturn') }}</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($contract->products as $key => $product)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $product->name }}</td>
                        <td class="text-right">
                           {{ $product->pivot->quantity }}
                        </td>
                        <td class="text-right">{{ $product->pivot->mesu_return + $product->pivot->re_rent_return }}</td>
                        <td class="text-right">{{ $product->pivot->quantity - $product->pivot->mesu_return - $product->pivot->re_rent_return }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
        <hr>
    @endforeach



</body>

</html>
