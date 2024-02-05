<style>
    table {
        border: 0px !important;

    }
</style>
<header>
    <table style="width: 100%">
        <tbody>
            <tr>
                <td style="text-align: left">
                    <img style="height: 76px;" src="{{ public_path('img/logo.png') }}">
                </td>
                <td style="text-align: right">
                    <div><strong>{{ $title }}</strong></div>
                    <div>METRO SUPPLIES, S.A.</div>
                    <div>Generado por: @auth
                            {{ Auth::user()->name }}
                        @endauth
                    </div>
                    <div>Generado fecha: {{ \Carbon\Carbon::now()->format('d/m/Y') }} </div>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="mb-4" style="background-color: black; width: 100%; height: 4px; margin-top: 3px;"></div>
    <br></br>
</header>
