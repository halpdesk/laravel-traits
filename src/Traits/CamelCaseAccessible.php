<?php

namespace Halpdesk\LaravelTraits\Traits;

use Halpdesk\LaravelTraits\Exceptions\AttributeNotFoundException;

/**
 * @author Daniel Leppänen
 */
trait CamelCaseAccessible
{
    public function getAttribute($key)
    {
        $snakeCasedKey = snake_case($key);
        $attributes = $this->getAllAttributes();

        if (!method_exists($this, $key)) {
            if ($key == "id" || in_array(snake_case($key), $attributes)) {
                return parent::getAttribute(snake_case($key));
            } else {
                throw new AttributeNotFoundException("attribute_not_found", get_class($this), $key, []);
            }
        } else if (method_exists($this, $key)) {
            return parent::getAttribute($key);
        } else {
            return parent::getAttribute($snakeCasedKey);
        }
    }

    public function setAttribute($key, $value)
    {
        return parent::setAttribute(snake_case($key), $value);
    }

    public function fill(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            if ($value === null) {
                unset($attributes[$key]);
            }
        }
        return parent::fill(array_keys_to_snake_case($attributes));
    }

    protected function getAllAttributes()
    {
        $fillable = $this->getFillable();
        $dates = array_merge($this->getDates(), ["deleted_at", "created_at", "updated_at"]);
        $casts = $this->getCasts();

        return array_unique(array_keys_to_snake_case(array_merge(
            $fillable,
            $dates,
            array_keys($casts),
            array_keys($this->attributesToArray()),
        )));
    }
}
