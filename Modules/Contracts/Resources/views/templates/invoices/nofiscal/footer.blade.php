<div style="background-color: black; width: 100%; height: 2px; margin-top: 10px; margin-bottom: 3px"></div>


<table class="table content-table" style="margin-top: 10px;">
    <tbody>
    <tr>
        <td colspan="5" class="vertical-top">
            
        </td>
        <td class="text-right vertical-top">
            <div>Subtotal:</div>
            <div>Descuento:</div>
            <div>Impuesto:</div>
            <div style="font-size: 24px; margin-top: 10px;">Total:</div>
        </td>
        <td class="text-right vertical-top" style="width:200px"> 
            <div>{{money($invoice->subtotal)}}</div>
            <div>{{money($invoice->discount)}}</div>
            <div>{{money($invoice->taxes)}}</div>
            <div style="font-size: 24px; margin-top: 10px;">{{money($invoice->total)}}</div>
        </td>

    </tr>
    </tbody>
</table>


   
 <div class="vertical-top">
            <div style="font-size: 12px;">Notas:</div>
            <div></div>
        </div>
