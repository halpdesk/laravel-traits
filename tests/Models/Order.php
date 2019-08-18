<?php

namespace Halpdesk\LaravelTraits\Tests\Models;

use Halpdesk\LaravelTraits\BaseModel;
use Halpdesk\LaravelTraits\Tests\Transformers\OrderTransformer;

class Order extends BaseModel
{
    protected $transformer = OrderTransformer::class;
    protected $fillable = [
        "id",
        "company_id",
        "order_number",
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }
}
