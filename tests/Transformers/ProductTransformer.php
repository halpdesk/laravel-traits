<?php

namespace Halpdesk\LaravelTraits\Tests\Transformers;

use League\Fractal\TransformerAbstract;
use Halpdesk\LaravelTraits\Tests\Models\Product;

class ProductTransformer extends TransformerAbstract
{

    public $availableIncludes = ["orders"];

    public function transform(Product $product)
    {
        return [
            "id"   => (int)$product->id,
            "name" => $product->name,
        ];
    }

    public function includeOrders(Product $product)
    {
        return $product->relationLoaded('orders')
            ? $this->collection($product->orders, new OrderTransformer)
            : null;
    }
}
