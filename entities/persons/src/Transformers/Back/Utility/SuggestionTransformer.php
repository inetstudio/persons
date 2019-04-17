<?php

namespace InetStudio\PersonsPackage\Persons\Transformers\Back\Utility;

use League\Fractal\TransformerAbstract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Utility\SuggestionTransformerContract;

/**
 * Class SuggestionTransformer.
 */
class SuggestionTransformer extends TransformerAbstract implements SuggestionTransformerContract
{
    /**
     * @var string
     */
    protected $type;

    /**
     * SuggestionTransformer constructor.
     *
     * @param  string  $type
     */
    public function __construct(string $type = '')
    {
        $this->type = $type;
    }

    /**
     * Трансформация данных.
     *
     * @param  PersonModelContract  $item
     *
     * @return array
     */
    public function transform(PersonModelContract $item): array
    {
        return ($this->type == 'autocomplete')
            ? [
                'value' => $item['name'],
                'data' => [
                    'id' => $item['id'],
                    'type' => get_class($item),
                    'name' => $item['name'],
                    'post' => $item['post'],
                    'path' => parse_url($item['href'], PHP_URL_PATH),
                    'href' => $item['href'],
                    'preview' => ($item->hasMedia('preview'))
                        ? url($item->getFirstMediaUrl('preview', 'preview_square_sidebar'))
                        : '',
                ],
            ]
            : [
                'id' => $item['id'],
                'name' => $item['name'],
            ];
    }

    /**
     * Обработка коллекции объектов.
     *
     * @param $items
     *
     * @return FractalCollection
     */
    public function transformCollection($items): FractalCollection
    {
        return new FractalCollection($items, $this);
    }
}
