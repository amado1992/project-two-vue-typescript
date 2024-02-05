<table class="table content-table" style="margin-top: 10px;width:100%">
        <tbody>
        <tr>
            <td colspan="2" class="vertical-top">
                <div style="font-size: 16px;">Comentario:</div>
                <div></div>
                <br>
                <br>
                <div style="text-align:right;">
                <div>Recibido conforme: ____________________</div>
                <div>Cédula: ____________________</div>
                </div>
            </td>
            <td class="text-right vertical-top">
                <div>Subtotal:</div>
                <div>Descuento:</div>
                <div>Impuestos (ITBMS):</div>
                <div style="font-size: 16px; margin-top: 10px;">Total:</div>
            </td>
            <td class="text-right vertical-top">
                <div>{{money($invoice->subtotal)}}</div>
                <div>{{money($invoice->discount)}}</div>
                <div>{{money($invoice->taxes)}}</div>
                <div style="font-size: 16px; margin-top: 10px;">{{money($invoice->total)}}</div>
            </td>

        </tr>
        </tbody>
    </table>

    <table   style="width:100%">
        <tbody>
            <tr>
                <td class="contact">
                <img style="height: 20px;" src="{{ public_path('img/location.png') }}">
               <div>
               {{ $company->name }}
                <p style="word-wrap: break-word;">{{ $company->address }}</p>   
               </div>
                                
                </td>
                <td >
                <img style="height: 20px;" src="{{ public_path('img/email.png') }}">
               
                {{ $company->email }}
                </td>
                <td >
                <img style="height: 20px;" src="{{ public_path('img/phone.png') }}">
               
                {{ $company->contact_information }}              
                </td>
            </tr>
        </tbody>
    </table>