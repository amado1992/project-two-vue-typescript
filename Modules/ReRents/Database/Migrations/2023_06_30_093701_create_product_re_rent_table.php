<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Products\Entities\Product;
use Modules\ReRents\Entities\ReRent;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_re_rent', function (Blueprint $table) {
            $table->id();
            $table->float('price');
            $table->integer('quantity');
            $table->integer('returned')->default(0);
            $table->float('discount');
            $table->float('tax');
            $table->float('subtotal');
            $table->float('total');
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->foreignIdFor(ReRent::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_re_rent');
    }
};
