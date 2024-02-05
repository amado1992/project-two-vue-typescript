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
            font-family: "Roboto", "-apple-system", "Helvetica Neue", Helvetica, Arial, sans-serif;
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

        .border{
            border: 1px solid black;
        }
        table{
              border-collapse: separate;
              border-spacing: 5px 0px;
              width: 100%;
        }

        table.margint{
            border-collapse: separate;
              border-spacing: 5px 5px;
        }
        .inter{
            width: 160px;
        }

        .anchor{
            height: 50px;
            width: 50px;
        }

        .anchordiv{
            height: 20px;
            width: 20px;
        }

        .margin-two{
            margin-top: 40px;
        }



    </style>
</head>
<body>

    <header>
        @include('contracts::templates.invoices.'.$template.'.header', ['invoice' => $invoice])
    </header>
    <main>

        <table style="text-align:center;">
            <tbody>
                <tr style="width: auto;">
                    <td class="border">
                        <div>{!!  __('contracts::pdf.date', ['date' => $invoice->created_at->format('Y-m-d')]) !!}</div>

                    </td>

                    <td class="border">

                        <p><strong>Fiscal: </strong> INV1234</p>
                        <p><strong>COO: </strong> 00000</p>

                    </td>
                    <td class="border">

                        <p><strong>Termino: </strong> CREDIT</p>

                </td>
                    <td class="border">
                        <p><strong>Bodega: </strong> Bodega principal</p>
                    </td>
                </tr>
                    </td>
                        </tbody>
        </table>
        <table class="margint">
            <tbody>
                <tr class="d-flex">
                    <td class="border" style="width: 30px;height: 30px;">
                        <p style="padding: 10px;"><strong>Vendido a: </strong><br> {{$invoice->contract->project->client->name}} </p>
                    </td>
                    <td class="border" style="width: 30px;height: 30px;">
                        <p style="padding: 10px;"><strong>Dirección: </strong><br> {{$invoice->contract->project->client->address}}</p>
                    </td>
                </tr>
                <tr class="d-flex">
                    <td class="border" style="width: 30px;height: 30px;">
                        <p style="padding: 10px;"><strong>Vendedor: </strong>{{$invoice->contract->commercial->name}}</p>
                    </td>
                    <td class="border" style="width: 30px;height: 30px;">
                        <p style="padding: 10px;"><strong>Contacto: </strong>Bodega principal</p>
                    </td>
                </tr>
            </tbody>
        </table>
            <br>
            <br>
    <table style="text-align: center;">
        <th>
            <tr style="font-weight: bolder;">
                <td>Código</td>
                <td>Descripción</td>
                <td>Cantidad</td>
                <td>Precio</td>
                <td>Total</td>
            </tr>
        </th>
        <tbody>
            @foreach($invoice->products as $product)
            <tr>
                <td >#{{ $product->id  }}</td>
                <td style="width:60%;text-align: left">{{ $product->name }}</td>
                <td >{{ $product->pivot->quantity  }}</td>
                <td class="text-right">{{ money($product->pivot->price) }}</td>
                <td class="text-right"><strong>{{ money($product->pivot->total) }}</strong></td>
            </tr>
            <div class="anchordiv"></div>
        @endforeach
        </tbody>
    </table>

    </main>


    <footer>
        @include('contracts::templates.invoices.'.$template.'.footer', ['invoice' => $invoice])
    </footer>




</body>
</html>
