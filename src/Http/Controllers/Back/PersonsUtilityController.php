<?php

namespace InetStudio\Persons\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SlugResponseContract;
use InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsUtilityControllerContract;
use InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Class PersonsUtilityController.
 */
class PersonsUtilityController extends Controller implements PersonsUtilityControllerContract
{
    /**
     * Используемые сервисы.
     *
     * @var array
     */
    public $services;

    /**
     * PersonsUtilityController constructor.
     */
    public function __construct()
    {
        $this->services['persons'] = app()->make('InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract');
    }

    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     *
     * @return SlugResponseContract
     */
    public function getSlug(Request $request): SlugResponseContract
    {
        $id = (int) $request->get('id');
        $name = $request->get('name');

        $model = $this->services['persons']->getPersonObject($id);

        $slug = ($name) ? SlugService::createSlug($model, 'slug', $name) : '';

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SlugResponseContract', [
            'slug' => $slug,
        ]);
    }

    /**
     * Возвращаем объекты для поля.
     *
     * @param Request $request
     *
     * @return SuggestionsResponseContract
     */
    public function getSuggestions(Request $request): SuggestionsResponseContract
    {
        $search = $request->get('q');
        $type = $request->get('type');

        $data = app()->make('InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract')
            ->getSuggestions($search, $type);

        return app()->makeWith('InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', [
            'suggestions' => $data,
        ]);
    }
}
