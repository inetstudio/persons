<?php

namespace InetStudio\PersonsPackage\Persons\Services\Back;

use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use InetStudio\AdminPanel\Base\Services\BaseService;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Services\Back\ItemsServiceContract;

/**
 * Class ItemsService.
 */
class ItemsService extends BaseService implements ItemsServiceContract
{
    /**
     * ItemsService constructor.
     *
     * @param  PersonModelContract  $model
     */
    public function __construct(PersonModelContract $model)
    {
        parent::__construct($model);
    }

    /**
     * Сохраняем модель.
     *
     * @param  array  $data
     * @param  int  $id
     *
     * @return PersonModelContract
     *
     * @throws BindingResolutionException
     */
    public function save(array $data, int $id): PersonModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';

        $itemData = Arr::only($data, $this->model->getFillable());
        $item = $this->saveModel($itemData, $id);

        $metaData = Arr::get($data, 'meta', []);
        app()->make('InetStudio\MetaPackage\Meta\Contracts\Services\Back\ItemsServiceContract')
            ->attachToObject($metaData, $item);

        $classifiersData = Arr::get($data, 'classifiers', []);
        app()->make('InetStudio\Classifiers\Entries\Contracts\Services\Back\ItemsServiceContract')
            ->attachToObject($classifiersData, $item);

        resolve(
            'InetStudio\UploadsPackage\Uploads\Contracts\Actions\AttachMediaToObjectActionContract',
            [
                'item' => $item,
                'media' => Arr::get($data, 'media', []),
            ]
        )->execute();

        $item->searchable();

        event(
            app()->makeWith(
                'InetStudio\PersonsPackage\Persons\Contracts\Events\Back\ModifyItemEventContract',
                compact('item')
            )
        );

        Session::flash('success', 'Персона «'.$item->name.'» успешно '.$action);

        return $item;
    }

    /**
     * Присваиваем классификаторы объекту.
     *
     * @param $persons
     *
     * @param $item
     */
    public function attachToObject($persons, $item): void
    {
        if ($persons instanceof Request) {
            $persons = $persons->get('persons', []);
        } else {
            $persons = (array) $persons;
        }

        if (! empty($persons)) {
            $item->syncPersons($this->model::whereIn('id', $persons)->get());
        } else {
            $item->detachPersons($item->persons);
        }
    }

    /**
     * Получаем объекты по пользователю.
     *
     * @param  array|int  $id
     * @param  array  $params
     *
     * @return mixed
     */
    public function getItemsByUserId(int $id, array $params = []): Collection
    {
        return $this->model->buildQuery($params)->where('user_id', '=', $id)->get();
    }
}
