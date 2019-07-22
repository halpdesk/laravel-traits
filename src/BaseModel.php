<?php

namespace Halpdesk\LaravelTraits;

use Illuminate\Database\Eloquent\Model;
use Halpdesk\LaravelTraits\Traits\SnakeAttributes;
use Halpdesk\LaravelTraits\Traits\Filterable;
use Halpdesk\LaravelTraits\Traits\Transformable;

abstract class BaseModel extends Model
{
    use SnakeAttributes,
        Filterable,
        Transformable;
}
