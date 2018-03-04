<?php

namespace InetStudio\Experts\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Experts\Contracts\Models\ExpertModelContract;
use InetStudio\Experts\Contracts\Transformers\Back\ExpertTransformerContract;

class ExpertTransformer extends TransformerAbstract implements ExpertTransformerContract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param ExpertModelContract $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(ExpertModelContract $item): array
    {
        return [
            'id' => (int) $item->id,
            'name' => $item->name,
            'created_at' => (string) $item->created_at,
            'updated_at' => (string) $item->updated_at,
            'actions' => view('admin.module.experts::back.partials.datatables.actions', [
                'id' => $item->id,
            ])->render(),
        ];
    }
}
