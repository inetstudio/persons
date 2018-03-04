<?php

namespace InetStudio\Experts\Http\Controllers\Back;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cviebrock\EloquentSluggable\Services\SlugService;
use InetStudio\Experts\Contracts\Http\Responses\Back\Utility\SlugResponseContract;
use InetStudio\Experts\Contracts\Http\Controllers\Back\ExpertsUtilityControllerContract;
use InetStudio\Experts\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Class ExpertsUtilityController.
 */
class ExpertsUtilityController extends Controller implements ExpertsUtilityControllerContract
{
    /**
     * Получаем slug для модели по строке.
     *
     * @param Request $request
     *
     * @return SlugResponseContract
     */
    public function getSlug(Request $request): SlugResponseContract
    {
        $name = $request->get('name');
        $slug = ($name) ? SlugService::createSlug(app()->make('InetStudio\Experts\Contracts\Models\ExpertModelContract'), 'slug', $name) : '';

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Utility\SlugResponseContract', [
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

        $data = app()->make('InetStudio\Experts\Contracts\Services\Back\ExpertsServiceContract')
            ->getSuggestions($search, $type);

        return app()->makeWith('InetStudio\Experts\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract', [
            'suggestions' => $data,
        ]);
    }
}
