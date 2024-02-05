<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
        body {
            font-size: 12px;
        }

        .table {
            width: 100%;
            border-bottom-width: 1px;
            border-bottom-color: gray;
            border-bottom-style: solid;
            border-spacing: 10px;
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

        .text-left {
            text-align: left;
        }

        .saltopagina{page-break-after:always;}
    </style>
</head>
<body>

@include('contracts::templates.contract_header_pdf', ['contract' => $contract])

<main>
    <div>{!! __('contracts::pdf.date', ['date' => $contract->date->format('Y-m-d')]) !!}</div>
    <div>{!! __('contracts::pdf.client', ['name' => $contract->project->client->name]) !!}</div>
    <div>{!! __('contracts::pdf.legal_representative', ['name' => $contract->legal_representative]) !!}</div>
    <div>{!! __('contracts::pdf.legal_representative_id', ['value' => $contract->legal_representative_id]) !!}</div>
    <div>{!! __('contracts::pdf.client_address', ['address' => $contract->project->client->address]) !!}</div>
    <div>{!! __('contracts::pdf.project_manager', ['name' => $contract->project->project_manager]) !!}</div>
    <div>{!! __('contracts::pdf.project_manager_phone', ['value' => $contract->project->project_manager_phone]) !!}</div>
    <div>{!! __('contracts::pdf.project_address', ['address' => $contract->project->address]) !!}</div>
    <div>
        {!! __('contracts::pdf.period', ['period' => __('contracts::pdf.period_value', ['value' => $contract->period])]) !!}
    </div>

    <strong style="margin-top: 20px; display: block">{{ __('contracts::pdf.products_details') }}</strong>

    <table class="table" style="margin-top: 10px;">
        <thead style="border-bottom-width: 1px; border-bottom-color: gray; border-bottom-style: solid;">
        <tr style="text-align: center">
            <th class="text-right">{{ __('contracts::pdf.description') }}</th>
            <th class="text-right">{{ __('contracts::pdf.price') }}</th>
            <th class="text-right">{{ __('contracts::pdf.quantity') }}</th>
            <th class="text-right">{{ __('contracts::pdf.discount') }}</th>
            @if(! $contract->tax_exempt)
                <th class="text-right">{{ __('contracts::pdf.tax') }}</th>
            @endif
            <th class="text-right">{{ __('contracts::pdf.replacement_price') }}</th>
            <th class="text-right">{{ __('contracts::pdf.total') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($contract->products as $product)
            <tr style="text-align: center;">
                <td class="text-left" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis">
                    {{ $product->name }}
                </td>
                <td class="text-right">{{ money($product->pivot->price)  }}</td>
                <td class="text-right">{{ $product->pivot->quantity }}</td>

                <td class="text-right">{{ money($product->pivot->discount) }}</td>
                @if(! $contract->tax_exempt)
                    <td class="text-right">{{ money($product->tax) }}</td>
                @endif
                <td class="text-right">{{ money($product->pivot->replacement_price) }}</td>
                <td class="text-right">{{ money($product->pivot->total) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <table style="width: 100%; margin-top: 10px;">
        <tbody>
        <tr style="text-align: left">
            <td style="text-align: right">
                <div class="px-3" style="display: inline-block; font-weight: bold;">
                    <div>{!! __('contracts::pdf.discount_2') !!}</div>
                    <div>{!! __('contracts::pdf.subtotal_2') !!}</div>
                    <div>{!! __('contracts::pdf.tax_2') !!}</div>
                    <div>{!! __('contracts::pdf.total_2') !!}</div>
                </div>
                <div style="display: inline-block; margin-left: 10px">
                    <div>{{ money($contract->discount) }}</div>
                    <div>{{ money($contract->subtotal) }}</div>
                    <div>{{ money($contract->tax) }}</div>
                    <div>{{ money($contract->total) }}</div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <div style="margin-top: 200px" class="saltopagina">
        @include('contracts::templates.contract_footer_pdf', ['contract' => $contract])
    </div>

    <strong style="display: block; margin-left: auto; margin-right: auto; width: 100%; text-align: center; margin-top: 80px">
        {{ __('contracts::pdf.contracts_clauses') }}
    </strong>

    <div style="margin-top: 20px">{!! $clausules !!}</div>

    <div style="margin-top: 20px">
        @include('contracts::templates.contract_footer_pdf', ['contract' => $contract])
    </div>
</main>
</body>
</html>
