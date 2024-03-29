<?php

namespace Halpdesk\LaravelTraits\Tests\Transformers;

use League\Fractal\TransformerAbstract;
use Halpdesk\LaravelTraits\Tests\Models\Order;

class OrderTransformer extends TransformerAbstract
{
    public $availableIncludes = ["company", "products"];

    public function transform(Order $order)
    {
        return [
            "id"          => (int)$order->id,
            "companyId"   => (int)$order->company_id,
            "orderNumber" => (int)$order->order_number,
        ];
    }

    public function includeCompany(Order $order)
    {
        return $order->relationLoaded('company')
            ? $this->item($order->company, new CompanyTransformer)
            : null;
    }

    public function includeProducts(Order $order)
    {
        return $order->relationLoaded('products')
            ? $this->collection($order->products, new ProductTransformer)
            : null;
    }
}
