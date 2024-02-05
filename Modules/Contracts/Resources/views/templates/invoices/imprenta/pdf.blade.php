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
        border-bottom: 3px solid gray;
        text-align:center;
       }

       .factura > th > tr > td{
        font-weight: bolder;
       }
       .tl{
        text-align:left!important;
       }
       .tr{
        text-align:right!important;
       }

    </style>
</head>
<body>

    <header>
        @include('contracts::templates.invoices.'.$template.'.header', ['invoice' => $invoice])
    </header>
    <main>



<table  class="factura" style="width:100%;">
    <th>
       <tr style="font-weight: bolder;">
       <td class="tl">Descripción</td>
       <td>Unidades</td>
       <td>Precio</td>
       <td class="tr">Total</td>
       </tr>
    </th>
     <tbody>
        @foreach($invoice->products as $product)
        <tr style="text-align: center; ">

            <td class="tl" style="width: 60%">{{ $product->name }}</td>
            <td >{{ $product->pivot->quantity  }}</td>
            <td class="tr">{{ money($product->pivot->price) }}</td>
            <td class="tr"><strong>{{ money($product->pivot->total) }}</strong></td>
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
