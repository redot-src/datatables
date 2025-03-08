<?php

namespace Redot\Datatables\Traits;

trait Serializable
{
    /**
     * Serialize the object.
     */
    public function __serialize(): array
    {
        return $this->toArray();
    }

    /**
     * Unserialize the object.
     */
    public function __unserialize(array $data): void
    {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * Convert the object to its array representation.
     */
    public function toArray(): array
    {
        return get_object_vars($this);
    }

    /**
     * Convert the object to its JSON representation.
     */
    public function toJson(int $options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the object to its string representation.
     */
    public function __toString(): string
    {
        return $this->toJson();
    }
}
