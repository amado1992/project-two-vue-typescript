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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('address');
            $table->string('project_manager');
            $table->string('project_manager_phone');
            $table->string('construction_manager');
            $table->string('construction_manager_phone');
            $table->string('in_charge_to_pay');
            $table->string('in_charge_to_pay_phone');
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
        Schema::dropIfExists('projects');
    }
};
