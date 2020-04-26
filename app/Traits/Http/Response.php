<?php

namespace App\Traits\Http;

use URL;

use App\Http\Serializers\CustomSerializer;

use Illuminate\Pagination\LengthAwarePaginator;

use Illuminate\Database\Eloquent\Collection;

trait Response
{
    protected $transformer;

    protected function success($data)
    {
        $t = $this->transformer;
        $url = URL::to('/api');

        $includes = isset(request()->include) ? request()->include : [];

        $serializer = function ($resource, $fractal) use ($t, $url, $includes) {
            $fractal->parseIncludes($includes);
            $fractal->setSerializer(new CustomSerializer($url, $t));
        };

        if ($data instanceof LengthAwarePaginator) {
            return $this->response
                        ->paginator($data, $t, [], $serializer)
                        ->setStatusCode(200);
        } else {
            $function = 'item';

            if ($data instanceof Collection) {
                $function = 'collection';
            }

            return $this->response
                        ->{$function}($data, $t, $serializer)
                        ->setStatusCode(200);
        }
    }

    protected function error($data)
    {
        return response()->json([
            'error' => $data
        ], 400);
    }
}
