<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Client\Entities\Client;

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
        Schema::create('bonos', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->float('credit');
            $table->foreignIdFor(Client::class)
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
        Schema::dropIfExists('bonos');
    }
};
