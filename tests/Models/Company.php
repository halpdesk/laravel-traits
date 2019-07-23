<?php

namespace Halpdesk\LaravelTraits\Tests\Models;

use Halpdesk\LaravelTraits\BaseModel;
use Halpdesk\LaravelTraits\Tests\Transformers\CompanyTransformer;

class Company extends BaseModel
{
    protected $transformer = CompanyTransformer::class;
    protected $table = "companies";
    protected $fillable = [
        "company_name",
        "email",
        "registered_at",
    ];

    protected $casts = [
        "registered_at" => "datetime:Y-m-d"
    ];

    protected $dates = [
        "registered_at"
    ];

    public $timestamps = false;

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
