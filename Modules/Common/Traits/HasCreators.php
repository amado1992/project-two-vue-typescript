<?php


namespace Modules\Common\Traits;


use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Schema\Blueprint;
use Modules\Users\Entities\User;

/**
 * Métodos de informes sobre la creación y actualización del modelo.
 *
 * Trait HasCreators
 * @package App\Traits
 */
trait HasCreators
{
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updated_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * @param Blueprint $table
     * @return void
     */
    protected function creators(Blueprint $table): void
    {
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
    }
}
