<?php

namespace InetStudio\PersonsPackage\Persons\Transformers\Back\Resource;

use Throwable;
use League\Fractal\TransformerAbstract;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Resource\ShowTransformerContract;

/**
 * Class ShowTransformer.
 */
class ShowTransformer extends TransformerAbstract implements ShowTransformerContract
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
            'id' => $item['id'],
            'name' => $item['name'],
        ];
    }
}
