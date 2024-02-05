<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->boolean('active')->default(1);

            $table->foreignId('product_category_id')
                ->constrained('product_categories')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->enum('type', ['service', 'product'])->default('product');

            $table->float('cost_price')->default(0);
            $table->float('daily_price')->default(0);
            $table->float('weekly_price')->default(0);
            $table->float('biweekly_price')->default(0);
            $table->float('monthly_price')->default(0);
            $table->float('replacement_price')->default(0);
            $table->float('tax')->default(0);

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();


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
        Schema::dropIfExists('products');
    }
};
