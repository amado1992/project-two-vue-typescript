<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ $css }}">

    <style>
        body {
            font-size: 12px;
        }

        .left-sign {
            min-width: 50%;
            float: left;
        }

        .right-sign {
            min-width: 50%;
            float: right;
        }

        .table {
            width: 100%;
            border-bottom-width: 1px;
            border-bottom-color: gray;
            border-bottom-style: solid;
            border-spacing: 10px;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

@include('quotes::templates.quote_header_pdf', ['quote' => $quote])

<main>
    <table style="width: 100%">
        <tbody>
        <tr>
            <td style="text-align: left">
            @if(isset($quote->project))
            <div>{{ __('quotes::pdf.client', ['name' => $quote->project->client?->name]) }}</div>
            @else
            <div>{{ __('quotes::pdf.client', ['name' => $quote->client?->name]) }}</div>
            @endif

              <div>{!! __('quotes::pdf.date', ['date' => $quote->date]) !!}</div>
            </td>
            <td style="text-align: right">
                <div>{!! __('quotes::pdf.seller', ['name' => $quote->commercial?->name]) !!}</div>
                <div>{!! __('quotes::pdf.period', ['period' => $quote->period]) !!}</div>
            </td>
        </tr>
        </tbody>
    </table>

    <div style="margin-top: 5px">{{ __('quotes::pdf.products_details') }}</div>

    <table class="table" style="width: 100%; margin-top: 3px">
        <thead style="border-bottom-width: 1px; border-bottom-color: gray; border-bottom-style: solid;">
        <tr style="text-align: center">
            <th class="text-right">#</th>
            <th class="text-right">{{ __('quotes::pdf.product') }}</th>
            <th class="text-right">{{ __('quotes::pdf.price') }}</th>
            <th class="text-right">{{ __('quotes::pdf.quantity') }}</th>
            <th class="text-right">{{ __('quotes::pdf.subtotal') }}</th>
            <th class="text-right">{{ __('quotes::pdf.tax') }}</th>
            <th class="text-right">{{ __('quotes::pdf.total') }}</th>
        </tr>
        </thead>
        <tbody>
        @foreach($quote->products as $product)
            <!--<tr style="text-align: center">-->
            <tr>
                <td class="text-right">{{ $product->id }}</td>
                <td class="text-left" style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; margin-left: 16px !important;  padding-left: 16px !important;">
                    {{ $product->name }}
                </td>
                <td class="text-right">
                    {{ money($product->pivot->price).' ('.percentage($product->pivot->percent_discount).')' }}
                      </td>
                <td class="text-right">{{ $product->pivot->quantity }}</td>
                <td class="text-right">{{ money($product->pivot->subtotal) }}</td>
                <td class="text-right">{{ money($product->pivot->tax) }}</td>
                <td class="text-right">{{ money($product->pivot->total) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <br>

    <table style="width: 100%; margin-top: 5px;">
        <tbody>
        <tr style="text-align: left">
            <td>{{ $quote->observations }}</td>
            <td style="text-align: right">
                <div class="px-3" style="display: inline-block; font-weight: bold;">
                    <div>{{ __('quotes::pdf.subtotal_2') }}</div>
                    <div>{{ __('quotes::pdf.tax_2') }}</div>
                    <div style="color: green">{{ __('quotes::pdf.total_2') }}</div>
                </div>
                <div style="display: inline-block; margin-left: 10px">
                    <div>{{ money($quote->subtotal) }}</div>
                    <div>{{ money($quote->tax) }}</div>
                    <div style="color: green">{{ money($quote->total) }}</div>
                </div>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="mt-3">{{ __('quotes::pdf.commercials_conditions') }}</div>
    <div style="width: 50%">
        <p>{!! __('quotes::pdf.commercials_conditions_content') !!}</p>
    </div>

    <div style="width: 100%; margin-top: 24px;">
        <span class="left-sign">
            <div style="bottom: 0; width: 50%; min-height: 15px; border-bottom-color: gray; border-bottom-width: 3px; border-bottom-style: solid;"></div>
            <div style="width: 50%; text-align: center;">
                {{ __('quotes::pdf.client_2') }}
            </div>
        </span>

        <span class="right-sign">
            <div style="text-align: center; float: right; bottom: 0; width: 50%; min-height: 15px; border-bottom-color: gray; border-bottom-width: 3px; border-bottom-style: solid;">
                {{ $quote->commercial->name }}
            </div>
            <div style="width: 50%; text-align: center; float: right; margin-top: 20px;">
                {{ __('quotes::pdf.make_by') }}
            </div>
        </span>
    </div>
</main>
</body>
</html>
