<?php

namespace TheClinicDataStructures\DataStructures\Traits;

use TheClinicDataStructures\DataStructures\Interfaces\Arrayable;

/**
 * For excluding properties from attributes define a static method with name: 'getExcludedPropertiesNames' 
 * that returns an array of excluded properties NAME (string[])
 * 
 * public static function getExcludedPropertiesNames(): string[];
 */
trait IsArrayable
{
    public static function getExcludedPropertiesNames(): array
    {
        if (method_exists(static::class, 'getExcludedPropertiesNames')) {
            return self::getExcludedPropertiesNames();
        } else {
            return [];
        }
    }

    /**
     * @return array<string, string[]> ['attribute' => ['type', ...], ...]
     */
    public static function getAttributes(): array
    {
        $attributes = [];
        $reflectionClass = new \ReflectionClass(static::class);
        $properties = $reflectionClass->getProperties();

        $reflectionParentClass = $reflectionClass->getParentClass();

        if ($reflectionParentClass !== false) {
            $parentProperties = $reflectionParentClass->getProperties();
            $properties = array_merge($properties, $parentProperties);
        }

        /** @var \ReflectionProperty $property */
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (
                in_array($propertyName, self::getExcludedPropertiesNames()) ||
                $property->isStatic()
            ) {
                continue;
            }

            $propertyType = $property->getType();

            $types = [];
            if ($propertyType instanceof \ReflectionNamedType) {
                $attributes[$propertyName] = [$propertyType->getName()];
            } elseif ($propertyType instanceof \ReflectionUnionType) {
                /** @var \ReflectionNamedType $type */
                foreach ($propertyType->getTypes() as $type) {
                    $types[] = $type->getName();
                }

                $attributes[$propertyName] = $types;
            }
        }

        return $attributes;
    }

    public function toArray(): array
    {
        $array = [];
        foreach (self::getAttributes() as $attribute => $types) {
            if (in_array(gettype($this->{$attribute}), ['integer', 'string', 'float', 'bool', 'array', 'NULL'])) {
                $value = $this->{$attribute};
            } elseif ($this->{$attribute} instanceof \DateTime) {
                $value = $this->{$attribute}->format('Y-m-d H:i:s');
            } elseif ($this->{$attribute} instanceof Arrayable) {
                $value = $this->{$attribute}->toArray();
            } else {
                throw new \LogicException(
                    'Failed to find property: ' . $attribute .
                        ' with type of: ' .
                        (gettype($this->{$attribute}) === 'object' ? get_class($this->{$attribute}) : gettype($this->{$attribute})) .
                        ' for object of class: ' . get_class($this),
                    500
                );
            }

            $array[$attribute] = $value;
        }

        return $array;
    }
}
