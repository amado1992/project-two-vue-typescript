
    <table class="table content-table" style="margin-top: 10px;">
        <tbody>
        <tr>
            <td class="text-right vertical-top">
                <div>Comentario:</div>
            </td>
            <td class="text-right vertical-top">
                <div>Subtotal:</div>
                <div>Descuento:</div>
                <div>Impuesto:</div>
                <div style="font-size: 24px; margin-top: 10px;">Total:</div>
            </td>
            <td class="text-right vertical-top">
                <div>{{money($invoice->subtotal)}}</div>
                <div>{{money($invoice->discount)}}</div>
                <div>{{money($invoice->taxes)}}</div>
                <div style="font-size: 24px; margin-top: 10px;">{{money($invoice->total)}}</div>
            </td>

        </tr>
        </tbody>
    </table>
