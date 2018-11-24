@inject('personsService', 'InetStudio\Persons\Contracts\Services\Back\PersonsServiceContract')

@php
    $item = $value;

    $userID = \Auth::user()->id;

    $value = $item->persons()->pluck('id')->toArray();
    $options = (old('persons')) ? $personsService->getPersonsByIDs(old('persons'))->pluck('name', 'id')->toArray() : $item->persons()->pluck('name', 'id')->toArray();

    $userPerson = $personsService->getPersonByUserID($userID)->pluck('name', 'id')->toArray();
@endphp

{!! Form::dropdown('persons[]', (empty($value)) ? array_keys($userPerson) : $value, [
    'label' => [
        'title' => $attributes['label'] ?? 'Персоны',
    ],
    'field' => [
        'class' => 'select2 form-control',
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
