<?php

namespace InetStudio\Persons\Observers;

use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Observers\PersonObserverContract;

/**
 * Class PersonObserver.
 */
class PersonObserver implements PersonObserverContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    protected $services;

    /**
     * PersonObserver constructor.
     */
    public function __construct()
    {
        $this->services['personsObserver'] = app()->make('InetStudio\Persons\Contracts\Services\Back\PersonsObserverServiceContract');
    }

    /**
     * Событие "объект создается".
     *
     * @param PersonModelContract $item
     */
    public function creating(PersonModelContract $item): void
    {
        $this->services['personsObserver']->creating($item);
    }

    /**
     * Событие "объект создан".
     *
     * @param PersonModelContract $item
     */
    public function created(PersonModelContract $item): void
    {
        $this->services['personsObserver']->created($item);
    }

    /**
     * Событие "объект обновляется".
     *
     * @param PersonModelContract $item
     */
    public function updating(PersonModelContract $item): void
    {
        $this->services['personsObserver']->updating($item);
    }

    /**
     * Событие "объект обновлен".
     *
     * @param PersonModelContract $item
     */
    public function updated(PersonModelContract $item): void
    {
        $this->services['personsObserver']->updated($item);
    }

    /**
     * Событие "объект подписки удаляется".
     *
     * @param PersonModelContract $item
     */
    public function deleting(PersonModelContract $item): void
    {
        $this->services['personsObserver']->deleting($item);
    }

    /**
     * Событие "объект удален".
     *
     * @param PersonModelContract $item
     */
    public function deleted(PersonModelContract $item): void
    {
        $this->services['personsObserver']->deleted($item);
    }
}
