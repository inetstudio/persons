<?php

namespace InetStudio\Persons\Services\Back;

use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract;
use InetStudio\Persons\Contracts\Services\Back\PersonsObserverServiceContract;

/**
 * Class PersonsObserverService.
 */
class PersonsObserverService implements PersonsObserverServiceContract
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
     * Событие "объект создается".
     *
     * @param PersonModelContract $item
     */
    public function creating(PersonModelContract $item): void
    {
    }

    /**
     * Событие "объект создан".
     *
     * @param PersonModelContract $item
     */
    public function created(PersonModelContract $item): void
    {
    }

    /**
     * Событие "объект обновляется".
     *
     * @param PersonModelContract $item
     */
    public function updating(PersonModelContract $item): void
    {
    }

    /**
     * Событие "объект обновлен".
     *
     * @param PersonModelContract $item
     */
    public function updated(PersonModelContract $item): void
    {
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param PersonModelContract $item
     */
    public function deleting(PersonModelContract $item): void
    {
    }

    /**
     * Событие "объект удален".
     *
     * @param PersonModelContract $item
     */
    public function deleted(PersonModelContract $item): void
    {
    }
}
