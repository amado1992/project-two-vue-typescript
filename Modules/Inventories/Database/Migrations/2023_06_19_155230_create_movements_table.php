<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Inventories\Entities\Reason;

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
        Schema::create('movements', function (Blueprint $table) {
            $table->id();

            $table->date('date');
            $table->string('type');
            $table->longText('observations')->nullable();

            $table->foreignIdFor(Reason::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

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
        Schema::dropIfExists('movements');
    }
};
