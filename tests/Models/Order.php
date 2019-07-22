<?php

namespace Halpdesk\LaravelTraits\Tests\Models;

use Halpdesk\LaravelTraits\BaseModel;

class Order extends BaseModel
{
    protected $transformer = Order::class;
    protected $fillable = [
        "id",
        "company_id",
        "order_number",
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
