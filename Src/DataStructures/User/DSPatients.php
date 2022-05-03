<?php

namespace TheClinicDataStructures\DataStructures\User;

use TheClinicDataStructures\DataStructures\Interfaces\Arrayable;
use TheClinicDataStructures\DataStructures\Traits\TraitKeyPositioner;
use TheClinicDataStructures\Exceptions\DataStructures\NoKeyFoundException;

class DSPatients implements \Countable, \ArrayAccess, \Iterator, \Stringable, Arrayable
{
    use TraitKeyPositioner;

    /**
     * @var DSPatient[]
     */
    private array $dsPatients = [];

    private int $position;

    // ------------- Arrayable ----------------------------------------------------

    public function toArray(): array
    {
        return $this->dsPatients;
    }

    // ------------- \Stringable ----------------------------------------------------

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }

    // ------------- \Countable ----------------------------------------------------

    public function count(): int
    {
        return count($this->dsPatients);
    }

    // ------------- \Iterator ----------------------------------------------------

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return $this->validate($this->position);
    }

    public function validate(int $offset): bool
    {
        return isset($this->dsPatients[$offset]);
    }

    public function next(): void
    {
        if ($this->count() === 0) {
            $this->position++;
        }

        try {
            $this->position = $this->findNextPosition(function ($offset) {
                return $this->validate($offset);
            }, $this->position, array_key_last($this->current()));
        } catch (NoKeyFoundException $th) {
            $this->position++;
        }
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function current(): mixed
    {
        return $this->dsPatients[$this->position];
    }

    // ------------- \ArrayAccess ----------------------------------------------------

    public function offsetExists(mixed $offset): bool
    {
        $this->validateOffset($offset);
        return isset($this->dsPatients[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        $this->validateOffset($offset);
        return $this->dsPatients[$offset];
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->validateOffset($offset, true);
        $this->validateValue($value);

        if (is_null($offset)) {
            $this->dsPatients[] = $value;
        } else {
            $this->dsPatients[$offset] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->validateOffset($offset);
        unset($this->dsPatients[$offset]);
    }

    private function validateOffset(mixed $offset, bool|null $isNullAllowed = false): void
    {
        if (($isNullAllowed && is_null($offset)) || is_integer($offset)) {
            throw new \TypeError("Offset type error. Acceptable type" . ($isNullAllowed ? 's are: null and integer' : ' is: integer.'), 1);
        }
    }

    private function validateValue(mixed $value): void
    {
        if (!($value instanceof DSPatient)) {
            throw new \TypeError("Offset type error. Acceptable type is: " . DSPatient::class, 1);
        }
    }
}
