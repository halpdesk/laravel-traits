<?php

namespace Halpdesk\LaravelTraits\Tests\Models;

use Halpdesk\LaravelTraits\BaseModel;
use Halpdesk\LaravelTraits\Tests\Transformers\ProductTransformer;

class Product extends BaseModel
{
    protected $transformer = ProductTransformer::class;
    protected $fillable = [
        "id",
        "name",
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }
}
