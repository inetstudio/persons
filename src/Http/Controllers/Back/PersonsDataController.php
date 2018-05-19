<?php

namespace InetStudio\Persons\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Persons\Contracts\Services\Back\PersonsDataTableServiceContract;
use InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsDataControllerContract;

/**
 * Class PersonsDataController.
 */
class PersonsDataController extends Controller implements PersonsDataControllerContract
{
    /**
     * Получаем данные для отображения в таблице.
     *
     * @param PersonsDataTableServiceContract $dataTableService
     *
     * @return mixed
     */
    public function data(PersonsDataTableServiceContract $dataTableService)
    {
        return $dataTableService->ajax();
    }
}
