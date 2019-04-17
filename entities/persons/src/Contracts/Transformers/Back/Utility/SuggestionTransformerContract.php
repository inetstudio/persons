<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Utility;

use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;

/**
 * Interface SuggestionTransformerContract.
 */
interface SuggestionTransformerContract
{
    /**
     * Трансформация данных.
     *
     * @param  PersonModelContract  $item
     *
     * @return array
     */
    public function transform(PersonModelContract $item): array;

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection;
}
