
    <table style="width: 100%" class="text-muted">
        <tbody>
        <tr>
            <td style="text-align: left" class="spaced-td">
                <img style="height: 76px;" src="{{ public_path('img/logo.png') }}">
                <div class="pl-30">{{ $company->name }}</div>
                <div class="pl-30">{{ $company->ruc }}</div>
                <div class="pl-30">{{ $company->address }}</div>
            </td>
            <td style="text-align: right" class="spaced-td">
                <div style="font-size: 36px;">{{ __('contracts::pdf.invoice', ['id' => $invoice->id]) }}</div>
                <div><strong>{{ __('contracts::pdf.client_text') }}:</strong> {{$invoice->contract->project->client->name}} </div>
                <div>{!!  __('contracts::pdf.date', ['date' => $invoice->created_at->format('Y-m-d')]) !!}</div>
                <div><strong>{{ __('contracts::pdf.invoice_type') }}:</strong></div>
                <div><strong>{{ __('contracts::pdf.payment_date') }}:</strong></div>
                <div>{!! __('contracts::pdf.seller', ['name' => $invoice->created_by()->first()->name]) !!}</div>
                <div>{!! __('contracts::pdf.address', ['address' => $invoice->contract->project->client->address]) !!}</div>
            </td>
        </tr>
        </tbody>
    </table>

    <div style="background-color: black; width: 100%; height: 2px; margin-top: 10px; margin-bottom: 3px"></div>
