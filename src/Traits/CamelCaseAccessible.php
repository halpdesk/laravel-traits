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

        if (!method_exists($this, $key) || !method_exists($this, $snakeCasedKey)) {
            if ($key == "id" || in_array(snake_case($key), array_keys(array_keys_to_snake_case($this->attributesToArray())))) {
                return parent::getAttribute(snake_case($key));
            } else {
                throw new AttributeNotFoundException("attribute_not_found", get_class($this), $key, []);
            }
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
