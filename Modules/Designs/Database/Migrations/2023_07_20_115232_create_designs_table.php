<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Quotes\Entities\Quote;

return new class extends Migration
{
    use \Modules\Common\Traits\HasCreators;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Quote::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $this->creators($table);

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
        Schema::dropIfExists('designs');
    }
};
