<?php

namespace App\Http\Controllers\Api;

use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;
use Jiannei\Response\Laravel\Support\Traits\JsonResponseTrait;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use JsonResponseTrait;

    protected function responseCreatedSuccess($data) {
        return $this->success($data, '', Response::HTTP_CREATED);
    }

    protected function responsePagination(LengthAwarePaginator $data) {
        return $this->success([
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'current_page' => $data->currentPage(),
                'per_page' => $data->perPage(),
                'total_page' => $data->lastPage()
            ]
        ]);
    }

    protected function responseErrorUnauthorized() {
        return $this->errorUnauthorized(trans('auth.failed'));
    }
}
