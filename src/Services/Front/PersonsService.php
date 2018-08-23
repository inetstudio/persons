<?php

namespace InetStudio\Persons\Services\Front;

use InetStudio\Persons\Contracts\Services\Front\PersonsServiceContract;

/**
 * Class PersonsService.
 */
class PersonsService implements PersonsServiceContract
{
    /**
     * @var
     */
    public $repository;

    /**
     * PersonsService constructor.
     */
    public function __construct()
    {
        $this->repository = app()->make('InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract');
    }

    /**
     * Получаем объект по id.
     *
     * @param int $id
     *
     * @return mixed
     */
    public function getPersonById(int $id = 0)
    {
        return $this->repository->getItemByID($id);
    }

    /**
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param array $params
     *
     * @return mixed
     */
    public function getPersonsByIDs($ids, array $params = [])
    {
        return $this->repository->getItemsByIDs($ids, $params);
    }

    /**
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getPersonBySlug(string $slug, array $params = [])
    {
        return $this->repository->getItemBySlug($slug, $params);
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
