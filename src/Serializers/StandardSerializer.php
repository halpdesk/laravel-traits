<?php

namespace Halpdesk\LaravelTraits\Serializers;

use League\Fractal\Serializer\DataArraySerializer;

class StandardSerializer extends DataArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        return ($resourceKey && $resourceKey !== 'data') ? array($resourceKey => $data) : $data;
    }

    public function item($resourceKey, array $data)
    {
        return ($resourceKey && $resourceKey !== 'data') ? array($resourceKey => $data) : $data;
    }
}
