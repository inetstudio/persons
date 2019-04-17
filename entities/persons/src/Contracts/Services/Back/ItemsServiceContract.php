<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Services\Back;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return PersonModelContract
     */
    public function save(array $data, int $id): PersonModelContract;

    /**
     * Присваиваем классификаторы объекту.
     *
     * @param $persons
     *
     * @param $item
     */
    public function attachToObject($persons, $item): void;

    /**
     * Получаем объекты по пользователю.
     *
     * @param  array|int  $id
     * @param  array  $params
     *
     * @return mixed
     */
    public function getItemsByUserId(int $id, array $params = []): Collection;
}
