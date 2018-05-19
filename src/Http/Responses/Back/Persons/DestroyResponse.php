<?php

namespace InetStudio\Persons\Http\Responses\Back\Persons;

use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Persons\Contracts\Http\Responses\Back\Persons\DestroyResponseContract;

/**
 * Class DestroyResponse.
 */
class DestroyResponse implements DestroyResponseContract, Responsable
{
    /**
     * @var bool
     */
    private $result;

    /**
     * DestroyResponse constructor.
     *
     * @param bool $result
     */
    public function __construct(bool $result)
    {
        $this->result = $result;
    }

    /**
     * Возвращаем ответ при удалении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'success' => $this->result,
        ]);
    }
}
