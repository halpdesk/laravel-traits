<?php

namespace Halpdesk\LaravelTraits\Transformers;

use League\Fractal\TransformerAbstract;

class ArrayTransformer extends TransformerAbstract
{
    public function transform(Array $array)
    {
        return $array;
    }
}
