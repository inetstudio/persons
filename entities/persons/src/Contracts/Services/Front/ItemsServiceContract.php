<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Services\Front;

use InetStudio\AdminPanel\Base\Contracts\Services\BaseServiceContract;

/**
 * Interface ItemsServiceContract.
 */
interface ItemsServiceContract extends BaseServiceContract
{
    /**
     * Получаем объекты по типу.
     *
     * @param  string  $type
     * @param  array  $params
     *
     * @return mixed
     */
    public function getItemsByType(string $type = '', array $params = []): array;
}
