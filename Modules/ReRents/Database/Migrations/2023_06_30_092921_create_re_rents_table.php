<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Providers\Entities\Provider;

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
        Schema::create('re_rents', function (Blueprint $table) {
            $table->id();
            $table->date('start');
            $table->date('finish');
            $table->boolean('tax_exempt')->default(false);
            $table->dateTime('finished_at')->nullable();
            $table->foreignId('finished_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->dateTime('cancelled_at')->nullable();
            $table->foreignId('cancelled_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete()
                ->cascadeOnUpdate();
            $table->string('cancelled_reason')->nullable();
            $table->text('observations')->nullable();
            $table->foreignIdFor(Provider::class)
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
        Schema::dropIfExists('re_rents');
    }
};
