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


          .factura td{
        border-bottom: 2px solid #d1d4d9;
        text-align:center;
        padding: 5px 0px;

       }

       .contact{
        display: flex;
       }

       .content-table{
        border-bottom:2px solid #d1d4d9;
        padding-bottom:15px;
       }
       .tl{
        text-align:left!important;
       }

       .right-sign {
            min-width: 50%;
            float: right;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <header>
    @include('contracts::templates.invoices.'.$template.'.header', ['invoice' => $invoice])


    </header>
    <main>



<table  class="factura" style="width:100%;">
    <thead>

    <tr style="background-color:#d1d4d9">
        <td class="tl" >Descripción</td>
        <td ><strong>Unidades</strong></td>
        <td ><strong>Precio</strong></td>
        <td ><strong>Total</strong></td>
    </tr>
    </thead>


     <tbody>

        @foreach($invoice->products as $product)
        <tr style="text-align: center;">
            <td class="tl" style="width: 60%">{{ $product->name }}</td>
            <td >{{ $product->pivot->quantity  }}</td>
            <td class="text-right"> {{ money($product->pivot->price) }}</td>
            <td class="tr text-right">{{ money($product->pivot->total) }}</td>
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
