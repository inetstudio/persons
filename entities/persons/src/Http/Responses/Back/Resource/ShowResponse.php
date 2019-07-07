<?php

namespace InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource;

use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item as FractalItem;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\ShowResponseContract;

/**
 * Class ShowResponse.
 */
class ShowResponse implements ShowResponseContract
{
    /**
     * @var PersonModelContract
     */
    protected $item;

    /**
     * ShowResponse constructor.
     *
     * @param  PersonModelContract  $item
     */
    public function __construct(PersonModelContract $item)
    {
        $this->item = $item;
    }

    /**
     * Возвращаем ответ при получении объекта.
     *
     * @param  Request  $request
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     *
     * @throws BindingResolutionException
     */
    public function toResponse($request)
    {
        $resource = new FractalItem(
            $this->item,
            app()->make('InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Resource\ShowTransformerContract')
        );

        $serializer = app()->make('InetStudio\AdminPanel\Base\Contracts\Serializers\SimpleDataArraySerializerContract');

        $manager = new Manager();
        $manager->setSerializer($serializer);

        $transformation = $manager->createData($resource)->toArray();
        $transformation['success'] = ((bool) $transformation['id']);

        return response()->json($transformation);
    }
}
