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
            if (in_array(snake_case($key), $attributes)) {
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

    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();
        return $attributes + array_keys_to_camel_case($attributes);
    }

    protected function getAllDates()
    {
        $datesAsValues = $this->getDates();
        foreach ($datesAsValues as $key => $date) {
            if (is_string($key)) {
                unset($datesAsValues[$key]);
                $datesAsValues[] = $key;
            }
        }
        return array_merge($datesAsValues, $this->timestamps ? ["deleted_at", "created_at", "updated_at"] : []);
    }

    protected function getAllAttributes()
    {
        $fillable = $this->getFillable();
        $casts = $this->getCasts();
        $dates = $this->getAllDates();

        return array_unique(array_keys_to_snake_case(array_merge(
            ["id"],
            $fillable,
            $dates,
            array_keys($casts),
            array_keys(parent::attributesToArray()),
        )));
    }
}
