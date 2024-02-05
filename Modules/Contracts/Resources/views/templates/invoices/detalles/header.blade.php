
<table>
    <tbody>
        <tr>
            <td>
            <img style="height: 76px;" src="{{ public_path('img/logo.png') }}">

            </td>
            <td>
            <strong><h1>Detalle de venta</h1></strong>
            </td>
        </tr>
    </tbody>
</table>
        <br>

        <table style="width:100%;border: 1px solid black; padding:10px;">
            <tbody >

                <tr>
                    <td>
                        <div class="pl-30"> <strong><h1>Generales del cliente:</h1> </strong> </div>
                        <div><strong>Cliente: </strong>  <span style="margin-left: 10px !important;">{{$invoice->contract->project->client->name}}</span></div>
                        <div><strong>Tel: </strong>          <span style="margin-left: 34px !important;">{{$invoice->contract->project->client->phone}}</span></div>
                        <div><strong>País: </strong></div>
                        <div><strong>Dirección: </strong> <span style="margin-left: -2px !important;">{{$invoice->contract->project->client->address}}</span></div>

                         </td>
                    <td>
                        <div class="pl-30"> <strong><h1># Fiscal:</h1> </strong> </div>
                        <div><strong>Fecha: </strong> <span style="margin-left: 20px !important;">{{$invoice->created_at->format('Y-m-d')}}</span></div>
                        <!--<div>{!!  __('contracts::pdf.date', ['date' => $invoice->created_at->format('Y-m-d')]) !!}</div>-->
                        <div><strong>Termino: </strong></div>
                        <div><strong>Vendedor: </strong> <span>{{$invoice->created_by()->first()->name}}</span></div>
                        <!--<div>{!! __('contracts::pdf.seller', ['name' => $invoice->created_by()->first()->name]) !!}</div>-->
                        <div><strong>Página: </strong></div>

                    </td>
                </tr>

            </tbody>
        </table>

        <div>

        </div>
