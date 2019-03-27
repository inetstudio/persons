@pushonce('modals:choose_person')
    <div id="choose_person_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h1 class="modal-title">Выберите персону</h1>
                </div>

                <div class="modal-body">
                    <div class="ibox-content">

                        {!! Form::hidden('person_data', '', [
                            'class' => 'choose-data',
                            'id' => 'person_data',
                        ]) !!}

                        {!! Form::string('person', '', [
                            'label' => [
                                'title' => 'Персона',
                            ],
                            'field' => [
                                'class' => 'form-control autocomplete',
                                'data-search' => route('back.persons.getSuggestions'),
                                'data-target' => '#person_data'
                            ],
                        ]) !!}

                        {!! Form::wysiwyg('person_opinion', '', [
                            'label' => [
                                'title' => 'Текст',
                            ],
                            'field' => [
                                'class' => 'tinymce-simple',
                                'type' => 'simple',
                                'id' => 'person_opinion',
                                'cols' => '50',
                                'rows' => '10',
                            ],
                        ]) !!}

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary save">Сохранить</a>
                </div>

            </div>
        </div>
    </div>
@endpushonce
