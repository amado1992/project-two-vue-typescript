
<table class="table content-table" style=" margin-top: 10px; float:right;">
    <tbody>
    <tr>
        <td colspan="2" class="vertical-top">

        </td>
        <td class="text-right vertical-top">
            <div>Subtotal:</div>
            <div>Descuento:</div>
            <div>Impuestos (ITBMS):</div>
            <div style="font-size: 18px; margin-top: 10px;">Total:</div>
        </td>
        <td class="text-right vertical-top" style="text-align:right; width:200px;">
            <div>{{money($invoice->subtotal)}}</div>
            <div>{{money($invoice->discount)}}</div>
            <div>{{money($invoice->taxes)}}</div>
            <div style="font-size: 18px; margin-top: 10px;">{{money($invoice->total)}}</div>
        </td>
    </tr>
    <tr>
        <td>
        </td>

        <td>
        </td>

        <td>
        </td>

        <td>
        <div class="vertical-top" style="margin-left: 22px !important;">
            Notas adicionales
            <h5>Ley 72 del 27 de septiembre del 2011</h5>
            <strong>No contribuyente del I.T.B.M.S</strong>
        </div>
        </td>
    </tr>
    </tbody>
</table>

<!--<div class="vertical-top">
    Notas adicionales
    <h5>Ley 72 del 27 de septiembre del 2011</h5>
    <strong>No contribuyente del I.T.B.M.S</strong>
</div>-->

