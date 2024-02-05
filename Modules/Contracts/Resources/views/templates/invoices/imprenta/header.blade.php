     <div style="text-align: center;">
        <strong>Factura no fiscal # {{$invoice->id}} </></strong>
    </div>
    <br>
    <div>
        <img style="height: 76px;" src="{{ public_path('img/logo.png') }}">
    </div>
    <br>
    <br>

    <table style="width:100%">
        <tbody>

            <tr>
                <td>
                    <div class="pl-30"> Facturado a: </div>
                    <!--<div class="pl-30"><h3>{{ $company->name }}</h3></div>
                    <div class="pl-30">Ruc: {{ $company->ruc }}</div>
                    <div class="pl-30">{{ $company->address }}</div>-->

                    <div class="pl-30">{{ $invoice->contract->project->client->name}}</div>
                    <div class="pl-30">Ruc: {{ $invoice->contract->project->client->ruc }}</div>
                    <div class="pl-30">Dirección: {{ $invoice->contract->project->client->address }}</div>
                    <div class="pl-30">Tel: {{ $invoice->contract->project->client->phone }}</div>
                    <div class="pl-30">Cel: {{ $invoice->contract->project->client->mobile }}</div>
                </td>
                <td>
                    <h3><div>{!!  __('contracts::pdf.date', ['date' => $invoice->created_at->format('Y-m-d')]) !!}</div></h3>
                    <div>{{ __('contracts::pdf.invoice_type') }}: crédito</div>
                    <div>Bodega: Bodega Principal</div>
                    <div>Vendedor: {{$invoice->contract->commercial->name}}</div>

                </td>
            </tr>
            <tr ><br>
                <span style="padding-left:20px">Balance:</span>
    <h3 style="padding-left:20px">{{money($invoice->total)}}</h3>
            </tr>
        </tbody>
    </table>

<div>

</div>
