<?php

namespace InetStudio\Experts\Http\Responses\Back\Experts;

use Illuminate\View\View;
use Illuminate\Contracts\Support\Responsable;
use InetStudio\Experts\Contracts\Http\Responses\Back\Experts\FormResponseContract;

/**
 * Class FormResponse.
 */
class FormResponse implements FormResponseContract, Responsable
{
    /**
     * @var array
     */
    private $data;

    /**
     * FormResponse constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Возвращаем ответ при открытии формы объекта.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return View
     */
    public function toResponse($request): View
    {
        return view('admin.module.experts::back.pages.form', $this->data);
    }
}
