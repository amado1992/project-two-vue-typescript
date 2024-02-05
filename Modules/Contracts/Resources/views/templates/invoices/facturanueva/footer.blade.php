<style>
    .c-table {
        position: relative;
        float: left;
    }

    .item-position {
        position: absolute;
    }
    </style>

<table class="table content-table" style="margin-top: 10px;width:100%">
        <tbody>
        <tr>
            <td colspan="2" class="vertical-top">
                <div style="font-size: 16px;">Notas adicionales:</div>
                <div></div>
                <br>
                <br>
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

        <table style="width:100%;">
            <tr>
                <td>
                    <div style="padding-top: 2px !important;">
                        <img style="height: 20px;" src="{{ public_path('img/location.png') }}">

                    </div>
                    <div>
                        <p>{{ $company->name }}</p>
                        <p>{{ $company->address }}</p>
                    </div>
                </td>
                <td>
                    <div style="padding-bottom: 52px !important;">
                        <img style="height: 20px;" src="{{ public_path('img/email.png') }}">
                        {{ $company->email }}
                    </div>
                </td>

                <td>
                    <div style="padding-bottom: 72px !important;">
                        <img class="c-table" style="height: 20px;" src="{{ public_path('img/phone.png') }}">

                        @foreach (json_decode($company->contact_information,true) as $item)
                            <div class="c-table">{{$item['PHONE']}}/</div>
                        @endforeach
                    </div>


                </td>
            </tr>
        </table>

            <!--<div class="item-position">
                <img style="height: 20px;" src="{{ public_path('img/location.png') }}">
                {{ $company->name }}
            </div>

           <div class="item-position" style="margin-left: 10px;">
            <p style="">{{ $company->address }}</p>
           </div>

           <div class="c-table" style="margin-left: 10px;">
            <img style="height: 20px;" src="{{ public_path('img/email.png') }}">

            <span style="margin-bottom: 4px !important;">{{ $company->email }}</span>
           </div>

            <div class="c-table">
            <div style="margin-top: 5px;">
                <img style="height: 20px;" src="{{ public_path('img/phone.png') }}">
                Telefonos
            </div>

            @foreach (json_decode($company->contact_information,true) as $item)
               <p>{{$item['PHONE']}}</p>
            @endforeach
            </div>-->

