<?php

namespace InetStudio\Persons\Services\Front;

use InetStudio\AdminPanel\Services\Front\BaseService;
use InetStudio\AdminPanel\Services\Front\Traits\SlugsServiceTrait;
use InetStudio\Persons\Contracts\Services\Front\PersonsServiceContract;

/**
 * Class PersonsService.
 */
class PersonsService extends BaseService implements PersonsServiceContract
{
    use SlugsServiceTrait;

    /**
     * PersonsService constructor.
     */
    public function __construct()
    {
        parent::__construct(app()->make('InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract'));
    }

    /**
     * Получаем объекты по типу.
     *
     * @param string $type
     * @param array $params
     *
     * @return mixed
     */
    public function getPersonsByType(string $type = '', array $params = [])
    {
        $items = $this->repository->getItemsByType($type, $params);

        $data = [];

        $items->each(function ($item, $key) use (&$data) {
            foreach ($item->classifiers as $type) {
                $data[$type->alias][] = $item;
            }
        });

        return $data;
    }
}
