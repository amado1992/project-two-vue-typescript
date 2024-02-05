<?php

namespace Modules\Contracts\Application;

use Modules\Contracts\Entities\Contract;
use Modules\Contracts\Entities\Renovation;
use Modules\Products\Entities\Product;

/**
 * @author Abel David.
 */
class CreateRenovationUseCase
{
    /**
     * @param Contract $contract
     * @return Renovation
     */
    public function __invoke(Contract $contract): Renovation
    {
        $sync = [];

        $discount = 0;
        $tax = 0;
        $subtotal = 0;
        $total = 0;

        $contract->products->each(function (Product $product) use (&$sync, &$tax, &$subtotal, &$total, &$discount) {

            $mesu_delivered = $product->pivot->mesu_delivered;
            $mesu_return = $product->pivot->mesu_return;

            $re_rent_delivered = $product->pivot->re_rent_delivered;
            $re_rent_return = $product->pivot->re_rent_return;

            if ($mesu_delivered > $mesu_return || $re_rent_delivered > $re_rent_return) {

                $discount += $product->pivot->discount;
                $tax += $product->pivot->tax;
                $subtotal += $product->pivot->subtotal;
                $total += $product->pivot->total;

                $sync[$product->id] = [
                    'price' => $product->pivot->price,
                    'mesu_delivered' => $mesu_delivered - $mesu_return,
                    're_rent_delivered' => $re_rent_delivered - $re_rent_return
                ];
            }
        });

        $renovation = Renovation::create([
            'discount' => $discount,
            'tax' => $tax,
            'subtotal' => $subtotal,
            'total' => $total,
            'contract_id' => $contract->id,
            'user_id' => auth()->user()->getAuthIdentifier()
        ]);

        $renovation->products()->sync($sync);

        return $renovation;
    }
}
