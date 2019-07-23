<?php

namespace Halpdesk\LaravelTraits\Tests\Transformers;

use League\Fractal\TransformerAbstract;
use Halpdesk\LaravelTraits\Tests\Models\Company;

class CompanyTransformer extends TransformerAbstract
{

    public $availableIncludes = ["orders"];

    public function transform(Company $company)
    {
        return [
            "id"           => (int)$company->id,
            "companyName"  => $company->company_name,
            "email"        => $company->email,
            "registeredAt" => $company->registeredAt->format("Y-m-d"),
        ];
    }

    public function includeOrders(Company $company)
    {
        return $company->relationLoaded('orders')
            ? $this->collection($company->orders, new OrderTransformer)
            : null;
    }
}
