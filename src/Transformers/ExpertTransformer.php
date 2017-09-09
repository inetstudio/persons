<?php

namespace Inetstudio\Experts\Transformers;

use League\Fractal\TransformerAbstract;
use InetStudio\Experts\Models\ExpertModel;

class ExpertTransformer extends TransformerAbstract
{
    /**
     * @param ExpertModel $expert
     * @return array
     */
    public function transform(ExpertModel $expert)
    {
        return [
            'id' => (int) $expert->id,
            'name' => $expert->name,
            'created_at' => (string) $expert->created_at,
            'updated_at' => (string) $expert->updated_at,
            'actions' => view('admin.module.experts::partials.datatables.actions', [
                'id' => $expert->id,
            ])->render(),
        ];
    }
}
