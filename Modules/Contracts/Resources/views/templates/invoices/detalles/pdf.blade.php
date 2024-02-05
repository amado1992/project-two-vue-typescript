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

        .factura{
        border: 1px solid black;
        border-top: none;
        text-align:center;
        border-collapse: collapse;
       }
       footer{
        border: 1px solid black;
        border-top: none;
        padding-bottom:20px;
       }


       .factura td{
        /*border-left: 3px solid black;*/
        text-align:center;
       }

       .factura thead td{
        font-weight: bolder;
        border-bottom: 1px solid black;
        border-right: 1px solid black;


       }

       .factura td{
        border-right: 1px solid black;
        padding-left:5px;
       }

       .tl{
        text-align:left!important;
       }

        .invoice td, tr {
            padding: 7px !important;
        }

    </style>
</head>
<body>

    <header>
    @include('contracts::templates.invoices.'.$template.'.header', ['invoice' => $invoice])


    </header>
    <main>



<table  class="factura invoice" style="width:100%;">
    <thead>

    <tr>
        <td >Cantidad</td>
        <td >Código</td>
        <td >Descripción</td>
    </tr>
    </thead>


     <tbody>

        @foreach($invoice->products as $product)
        <tr style="text-align: center; ">
            <td style="width: 20%">{{ $product->pivot->quantity  }}</td>
            <td style="width: 20%">{{ $product->id  }}</td>
            <td style="width: 60%"class="tl">{{ $product->name }}</td>
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
