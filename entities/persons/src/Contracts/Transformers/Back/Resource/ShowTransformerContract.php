<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Resource;

use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;

/**
 * Interface ShowTransformerContract.
 */
interface ShowTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PersonModelContract  $item
     *
     * @return array
     */
    public function transform(PersonModelContract $item): array;
}
