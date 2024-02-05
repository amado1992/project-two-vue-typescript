<table>

    <th colspan="7" style="text-align:center; font-size:xx-large;font-weight: bold;bg-color:darkgrey">
        INFORMACIÓN DETALLADA DE LOS CONTRATOS DEL CLIENTE {{ strtoupper($client->name) }}
    </th>

</table>
<table>
    <thead>
        <tr>

            <th rowspan="2" style="text-align:center; font-size:x-large;font-weight: bold">Contratos (ID) </th>
            <th rowspan="2" style="text-align:center; font-size:x-large;font-weight: bold">Proyecto </th>
            <th rowspan="2" style="text-align:center; font-size:x-large;font-weight: bold">Alquiler mensual por
                proyecto
            </th>
            <th rowspan="2" style="text-align:center; font-size:x-large;font-weight: bold">Alquiler total</th>
            <th colspan="3" style="text-align:center; font-size:x-large;font-weight: bold">Productos</th>

        </tr>
        <tr>

            <th style="text-align:center; font-size:x-large;font-weight: bold">Nombre</th>
            <th style="text-align:center; font-size:x-large;font-weight: bold">Alquilados</th>
            <th style="text-align:center; font-size:x-large;font-weight: bold">Alquiler mensual por producto</th>

        </tr>

    </thead>

    {{-- todo:  verificar, solo deben llegar los contratos correspondientes a un proyecto filtrado --}}
    <tbody>
        @foreach ($contracts as $contract)
            <tr>

                <td rowspan="{{ $contract->products->count() }}" style="vertical-align:center;text-align:center">

                    {{ $contract->id }}</td>

                <td rowspan="{{ $contract->products->count() }}" style="vertical-align:center;text-align:center">

                    {{ $contract->project->name }}</td>

                <td rowspan="{{ $contract->products->count() }}" style="vertical-align:center;text-align:right">

                    {{ money(getMonthlyRented($contract, $products)) }} </td>

                <td rowspan="{{ $contract->products->count() }}" style="vertical-align:center;text-align:right">

                    {{ money(getMonthlyTotalRented($contracts, $products)) }}</td>

                <td colspan="3">
                    @foreach ($contract->products as $product)
                        @if (in_array($product->id, $products))
                            <table>
                              {{--   @if ($loop->first)
                                    <tr>

                                    </tr>
                                @endif --}}

                                <tr>
                                    <td style="text-align: center">
                                        {{ $product->name }}
                                    </td>

                                    <td>
                                        {{ $product->pivot->quantity }}
                                    </td>
                                    <td style="text-align:right;"> {{ money($product->pivot->subtotal) }} </td>
                                </tr>

                            </table>
                        @endif
                    @endforeach
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
