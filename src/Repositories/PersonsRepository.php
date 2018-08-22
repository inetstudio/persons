<?php

namespace InetStudio\Persons\Repositories;

use InetStudio\AdminPanel\Repositories\BaseRepository;
use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract;

/**
 * Class PersonsRepository.
 */
class PersonsRepository extends BaseRepository implements PersonsRepositoryContract
{
    /**
     * PersonsRepository constructor.
     *
     * @param PersonModelContract $model
     */
    public function __construct(PersonModelContract $model)
    {
        $this->model = $model;

        $this->defaultColumns = ['id', 'user_id', 'name', 'post', 'slug'];
        $this->relations = [
            'meta' => function ($query) {
                $query->select(['metable_id', 'metable_type', 'key', 'value']);
            },

            'classifiers' => function ($query) {
                $query->select(['type', 'value', 'alias']);
            },

            'media' => function ($query) {
                $query->select(['id', 'model_id', 'model_type', 'collection_name', 'file_name', 'disk', 'custom_properties']);
            },
        ];
    }

    /**
     * Получаем объект по slug.
     *
     * @param string $slug
     * @param array $params
     *
     * @return mixed
     */
    public function getItemBySlug(string $slug, array $params = [])
    {
        $builder = $this->getItemsQuery($params)
            ->whereSlug($slug);

        $item = $builder->first();

        return $item;
    }

    /**
     * Получаем объекты по типу.
     *
     * @param string $type
     * @param array $params
     *
     * @return array
     */
    public function getItemsByType(string $type = '', array $params = [])
    {
        $builder = $this->getItemsQuery($params);

        if ($type) {
            $builder->whereHas('classifiers', function ($classifiersQuery) use ($type) {
                $classifiersQuery->where('classifiers.alias', $type);
            });
        } else {
            $builder->whereHas('classifiers');
        }

        $items = $builder->get();

        return $items;
    }
}
