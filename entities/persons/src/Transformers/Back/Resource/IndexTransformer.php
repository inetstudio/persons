<?php

namespace InetStudio\PersonsPackage\Persons\Transformers\Back\Resource;

use Throwable;
use League\Fractal\TransformerAbstract;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Resource\IndexTransformerContract;

/**
 * Class IndexTransformer.
 */
class IndexTransformer extends TransformerAbstract implements IndexTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PersonModelContract  $item
     *
     * @return array
     *
     * @throws Throwable
     */
    public function transform(PersonModelContract $item): array
    {
        return [
            'id' => (int) $item['id'],
            'name' => $item['name'],
            'created_at' => (string) $item['created_at'],
            'updated_at' => (string) $item['updated_at'],
            'actions' => view(
                'admin.module.persons::back.partials.datatables.actions',
                [
                    'id' => $item['id'],
                    'href' => $item['href'],
                ]
            )->render(),
        ];
    }
}
