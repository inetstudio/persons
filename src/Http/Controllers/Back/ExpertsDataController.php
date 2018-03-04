<?php

namespace InetStudio\Experts\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use InetStudio\Experts\Contracts\Services\Back\ExpertsDataTableServiceContract;
use InetStudio\Experts\Contracts\Http\Controllers\Back\ExpertsDataControllerContract;

/**
 * Class ExpertsDataController.
 */
class ExpertsDataController extends Controller implements ExpertsDataControllerContract
{
    /**
     * Получаем данные для отображения в таблице.
     *
     * @param ExpertsDataTableServiceContract $dataTableService
     *
     * @return mixed
     */
    public function data(ExpertsDataTableServiceContract $dataTableService)
    {
        return $dataTableService->ajax();
    }
}
