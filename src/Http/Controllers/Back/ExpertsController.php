<?php

namespace InetStudio\Experts\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Experts\Contracts\Http\Requests\Back\SaveExpertRequestContract;
use InetStudio\Experts\Contracts\Http\Controllers\Back\ExpertsControllerContract;
use InetStudio\Experts\Contracts\Http\Responses\Back\Experts\FormResponseContract;
use InetStudio\Experts\Contracts\Http\Responses\Back\Experts\SaveResponseContract;
use InetStudio\Experts\Contracts\Http\Responses\Back\Experts\IndexResponseContract;
use InetStudio\Experts\Contracts\Http\Responses\Back\Experts\DestroyResponseContract;

/**
 * Class ExpertsController.
 */
class ExpertsController extends Controller implements ExpertsControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    private $services;

    /**
     * ExpertsController constructor.
     */
    public function __construct()
    {
        $this->services['experts'] = app()->make('InetStudio\Experts\Contracts\Services\Back\ExpertsServiceContract');
        $this->services['dataTables'] = app()->make('InetStudio\Experts\Contracts\Services\Back\ExpertsDataTableServiceContract');
    }

    /**
     * Список объектов.
     *
     * @return IndexResponseContract
     */
    public function index(): IndexResponseContract
    {
        $table = $this->services['dataTables']->html();

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\IndexResponseContract', [
            'data' => compact('table'),
        ]);
    }

    /**
     * Добавление объекта.
     *
     * @return FormResponseContract
     */
    public function create(): FormResponseContract
    {
        $item = $this->services['experts']->getExpertObject();

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\FormResponseContract', [
            'data' => compact('item'),
        ]);
    }

    /**
     * Создание объекта.
     *
     * @param SaveExpertRequestContract $request
     *
     * @return SaveResponseContract
     */
    public function store(SaveExpertRequestContract $request): SaveResponseContract
    {
        return $this->save($request);
    }

    /**
     * Редактирование объекта.
     *
     * @param int $id
     *
     * @return FormResponseContract
     */
    public function edit($id = 0): FormResponseContract
    {
        $item = $this->services['experts']->getExpertObject($id);

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\FormResponseContract', [
            'data' => compact('item'),
        ]);
    }

    /**
     * Обновление объекта.
     *
     * @param SaveExpertRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    public function update(SaveExpertRequestContract $request, int $id = 0): SaveResponseContract
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение объекта.
     *
     * @param SaveExpertRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    private function save(SaveExpertRequestContract $request, int $id = 0): SaveResponseContract
    {
        $item = $this->services['experts']->save($request, $id);

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\SaveResponseContract', [
            'item' => $item,
        ]);
    }

    /**
     * Удаление объекта.
     *
     * @param int $id
     *
     * @return DestroyResponseContract
     */
    public function destroy(int $id = 0): DestroyResponseContract
    {
        $result = $this->services['experts']->destroy($id);

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Experts\DestroyResponseContract', [
            'result' => ($result === null) ? false : $result,
        ]);
    }
}
