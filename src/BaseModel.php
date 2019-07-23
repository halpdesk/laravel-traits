<?php

namespace Halpdesk\LaravelTraits;

use Illuminate\Database\Eloquent\Model;
use Halpdesk\LaravelTraits\Traits\CamelCaseAccessible;
use Halpdesk\LaravelTraits\Traits\Filterable;
use Halpdesk\LaravelTraits\Traits\Transformable;

abstract class BaseModel extends Model
{
    use CamelCaseAccessible,
        Filterable,
        Transformable;
}
