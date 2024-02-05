<?php

namespace Modules\Settings\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Schema;
use JsonSerializable;
use ReflectionClass;
use ReflectionProperty;

/**
 * @author Abel David.
 */
abstract class Settings implements Arrayable, Jsonable, JsonSerializable
{
    public function __construct()
    {
        if(Schema::hasTable('settings')){
            $properties = $this->getProperties();

            foreach ($properties as $property) {

                $name = $this->group().'.'.$property->getName();

                if ($property->isPublic()) {

                    $value = setting($name);

                    if (! $value) {

                        $value = $this->getDefaultValue($property);

                    } else if ($this->propertyIsArray($property)) {

                        $value = json_decode($value, true);
                    }

                    $property->setValue($this, $value);
                }
            }
        }

    }

    /**
     * @return void
     */
    function save(): void
    {
        $properties = $this->getProperties();

        foreach ($properties as $property) {

            if ($property->isPublic()) {

                $name = $this->group() . '.' . $property->getName();

                $value = $property->getValue($this);

                if (is_array($value)) {

                    $value = json_encode($value);
                }

                setting([$name => $value]);
            }
        }

        setting()->save();
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $data = [];

        foreach ($this->getProperties() as $property) {

            $data[$property->getName()] = $property->getValue($this);
        }

        return $data;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toJson();
    }

    /**
     * @return string
     */
    protected abstract function group(): string;

    /**
     * @return array<ReflectionProperty>
     */
    private function getProperties(): array
    {
        $reflector = new ReflectionClass($this);

        return $reflector->getProperties();
    }

    /**
     * @param ReflectionProperty $property
     * @return mixed
     */
    private function getDefaultValue(ReflectionProperty $property): mixed
    {
        $type = $property->getType();

        if ($type->allowsNull()) {

            return null;
        }

        if ($type instanceof \ReflectionNamedType) {

            switch ($type->getName()) {
                case 'bool':
                case 'boolean':
                    return false;
                case 'int':
                case 'float':
                    return 0;
                case 'array':
                    return [];
            }
        }

        return '';
    }

    /**
     * @param ReflectionProperty $property
     * @return bool
     */
    private function propertyIsArray(ReflectionProperty $property): bool
    {
        $type = $property->getType();

        if ($type instanceof \ReflectionNamedType) {

            return $type->getName() == 'array';
        }

        return false;
    }
}
