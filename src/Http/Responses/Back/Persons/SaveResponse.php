<?php

namespace InetStudio\Persons\Http\Responses\Back\Persons;

use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Http\Responses\Back\Persons\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract, Responsable
{
    /**
     * @var PersonModelContract
     */
    private $item;

    /**
     * SaveResponse constructor.
     *
     * @param PersonModelContract $item
     */
    public function __construct(PersonModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при сохранении объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return RedirectResponse
     */
    public function toResponse($request): RedirectResponse
    {
        return response()->redirectToRoute('back.persons.edit', [
            $this->item->fresh()->id,
        ]);
    }
}
