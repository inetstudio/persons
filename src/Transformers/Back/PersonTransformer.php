<?php

namespace InetStudio\Persons\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Persons\Contracts\Models\PersonModelContract;
use InetStudio\Persons\Contracts\Transformers\Back\PersonTransformerContract;

/**
 * Class PersonTransformer.
 */
class PersonTransformer extends TransformerAbstract implements PersonTransformerContract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param PersonModelContract $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(PersonModelContract $item): array
    {
        return [
            'id' => (int) $item->id,
            'name' => $item->name,
            'created_at' => (string) $item->created_at,
            'updated_at' => (string) $item->updated_at,
            'actions' => view('admin.module.persons::back.partials.datatables.actions', [
                'id' => $item->id,
                'href' => $item->href,
            ])->render(),
        ];
    }
}
