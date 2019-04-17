<?php

namespace InetStudio\PersonsPackage\Persons\Services\Back;

use Illuminate\Support\Collection;
use InetStudio\AdminPanel\Base\Services\BaseService;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Services\Back\UtilityServiceContract;

/**
 * Class UtilityService.
 */
class UtilityService extends BaseService implements UtilityServiceContract
{
    /**
     * UtilityService constructor.
     *
     * @param  PersonModelContract  $model
     */
    public function __construct(PersonModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Получаем подсказки.
     *
     * @param  string  $search
     *
     * @return Collection
     */
    public function getSuggestions(string $search): Collection
    {
        $items = $this->model::where(
            [
                ['name', 'LIKE', '%'.$search.'%'],
            ]
        )->get();

        return $items;
    }
}
