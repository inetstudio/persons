<?php

namespace InetStudio\Experts\Transformers\Back;

use League\Fractal\TransformerAbstract;
use InetStudio\Experts\Contracts\Models\ExpertModelContract;
use League\Fractal\Resource\Collection as FractalCollection;
use InetStudio\Experts\Contracts\Transformers\Back\SuggestionTransformerContract;

/**
 * Class SuggestionTransformer.
 */
class SuggestionTransformer extends TransformerAbstract implements SuggestionTransformerContract
{
    /**
     * @var string
     */
    private $type;

    /**
     * ExpertsSiteMapTransformer constructor.
     *
     * @param $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Подготовка данных для отображения в выпадающих списках.
     *
     * @param ExpertModelContract $item
     *
     * @return array
     *
     * @throws \Throwable
     */
    public function transform(ExpertModelContract $item): array
    {
        if ($this->type && $this->type == 'autocomplete') {
            $modelClass = get_class($item);

            return [
                'value' => $item->name,
                'data' => [
                    'id' => $item->id,
                    'type' => $modelClass,
                    'name' => $item->name,
                    'post' => $item->post,
                    'path' => parse_url($item->href, PHP_URL_PATH),
                    'href' => $item->href,
                    'preview' => ($item->getFirstMedia('preview')) ? url($item->getFirstMedia('preview')->getUrl('preview_default')) : '',
                ],
            ];
        } else {
            return [
                'id' => $item->id,
                'name' => $item->name,
            ];
        }
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
