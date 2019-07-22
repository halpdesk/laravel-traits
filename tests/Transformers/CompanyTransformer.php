<?php

namespace Halpdesk\LaravelTraits\Transformers;

use League\Fractal\TransformerAbstract;
use Halpdesk\LaravelTraits\Tests\Models\Company;

class CompanyTransformer extends TransformerAbstract
{

    public $availableIncludes = [];

    public function transform(Company $company)
    {
        return [
            "id"            => (int)$company->id,
            "company_name"  => $company->companyName,
            "email"         => $company->email,
            "registered_at" => $company->registeredAt->format("Y-m-d"),
        ];
    }
}
