<?php

namespace Modules\Travels\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Modules\Products\Entities\Product;

class TravelProduct extends Pivot
{
    public function travel(): BelongsTo
    {
        return $this->belongsTo(Travel::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
