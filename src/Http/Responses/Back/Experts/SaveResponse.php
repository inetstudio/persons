<?php

namespace InetStudio\Experts\Http\Responses\Back\Experts;

use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Experts\Contracts\Models\ExpertModelContract;
use InetStudio\Experts\Contracts\Http\Responses\Back\Experts\SaveResponseContract;

/**
 * Class SaveResponse.
 */
class SaveResponse implements SaveResponseContract, Responsable
{
    /**
     * @var ExpertModelContract
     */
    private $item;

    /**
     * SaveResponse constructor.
     *
     * @param ExpertModelContract $item
     */
    public function __construct(ExpertModelContract $item)
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
        return response()->redirectToRoute('back.experts.edit', [
            $this->item->fresh()->id,
        ]);
    }
}
