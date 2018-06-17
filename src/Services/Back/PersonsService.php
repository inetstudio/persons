<?php

namespace InetStudio\Persons\Services\Back;

use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract;
use InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract;
use InetStudio\Persons\Contracts\Http\Requests\Back\SavePersonRequestContract;

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
     * Получаем объект модели.
     *
     * @param int $id
     *
     * @return PersonModelContract
     */
    public function getPersonObject(int $id = 0)
    {
        return $this->repository->getItemByID($id);
    }

    /**
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPersonsByIDs($ids, bool $returnBuilder = false)
    {
        return $this->repository->getItemsByIDs($ids, $returnBuilder);
    }

    /**
     * Получаем объект по пользователю.
     *
     * @param array|int $id
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getPersonByUserID(int $id, bool $returnBuilder = false)
    {
        return $this->repository->searchItems([['user_id', '=', $id]], $returnBuilder);
    }

    /**
     * Сохраняем модель.
     *
     * @param SavePersonRequestContract $request
     * @param int $id
     *
     * @return PersonModelContract
     */
    public function save(SavePersonRequestContract $request, int $id): PersonModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';
        $item = $this->repository->save($request->only($this->repository->getModel()->getFillable()), $id);

        app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')
            ->attachToObject($request, $item);

        app()->make('InetStudio\Classifiers\Contracts\Services\Back\ClassifiersServiceContract')
            ->attachToObject($request, $item);

        $images = (config('persons.images.conversions.person')) ? array_keys(config('persons.images.conversions.person')) : [];
        app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract')
            ->attachToObject($request, $item, $images, 'persons', 'person');

        $item->searchable();

        event(app()->makeWith('InetStudio\Persons\Contracts\Events\Back\ModifyPersonEventContract', [
            'object' => $item,
        ]));

        Session::flash('success', 'Эксперт «'.$item->title.'» успешно '.$action);

        return $item;
    }

    /**
     * Удаляем модель.
     *
     * @param $id
     *
     * @return bool
     */
    public function destroy(int $id): ?bool
    {
        return $this->repository->destroy($id);
    }

    /**
     * Получаем подсказки.
     *
     * @param string $search
     * @param $type
     *
     * @return array
     */
    public function getSuggestions(string $search, $type): array
    {
        $items = $this->repository->searchItems([['name', 'LIKE', '%'.$search.'%']], true)->addSelect(['post'])->get();

        $resource = (app()->makeWith('InetStudio\Persons\Contracts\Transformers\Back\SuggestionTransformerContract', [
            'type' => $type,
        ]))->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        if ($type && $type == 'autocomplete') {
            $data['suggestions'] = $transformation['data'];
        } else {
            $data['items'] = $transformation['data'];
        }

        return $data;
    }

    /**
     * Присваиваем персон объекту.
     *
     * @param $request
     *
     * @param $item
     */
    public function attachToObject($request, $item)
    {
        if ($request->filled('persons')) {
            $item->syncPersons($this->repository->getItemsByIDs((array) $request->get('persons')));
        } else {
            $item->detachPersons($item->persons);
        }
    }
}
