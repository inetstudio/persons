<?php

namespace InetStudio\PersonsPackage\Persons\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * Class BindingsServiceProvider.
 */
class BindingsServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @var array
     */
    public $bindings = [
        'InetStudio\PersonsPackage\Persons\Contracts\Events\Back\ModifyItemEventContract' => 'InetStudio\PersonsPackage\Persons\Events\Back\ModifyItemEvent',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Controllers\Back\ResourceControllerContract' => 'InetStudio\PersonsPackage\Persons\Http\Controllers\Back\ResourceController',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Controllers\Back\DataControllerContract' => 'InetStudio\PersonsPackage\Persons\Http\Controllers\Back\DataController',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Controllers\Back\UtilityControllerContract' => 'InetStudio\PersonsPackage\Persons\Http\Controllers\Back\UtilityController',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Requests\Back\SaveItemRequestContract' => 'InetStudio\PersonsPackage\Persons\Http\Requests\Back\SaveItemRequest',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\DestroyResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource\DestroyResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\FormResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource\FormResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\IndexResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource\IndexResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\SaveResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource\SaveResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Resource\ShowResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Resource\ShowResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Utility\SlugResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Utility\SlugResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\PersonsPackage\Persons\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\PersonsPackage\Persons\Contracts\Models\PersonModelContract' => 'InetStudio\PersonsPackage\Persons\Models\PersonModel',
        'InetStudio\PersonsPackage\Persons\Contracts\Services\Back\DataTableServiceContract' => 'InetStudio\PersonsPackage\Persons\Services\Back\DataTableService',
        'InetStudio\PersonsPackage\Persons\Contracts\Services\Back\ItemsServiceContract' => 'InetStudio\PersonsPackage\Persons\Services\Back\ItemsService',
        'InetStudio\PersonsPackage\Persons\Contracts\Services\Back\UtilityServiceContract' => 'InetStudio\PersonsPackage\Persons\Services\Back\UtilityService',
        'InetStudio\PersonsPackage\Persons\Contracts\Services\Front\ItemsServiceContract' => 'InetStudio\PersonsPackage\Persons\Services\Front\ItemsService',
        'InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Resource\IndexTransformerContract' => 'InetStudio\PersonsPackage\Persons\Transformers\Back\Resource\IndexTransformer',
        'InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Resource\ShowTransformerContract' => 'InetStudio\PersonsPackage\Persons\Transformers\Back\Resource\ShowTransformer',
        'InetStudio\PersonsPackage\Persons\Contracts\Transformers\Back\Utility\SuggestionTransformerContract' => 'InetStudio\PersonsPackage\Persons\Transformers\Back\Utility\SuggestionTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
