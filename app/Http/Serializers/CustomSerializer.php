<?php

namespace App\Http\Serializers;

use League\Fractal\Serializer\JsonApiSerializer;
use League\Fractal\Pagination\PaginatorInterface;

use App\Transformers\BaseTransformer;

class CustomSerializer extends JsonApiSerializer
{
    private $transformer;

    public function __construct($url, BaseTransformer $transformer)
    {
        parent::__construct($url);

        $this->transfomer = $transformer;
    }

    private function addJsonApiVersion($result)
    {
        return array_merge([
            'jsonapi' => [
                'version' => '1.0',
            ],
        ], $result);
    }

    public function collection($resourceKey, array $data)
    {
        $resourceKey = $resourceKey ?? $this->transfomer->getType();

        $result = parent::collection($resourceKey, $data);

        return $this->addJsonApiVersion($result);
    }

    public function item($resourceKey, $data)
    {
        $resourceKey = $resourceKey ?? $this->transfomer->getType();

        $result = parent::item($resourceKey, $data);

        return $this->addJsonApiVersion($result);
    }

    public function paginator(PaginatorInterface $paginator)
    {
        $result = parent::paginator($paginator);

        $perPage = $result['pagination']['per_page'];

        foreach ($result['pagination']['links'] as $target => $value) {
            $parts = explode('?', $value);
            $url = $this->baseUrl . '/' . $parts[0];
            $pageIndex = str_replace('page=', '', $parts[1]);
            $url .= '?page[size]=%d&page[index]=%d';

            $pageUrl = sprintf($url, $perPage, $pageIndex);

            $result['pagination']['links'][$target] = $pageUrl;
        }

        return $result;
    }
}
