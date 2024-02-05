
<table style="width:100%;">
    <tbody>
        <tr>
            <td >
            <strong style="font-size:3em">Factura de venta</strong>
            <p>DOCUMENTO NO FISCAL</p>
            </td>
            <td style="text-align: right; display: grid; place-content: end;">           
            <div>
            <img style="height: 76px;" src="{{ public_path('img/logo.png') }}">
            
            </div>
            <span>{{ $company->name }}</span><br>
            <span>RUC:{{ $company->ruc }}</span>
            </td>
        </tr>
    </tbody>
</table>
        <br>
        
          
        <table style="width:100%; padding:10px;">
            <tbody >
               
                <tr>
                    <td>
                    <div class="pl-30">Facturado a:</div>                       
                        <div class="pl-30">{{$invoice->contract->project->client->name}}</div>
                        <div class="pl-30">Ruc:{{$invoice->contract->project->client->phone}}</div>
                        <div class="pl-30">{{$invoice->contract->project->client->address}}</div>
                        <div class="pl-30">Tel.:{{$invoice->contract->project->client->phone}}</div>
                        <div class="pl-30">Cel.:{{$invoice->contract->project->client->mobile}}</div>
                            
                    </td>
                    <td>
                    <div class="pl-30">Factura:</div>
                    <div class="pl-30" style="color:red;font-size:15px">No. <strong>{{$invoice->id}}</strong> </div>
                        <div class="pl-30">{!!  __('contracts::pdf.date', ['date' => $invoice->created_at->format('Y-m-d')]) !!}</div>
                        <div class="pl-30">Tipo:</div>
                        <div class="pl-30">Bodega:</div>
                        <div class="pl-30">{!! __('contracts::pdf.seller', ['name' => $invoice->created_by()->first()->name]) !!}</div>
                        
                       
                    </td>
                    <td>
                        
                        <div class="pl-30" style="text-align:right">Balance:
                        <div class="pl-30"><strong>{{money($invoice->total)}}</strong></div>
                        </div>
                       
                    </td>
                </tr>   
                
            </tbody>   
        </table>
        
        <div>
        
        </div>