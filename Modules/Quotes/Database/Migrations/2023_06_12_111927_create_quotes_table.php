<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Contracts\Entities\Contract;
use Modules\Projects\Entities\Project;
use Modules\Users\Entities\User;

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
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('period');
            $table->boolean('tax_exempt')->default(false);
            $table->string('observations')->nullable();
            $table->boolean('approved')->default(false);
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();
            $table->foreignIdFor(Project::class)->nullable()
                ->constrained()
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignIdFor(Contract::class)->nullable();

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
        Schema::dropIfExists('quotes');
    }
};
