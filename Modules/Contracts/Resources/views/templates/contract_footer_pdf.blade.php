<footer style="width: 100%; margin-top: 12px;">
    <span class="left-sign">
        <div style="bottom: 0; width: 50%; min-height: 15px; border-bottom-color: gray; border-bottom-width: 3px; border-bottom-style: solid;"></div>
        <div style="width: 50%; text-align: left;">
            <strong style="display: block">{{ __('contracts::pdf.lessor') }}</strong>
            <strong style="text-transform: uppercase; display: block">{{ config('app.name') }}</strong>
            <strong style="display: block">RENÉ GONZALEZ</strong>
            <strong style="display: block">CÉDULA: 8-395-553</strong>
        </div>
    </span>

    <span class="right-sign">
        <div style="text-align: center; float: right; bottom: 0; width: 50%; min-height: 15px; border-bottom-color: gray; border-bottom-width: 3px; border-bottom-style: solid;"></div>
        <div style="width: 50%; text-align: left; float: right; margin-top: 20px;">
            <strong style="display: block">{{ __('contracts::pdf.tenant') }}</strong>
            <strong style="text-transform: uppercase; display: block;">{{ $contract->project->client->name }}</strong>
            <strong style="text-transform: uppercase; display: block;">{{ $contract->project->client->legal_representative }}</strong>
            <strong style="text-transform: uppercase; display: block;">
                {{ __('contracts::pdf.cedula', ['value' => $contract->project->client->cedula]) }}
            </strong>
        </div>
    </span>

    <div style="margin-top: 90px; text-align: left">
        <div>{{ __('contracts::pdf.footer_note') }}</div>
        <strong>{{ __('contracts::pdf.footer_note_2') }}</strong>
    </div>
</footer>
