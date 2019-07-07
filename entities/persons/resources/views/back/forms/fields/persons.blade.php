@inject('personsService', 'InetStudio\PersonsPackage\Persons\Contracts\Services\Back\ItemsServiceContract')

@php
    $item = $value;

    $userID = auth()->user()->id;

    $value = $item->persons()->pluck('id')->toArray();
    $options = (old('persons')) ? $personsService->getItemById(old('persons'))->pluck('name', 'id')->toArray() : $item->persons()->pluck('name', 'id')->toArray();

    $userPerson = $personsService->getItemsByUserId($userID)->pluck('name', 'id')->toArray();
@endphp

{!! Form::dropdown('persons[]', (empty($value)) ? array_keys($userPerson) : $value, [
    'label' => [
        'title' => $attributes['label'] ?? 'Персоны',
    ],
    'field' => [
        'class' => 'select2-drop form-control',
        'data-placeholder' => $attributes['placeholder'] ?? 'Выберите персон',
        'style' => 'width: 100%',
        'multiple' => 'multiple',
        'data-source' => route('back.persons.getSuggestions'),
        'data-exclude' => isset($attributes['exclude']) ? implode('|', $attributes['exclude']) : '',
    ],
    'options' => [
        'values' => (empty($options)) ? $userPerson : $options,
    ],
]) !!}
