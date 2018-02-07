<?php

namespace InetStudio\Experts\Transformers;

use League\Fractal\TransformerAbstract;
use InetStudio\Experts\Models\ExpertModel;

class ExpertTransformer extends TransformerAbstract
{
    /**
     * Подготовка данных для отображения в таблице.
     *
     * @param ExpertModel $expert
     * @return array
     */
    public function transform(ExpertModel $expert): array
    {
        return [
            'id' => (int) $expert->id,
            'name' => $expert->name,
            'created_at' => (string) $expert->created_at,
            'updated_at' => (string) $expert->updated_at,
            'actions' => view('admin.module.experts::back.partials.datatables.actions', [
                'id' => $expert->id,
            ])->render(),
        ];
    }
}
