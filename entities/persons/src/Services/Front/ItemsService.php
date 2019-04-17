<?php

namespace InetStudio\PersonsPackage\Persons\Services\Front;

use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Services\Front\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  PersonModelContract  $model
     */
    public function __construct(PersonModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Получаем объекты по типу.
     *
     * @param  string  $type
     * @param  array  $params
     *
     * @return array
     */
    public function getItemsByType(string $type = '', array $params = []): array
    {
        $items = $this->model->itemsByType($type, $params);

        $data = [];

        $items->each(
            function ($item) use (&$data) {
                foreach ($item->classifiers as $type) {
                    $data[$type->alias][] = $item;
                }
            }
        );

        return $data;
    }
}