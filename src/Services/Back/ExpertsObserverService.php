<?php

namespace InetStudio\Experts\Services\Back;

use InetStudio\Experts\Contracts\Models\ExpertModelContract;
use InetStudio\Experts\Contracts\Repositories\ExpertsRepositoryContract;
use InetStudio\Experts\Contracts\Services\Back\ExpertsObserverServiceContract;

/**
 * Class ExpertsObserverService.
 */
class ExpertsObserverService implements ExpertsObserverServiceContract
{
    /**
     * @var ExpertsRepositoryContract
     */
    private $repository;

    /**
     * ExpertsService constructor.
     *
     * @param ExpertsRepositoryContract $repository
     */
    public function __construct(ExpertsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Событие "объект создается".
     *
     * @param ExpertModelContract $item
     */
    public function creating(ExpertModelContract $item): void
    {
    }

    /**
     * Событие "объект создан".
     *
     * @param ExpertModelContract $item
     */
    public function created(ExpertModelContract $item): void
    {
    }

    /**
     * Событие "объект обновляется".
     *
     * @param ExpertModelContract $item
     */
    public function updating(ExpertModelContract $item): void
    {
    }

    /**
     * Событие "объект обновлен".
     *
     * @param ExpertModelContract $item
     */
    public function updated(ExpertModelContract $item): void
    {
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param ExpertModelContract $item
     */
    public function deleting(ExpertModelContract $item): void
    {
    }

    /**
     * Событие "объект удален".
     *
     * @param ExpertModelContract $item
     */
    public function deleted(ExpertModelContract $item): void
    {
    }
}
