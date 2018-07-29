<?php

namespace InetStudio\Persons\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Persons\Contracts\Http\Requests\Back\SavePersonRequestContract;
use InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsControllerContract;
use InetStudio\Persons\Contracts\Http\Responses\Back\Persons\FormResponseContract;
use InetStudio\Persons\Contracts\Http\Responses\Back\Persons\SaveResponseContract;
use InetStudio\Persons\Contracts\Http\Responses\Back\Persons\IndexResponseContract;
use InetStudio\Persons\Contracts\Http\Responses\Back\Persons\DestroyResponseContract;

/**
 * Class PersonsController.
 */
class PersonsController extends Controller implements PersonsControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    public $services;

    /**
     * PersonsController constructor.
     */
    public function __construct()
    {
        $this->services['persons'] = app()->make('InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract');
        $this->services['dataTables'] = app()->make('InetStudio\Persons\Contracts\Services\Back\PersonsDataTableServiceContract');
    }

    /**
     * Список объектов.
     *
     * @return IndexResponseContract
     */
    public function index(): IndexResponseContract
    {
        $table = $this->services['dataTables']->html();

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\IndexResponseContract', [
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
        $item = $this->services['persons']->getPersonObject();

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\FormResponseContract', [
            'data' => compact('item'),
        ]);
    }

    /**
     * Создание объекта.
     *
     * @param SavePersonRequestContract $request
     *
     * @return SaveResponseContract
     */
    public function store(SavePersonRequestContract $request): SaveResponseContract
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
        $item = $this->services['persons']->getPersonObject($id);

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\FormResponseContract', [
            'data' => compact('item'),
        ]);
    }

    /**
     * Обновление объекта.
     *
     * @param SavePersonRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    public function update(SavePersonRequestContract $request, int $id = 0): SaveResponseContract
    {
        return $this->save($request, $id);
    }

    /**
     * Сохранение объекта.
     *
     * @param SavePersonRequestContract $request
     * @param int $id
     *
     * @return SaveResponseContract
     */
    private function save(SavePersonRequestContract $request, int $id = 0): SaveResponseContract
    {
        $item = $this->services['persons']->save($request, $id);

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\SaveResponseContract', [
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
        $result = $this->services['persons']->destroy($id);

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Persons\DestroyResponseContract', [
            'result' => ($result === null) ? false : $result,
        ]);
    }
}
