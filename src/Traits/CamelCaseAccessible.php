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
        $attributesAndDates = array_merge(["created_at", "updated_at", "deleted_at"], $this->getDates(), array_keys(array_keys_to_snake_case($this->attributesToArray())));

        if (!method_exists($this, $key)) {
            if ($key == "id" || in_array(snake_case($key), $attributesAndDates)) {
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
}
