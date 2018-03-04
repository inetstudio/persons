<?php

namespace InetStudio\Experts\Observers;

use InetStudio\Experts\Contracts\Models\ExpertModelContract;
use InetStudio\Experts\Contracts\Observers\ExpertObserverContract;

/**
 * Class ExpertObserver.
 */
class ExpertObserver implements ExpertObserverContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    protected $services;

    /**
     * ExpertObserver constructor.
     */
    public function __construct()
    {
        $this->services['expertsObserver'] = app()->make('InetStudio\Experts\Contracts\Services\Back\ExpertsObserverServiceContract');
    }

    /**
     * Событие "объект создается".
     *
     * @param ExpertModelContract $item
     */
    public function creating(ExpertModelContract $item): void
    {
        $this->services['expertsObserver']->creating($item);
    }

    /**
     * Событие "объект создан".
     *
     * @param ExpertModelContract $item
     */
    public function created(ExpertModelContract $item): void
    {
        $this->services['expertsObserver']->created($item);
    }

    /**
     * Событие "объект обновляется".
     *
     * @param ExpertModelContract $item
     */
    public function updating(ExpertModelContract $item): void
    {
        $this->services['expertsObserver']->updating($item);
    }

    /**
     * Событие "объект обновлен".
     *
     * @param ExpertModelContract $item
     */
    public function updated(ExpertModelContract $item): void
    {
        $this->services['expertsObserver']->updated($item);
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param ExpertModelContract $item
     */
    public function deleting(ExpertModelContract $item): void
    {
        $this->services['expertsObserver']->deleting($item);
    }

    /**
     * Событие "объект удален".
     *
     * @param ExpertModelContract $item
     */
    public function deleted(ExpertModelContract $item): void
    {
        $this->services['expertsObserver']->deleted($item);
    }
}
