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

        .content-table {
            width: 100%;
            border-bottom-width: 2px;
            border-bottom-color: gray;
            border-bottom-style: solid;
        }

        .content-table td{
            padding: 1.0em;
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

        .text-muted{
            color: #868b8c;
        }

        .spaced-td *{
            padding: 2px;
        }

        .pl-30 {
            padding-left: 30px !important;
        }

        .vertical-top {
            vertical-align: top;
        }
    </style>
</head>
<body>

    <header>
        @include('contracts::templates.invoices.'.$template.'.header', ['invoice' => $invoice])
    </header>
    <main>

        <table class="table content-table" style="margin-top: 10px;">
            <thead style="border-bottom-width: 1px; border-bottom-color: gray; border-bottom-style: solid;">
                <tr style="text-align: center; ">
                    <th class="text-left">{{ __('contracts::pdf.reference') }}</th>
                    <th class="text-left">{{ __('contracts::pdf.quantity') }}</th>
                    <th class="text-left">{{ __('contracts::pdf.description') }}</th>
                    <th class="">{{ __('contracts::pdf.price') }}</th>
                    <th class="text-right"><strong>{{ __('contracts::pdf.total') }}</strong></th>
                </tr>
            </thead>
            <tbody>
            @foreach($invoice->products as $product)
                <tr style="margin: 10px 0;">
                    <td class="text-left">#{{ $product->id  }}</td>
                    <td class="text-left">{{ $product->pivot->quantity  }}</td>
                    <td class="text-left" style="width: 50%">{{ $product->name }}</td>
                    <td class="text-right">{{ money($product->pivot->price) }}</td>
                    <td class="text-right"><strong>{{ money($product->pivot->total) }}</strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>



    </main>
    <footer>
        @include('contracts::templates.invoices.'.$template.'.footer', ['invoice' => $invoice])
    </footer>




</body>
</html>
