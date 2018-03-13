<?php

namespace InetStudio\Experts\Services\Back;

use League\Fractal\Manager;
use Illuminate\Support\Facades\Session;
use League\Fractal\Serializer\DataArraySerializer;
use InetStudio\Experts\Contracts\Models\ExpertModelContract;
use InetStudio\Experts\Contracts\Services\Back\ExpertsServiceContract;
use InetStudio\Experts\Contracts\Repositories\ExpertsRepositoryContract;
use InetStudio\Experts\Contracts\Http\Requests\Back\SaveExpertRequestContract;

/**
 * Class ExpertsService.
 */
class ExpertsService implements ExpertsServiceContract
{
    /**
     * @var ExpertsRepositoryContract
     */
    private $repository;

    /**
     * ExpertsService constructor.
     *
     * @param ExpertsRepositoryContract $repository
     */
    public function __construct(ExpertsRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Получаем объект модели.
     *
     * @param int $id
     *
     * @return ExpertModelContract
     */
    public function getExpertObject(int $id = 0)
    {
        return $this->repository->getItemByID($id);
    }

    /**
     * Получаем объекты по списку id.
     *
     * @param array|int $ids
     * @param bool $returnBuilder
     *
     * @return mixed
     */
    public function getExpertsByIDs($ids, bool $returnBuilder = false)
    {
        return $this->repository->getItemsByIDs($ids, $returnBuilder);
    }

    /**
     * Сохраняем модель.
     *
     * @param SaveExpertRequestContract $request
     * @param int $id
     *
     * @return ExpertModelContract
     */
    public function save(SaveExpertRequestContract $request, int $id): ExpertModelContract
    {
        $action = ($id) ? 'отредактирован' : 'создан';
        $item = $this->repository->save($request, $id);

        app()->make('InetStudio\Meta\Contracts\Services\Back\MetaServiceContract')
            ->attachToObject($request, $item);

        $images = (config('experts.images.conversions.expert')) ? array_keys(config('experts.images.conversions.expert')) : [];
        app()->make('InetStudio\Uploads\Contracts\Services\Back\ImagesServiceContract')
            ->attachToObject($request, $item, $images, 'experts', 'expert');

        $item->searchable();

        event(app()->makeWith('InetStudio\Experts\Contracts\Events\Back\ModifyExpertEventContract', [
            'object' => $item,
        ]));

        Session::flash('success', 'Эксперт «'.$item->title.'» успешно '.$action);

        return $item;
    }

    /**
     * Удаляем модель.
     *
     * @param $id
     *
     * @return bool
     */
    public function destroy(int $id): ?bool
    {
        return $this->repository->destroy($id);
    }

    /**
     * Получаем подсказки.
     *
     * @param string $search
     * @param $type
     *
     * @return array
     */
    public function getSuggestions(string $search, $type): array
    {
        $items = $this->repository->searchItemsByField('name', $search, true)->addSelect(['post'])->get();

        $resource = (app()->makeWith('InetStudio\Experts\Contracts\Transformers\Back\SuggestionTransformerContract', [
            'type' => $type,
        ]))->transformCollection($items);

        $manager = new Manager();
        $manager->setSerializer(new DataArraySerializer());

        $transformation = $manager->createData($resource)->toArray();

        if ($type && $type == 'autocomplete') {
            $data['suggestions'] = $transformation['data'];
        } else {
            $data['items'] = $transformation['data'];
        }

        return $data;
    }
}
