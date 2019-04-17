<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Http\Controllers\Back;

use InetStudio\PersonsPackage\Persons\Contracts\Services\Back\ItemsServiceContract;
use InetStudio\PersonsPackage\Persons\Contracts\Services\Back\DataTableServiceContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Requests\Back\SaveItemRequestContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\FormResponseContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\SaveResponseContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\IndexResponseContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\DestroyResponseContract;

/**
 * Interface ResourceControllerContract.
 */
interface ResourceControllerContract
{
    /**
     * Список объектов.
     *
     * @param  DataTableServiceContract  $dataTableService
     *
     * @return IndexResponseContract
     */
    public function index(DataTableServiceContract $dataTableService): IndexResponseContract;

    /**
     * Создание объекта.
     *
     * @param  ItemsServiceContract  $resourceService
     *
     * @return FormResponseContract
     */
    public function create(ItemsServiceContract $resourceService): FormResponseContract;

    /**
     * Создание объекта.
     *
     * @param  ItemsServiceContract  $resourceService
     * @param  SaveItemRequestContract  $request
     *
     * @return SaveResponseContract
     */
    public function store(ItemsServiceContract $resourceService, SaveItemRequestContract $request): SaveResponseContract;

    /**
     * Редактирование объекта.
     *
     * @param  ItemsServiceContract  $resourceService
     * @param  int  $id
     *
     * @return FormResponseContract
     */
    public function edit(ItemsServiceContract $resourceService, int $id = 0): FormResponseContract;

    /**
     * Обновление объекта.
     *
     * @param  ItemsServiceContract  $resourceService
     * @param  SaveItemRequestContract  $request
     * @param  int  $id
     *
     * @return SaveResponseContract
     */
    public function update(
        ItemsServiceContract $resourceService,
        SaveItemRequestContract $request,
        int $id = 0
    ): SaveResponseContract;

    /**
     * Удаление объекта.
     *
     * @param  ItemsServiceContract  $resourceService
     * @param  int  $id
     *
     * @return DestroyResponseContract
     */
    public function destroy(ItemsServiceContract $resourceService, int $id = 0): DestroyResponseContract;
}
