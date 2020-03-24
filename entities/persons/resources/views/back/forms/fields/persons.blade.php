@inject('personsService', 'InetStudio\PersonsPackage\Persons\Contracts\Services\Back\ItemsServiceContract')

@php
    $item = $value;

    $userID = auth()->user()->id;

    $value = $item->persons()->pluck('id')->toArray();
    $options = (old('persons')) ? $personsService->getItemById(old('persons'))->pluck('name', 'id')->toArray() : $item->persons()->pluck('name', 'id')->toArray();

    $userPerson = $personsService->getItemsByUserId($userID)->pluck('name', 'id')->toArray();
@endphp

{!! Form::dropdown(($name ?? 'persons').'[]', (empty($value)) ? array_keys($userPerson) : $value, [
    'label' => [
        'title' => $attributes['label'] ?? 'Персоны',
    ],
    'field' => array_merge([
        'class' => 'select2-drop form-control',
        'data-placeholder' => $attributes['placeholder'] ?? 'Выберите персон',
        'style' => 'width: 100%',
        'data-source' => route('back.persons.getSuggestions'),
        'data-exclude' => isset($attributes['exclude']) ? implode('|', $attributes['exclude']) : '',
    ], ((! isset($attributes['field']['multiple'])) || isset($attributes['field']['multiple']) && $attributes['field']['multiple']) ? ['multiple' => 'multiple'] : [],
       (isset($attributes['field']['readonly']) && $attributes['field']['readonly']) ? ['readonly' => 'readonly'] : []
    ),
    'options' => [
        'values' => (empty($options)) ? $userPerson : $options,
    ],
]) !!}
