<?php

namespace InetStudio\Persons\Providers;

use Illuminate\Support\ServiceProvider;

/**
 * Class PersonsBindingsServiceProvider.
 */
class PersonsBindingsServiceProvider extends ServiceProvider
{
    /**
    * @var  bool
    */
    protected $defer = true;

    /**
    * @var  array
    */
    public $bindings = [
        'InetStudio\Persons\Contracts\Events\Back\ModifyPersonEventContract' => 'InetStudio\Persons\Events\Back\ModifyPersonEvent',
        'InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsControllerContract' => 'InetStudio\Persons\Http\Controllers\Back\PersonsController',
        'InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsDataControllerContract' => 'InetStudio\Persons\Http\Controllers\Back\PersonsDataController',
        'InetStudio\Persons\Contracts\Http\Controllers\Back\PersonsUtilityControllerContract' => 'InetStudio\Persons\Http\Controllers\Back\PersonsUtilityController',
        'InetStudio\Persons\Contracts\Http\Requests\Back\SavePersonRequestContract' => 'InetStudio\Persons\Http\Requests\Back\SavePersonRequest',
        'InetStudio\Persons\Contracts\Http\Responses\Back\Persons\DestroyResponseContract' => 'InetStudio\Persons\Http\Responses\Back\Persons\DestroyResponse',
        'InetStudio\Persons\Contracts\Http\Responses\Back\Persons\FormResponseContract' => 'InetStudio\Persons\Http\Responses\Back\Persons\FormResponse',
        'InetStudio\Persons\Contracts\Http\Responses\Back\Persons\IndexResponseContract' => 'InetStudio\Persons\Http\Responses\Back\Persons\IndexResponse',
        'InetStudio\Persons\Contracts\Http\Responses\Back\Persons\SaveResponseContract' => 'InetStudio\Persons\Http\Responses\Back\Persons\SaveResponse',
        'InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SlugResponseContract' => 'InetStudio\Persons\Http\Responses\Back\Utility\SlugResponse',
        'InetStudio\Persons\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract' => 'InetStudio\Persons\Http\Responses\Back\Utility\SuggestionsResponse',
        'InetStudio\Persons\Contracts\Models\PersonModelContract' => 'InetStudio\Persons\Models\PersonModel',
        'InetStudio\Persons\Contracts\Repositories\PersonsRepositoryContract' => 'InetStudio\Persons\Repositories\PersonsRepository',
        'InetStudio\Persons\Contracts\Services\Back\PersonsDataTableServiceContract' => 'InetStudio\Persons\Services\Back\PersonsDataTableService',
        'InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract' => 'InetStudio\Persons\Services\Back\PersonsService',
        'InetStudio\Persons\Contracts\Services\Front\PersonsServiceContract' => 'InetStudio\Persons\Services\Front\PersonsService',
        'InetStudio\Persons\Contracts\Transformers\Back\PersonTransformerContract' => 'InetStudio\Persons\Transformers\Back\PersonTransformer',
        'InetStudio\Persons\Contracts\Transformers\Back\SuggestionTransformerContract' => 'InetStudio\Persons\Transformers\Back\SuggestionTransformer',
    ];

    /**
     * Получить сервисы от провайдера.
     *
     * @return  array
     */
    public function provides()
    {
        return array_keys($this->bindings);
    }
}
