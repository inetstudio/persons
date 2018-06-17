<?php

namespace InetStudio\Persons\Repositories;

use Illuminate\Database\Eloquent\Builder;
use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract;

/**
 * Class PersonsRepository.
 */
class PersonsRepository implements PersonsRepositoryContract
{
    /**
     * @var PersonModelContract
     */
    private $model;

    /**
     * PersonsRepository constructor.
     *
     * @param PersonModelContract $model
     */
    public function __construct(PersonModelContract $model)
    {
        $this->model = $model;
    }

    /**
     * Получаем модель репозитория.
     *
     * @return PersonModelContract
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Возвращаем объект по id, либо создаем новый.
     *
     * @param int $id
     *
     * @return PersonModelContract
     */
    public function getItemByID(int $id): PersonModelContract
    {
        return $this->model::find($id) ?? new $this->model;
    }

    /**
     * Возвращаем объекты по списку id.
     *
     * @param $ids
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemsByIDs($ids, bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery()->whereIn('id', (array) $ids);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Сохраняем объект.
     *
     * @param array $data
     * @param int $id
     *
     * @return PersonModelContract
     */
    public function save(array $data, int $id): PersonModelContract
    {
        $item = $this->getItemByID($id);
        $item->fill($data);
        $item->save();

        return $item;
    }

    /**
     * Удаляем объект.
     *
     * @param int $id
     *
     * @return bool
     */
    public function destroy($id): ?bool
    {
        return $this->getItemByID($id)->delete();
    }

    /**
     * Ищем объекты.
     *
     * @param array $conditions
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function searchItems(array $conditions, bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery([])->where($conditions);

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем все объекты.
     *
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getAllItems(bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery(['created_at', 'updated_at'], [])->orderBy('created_at', 'desc');

        if ($returnBuilder) {
            return $builder;
        }

        return $builder->get();
    }

    /**
     * Получаем объекты по slug.
     *
     * @param string $slug
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, bool $returnBuilder = false)
    {
        $builder = $this->getItemsQuery(['post', 'description', 'content'], ['meta', 'media'])->whereSlug($slug);

        if ($returnBuilder) {
            return $builder;
        }

        $item = $builder->first();

        return $item;
    }

    /**
     * Получаем объекты по типу.
     *
     * @param string $type
     *
     * @return array
     */
    public function getItemsByType(string $type = '')
    {
        $items = $this->getItemsQuery(['post', 'description'], ['classifiers', 'media']);

        if ($type) {
            $items = $items->whereHas('classifiers', function ($classifiersQuery) use ($type) {
                $classifiersQuery->where('classifiers.alias', $type);
            });
        } else {
            $items = $items->whereHas('classifiers');
        }

        $data = [];

        $items->get()->each(function ($item, $key) use (&$data) {
            foreach ($item->classifiers as $type) {
                $data[$type->alias][] = $item;
            }
        });

        return $data;
    }

    /**
     * Возвращаем запрос на получение объектов.
     *
     * @param array $extColumns
     * @param array $with
     *
     * @return Builder
     */
    protected function getItemsQuery($extColumns = [], $with = []): Builder
    {
        $defaultColumns = ['id', 'user_id', 'name', 'slug'];

        $relations = [
            'meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'classifiers' => function ($query) {
                $query->select(['type', 'value', 'alias']);
            },

            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk']);
            },
        ];

        return $this->model::select(array_merge($defaultColumns, $extColumns))
            ->with(array_intersect_key($relations, array_flip($with)));
    }
}
