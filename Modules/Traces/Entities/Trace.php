<?php

namespace Modules\Traces\Entities;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Modules\Bonos\Entities\Bono;
use Modules\Brands\Entities\Brand;
use Modules\Client\Entities\Client;
use Modules\Common\Entities\WithSerializeDate;
use Modules\Contracts\Entities\Contract;
use Modules\Designs\Entities\Design;
use Modules\Inventories\Entities\Movement;
use Modules\Inventories\Entities\Reason;
use Modules\Payments\Entities\Payment;
use Modules\ProductCategories\Entities\ProductCategory;
use Modules\Products\Entities\Product;
use Modules\Projects\Entities\Project;
use Modules\Providers\Entities\Provider;
use Modules\Quotes\Entities\Quote;
use Modules\ReRents\Entities\ReRent;
use Modules\Roles\Entities\Role;
use Modules\Users\Entities\User;

/**
 * @author Abel David.
 *
 * @property int $id
 * @property string $model
 * @property mixed|null $model_id
 * @property string $module
 * @property string $action
 * @property array $old_fields
 * @property array $fields
 * @property User $user
 * @method static self create(array $data)
 */
class Trace extends Model
{
    use HasFactory, WithSerializeDate;

    const CREATED_ACTION = 'created';
    const UPDATED_ACTION = 'updated';
    const DELETED_ACTION = 'deleted';
    const CANCELLED_ACTION = 'cancelled';
    const STARTED_ACTION = 'started';
    const FINISHED_ACTION = 'finished';
    const APPROVED_ACTION = 'approved';

    /**
     * @var array|string[]
     */
    const MODELS_MAP = [
        'brands' => Brand::class,
        'clients' => Client::class,
        'contracts' => Contract::class,
        'productCategories' => ProductCategory::class,
        'providers' => Provider::class,
        'products' => Product::class,
        'projects' => Project::class,
        'quotes' => Quote::class,
        'reRents' => ReRent::class,
        'roles' => Role::class,
        'users' => User::class,
        'movements' => Movement::class,
        'reasons' => Reason::class,
        'traces' => Trace::class,
        'bonos' => Bono::class,
        'payments' => Payment::class,
        'designs' => Design::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'model',
        'model_id',
        'module',
        'action',
        'old_fields',
        'fields',
        'user_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'old_fields' => 'array',
        'fields' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'readable_action',
        'merge_fields'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'user'
    ];

    /**
     * Ignore fields in fields and old_fields attributes.
     *
     * @var array
     */
    protected array $ignore = [
        'created_at',
        'created_by',
        'updated_by',
        'updated_at'
    ];

    protected  array $relationsIds = [
        'client_id',
        'user_id',
        'project_id',
        'quote_id',
        'design_id',
        'contract_id',
        'role_id'
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return string
     */
    protected function getReadableActionAttribute(): string
    {
        return __('traces::actions.'.$this->action);
    }

    protected function oldFields(): Attribute
    {
        return Attribute::get(function ($value) {
            return $this->formatFields($value);
        });
    }

    /**
     * @return Attribute
     */
    protected function fields(): Attribute
    {
        return Attribute::get(function ($value) {
            return $this->formatFields($value);
        });
    }

    /**
     * @return array
     */
    protected function getMergeFieldsAttribute(): array
    {
        $fields = $this->fields;
        $old_fields = $this->old_fields;

        $array = [];

        if ($fields) {
            foreach ($fields as $key => $value) {

                if (!isset($array[$key])) {
                    $array[$key] = [];
                }

                $array[$key]['value'] = $value;
            }
        }

        if ($old_fields) {
            foreach ($old_fields as $key => $value) {

                if (!isset($array[$key])) {
                    $array[$key] = [];
                }

                $array[$key]['old_value'] = $value;
            }
        }

        return $array;
    }

    private function formatFields($value): ?array
    {

        if ($value) {
            $value = json_decode($value, true);

            $fields = [];

            foreach ($value as $key => $field) {

                if (in_array($key, $this->ignore)) {
                    continue;
                }

                if(in_array($key, $this->relationsIds)){
                    $stringValue= $this->inferStringValueOfRelation(
                        $this->attributes['model'],
                        $this->attributes['model_id'],
                        $key,
                        $field
                    );
                    $fields[ucfirst(__('validation.attributes.' . $key))] = $stringValue;

                    //esto es para agregar a la fuerza el campo cliente en el contrato
                    if($key == 'project_id' && Str::contains($this->attributes['model'],'Contracts',true)){
                        $project = Project::query()->where('name','=',$stringValue)->first();
                        if($project != null){
                            $fields[ucfirst(__('validation.attributes.client_id'))] = $project->client?->name ?? "";
                        }
                    }
                    continue;
                }

                $fields[ucfirst(__('validation.attributes.' . $key))] = $this->formatField($field);
            }

            return $fields;
        }

        return null;
    }

    private function formatField(mixed $field)
    {
        if (is_bool($field)) {

            return $field ? __('Yes') : __('No');
        }

        return $field;
    }

    private function inferStringValueOfRelation($model,$model_id,$field,$value)
    {
        $inferdModelValue = $model::where('id','=',$model_id)->first();

        if($inferdModelValue == null){
            return $value;
        }

        return inferStringRepresentationForModel($field,$value);
    }

    private function arrayToString(array $array): string
    {
        $result = '';

        foreach ($array as $key => $value) {

            if (is_int($key)) {

                if (is_array($value)) {

                    $result .= $this->arrayToString($value).' ';
                } else {

                    $result .= $value.' ';
                }
            } else if (is_string($key)) {

                if (is_array($value)) {

                    $result .= __('validation.attributes.'.$key).': '.$this->arrayToString($value).' ';
                } else {

                    $result .= __('validation.attributes.'.$key).': '.$value.' ';
                }
            }
        }

        return $result;
    }

    protected static function newFactory()
    {
        return \Modules\Traces\Database\factories\TraceFactory::new();
    }
}
