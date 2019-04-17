<?php

namespace InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource;

use Illuminate\Http\Request;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract
{
    /**
     * @var PersonModelContract
     */
    protected $item;

    /**
     * SaveResponse constructor.
     *
     * @param  PersonModelContract  $item
     */
    public function __construct(PersonModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function toResponse($request)
    {
        return response()->redirectToRoute(
            'back.persons.edit', [
            $this->item->fresh()->id,
        ]
        );
    }
}
