<?php

namespace InetStudio\PersonsPackage\Persons\Contracts\Http\Controllers\Back;

use Illuminate\Http\Request;
use Illuminate\Contracts\Container\BindingResolutionException;
use InetStudio\PersonsPackage\Persons\Contracts\Services\Back\ItemsServiceContract;
use InetStudio\PersonsPackage\Persons\Contracts\Services\Back\UtilityServiceContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Utility\SlugResponseContract;
use InetStudio\PersonsPackage\Persons\Contracts\Http\Responses\Back\Utility\SuggestionsResponseContract;

/**
 * Interface UtilityControllerContract.
 */
interface UtilityControllerContract
{
    /**
     * Получаем slug для модели по строке.
     *
     * @param  ItemsServiceContract  $itemsService
     * @param  Request  $request
     *
     * @return SlugResponseContract
     *
     * @throws BindingResolutionException
     */
    public function getSlug(ItemsServiceContract $itemsService, Request $request): SlugResponseContract;

    /**
     * Возвращаем объекты для поля.
     *
     * @param  UtilityServiceContract  $utilityService
     * @param  Request  $request
     *
     * @return SuggestionsResponseContract
     */
    public function getSuggestions(UtilityServiceContract $utilityService, Request $request): SuggestionsResponseContract;
}
