<?php

namespace InetStudio\Persons\Services\Front;

use InetStudio\Persons\Contracts\Services\Front\PersonsServiceContract;
use InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract;

/**
 * Class PersonsService.
 */
class PersonsService implements PersonsServiceContract
{
    /**
     * @var PersonsRepositoryContract
     */
    private $repository;

    /**
     * PersonsService constructor.
     *
     * @param PersonsRepositoryContract $repository
     */
    public function __construct(PersonsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPersonBySlug(string $slug, bool $returnBuilder = false)
    {
        return $this->repository->getItemBySlug($slug, $returnBuilder);
    }

    /**
     * Получаем объекты по типу.
     *
     * @param string $type
     *
     * @return mixed
     */
    public function getPersonsByType(string $type = '')
    {
        return $this->repository->getItemsByType($type);
    }
}
