@pushonce('modals:choose_expert')
    <div id="choose_expert_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h1 class="modal-title">Выберите эксперта</h1>
                </div>

                <div class="modal-body">
                    <div class="ibox-content form-horizontal">
                        <div class="row">

                            {!! Form::hidden('expert_data', '', [
                                'class' => 'choose-data',
                                'id' => 'expert_data',
                            ]) !!}

                            {!! Form::string('expert', '', [
                                'label' => [
                                    'title' => 'Эксперт',
                                ],
                                'field' => [
                                    'class' => 'form-control autocomplete',
                                    'data-search' => route('back.experts.getSuggestions'),
                                    'data-target' => '#expert_data'
                                ],
                            ]) !!}

                            {!! Form::wysiwyg('expert_opinion', '', [
                                'label' => [
                                    'title' => 'Текст',
                                ],
                                'field' => [
                                    'class' => 'tinymce-simple',
                                    'type' => 'simple',
                                    'id' => 'expert_opinion',
                                    'cols' => '50',
                                    'rows' => '10',
                                ],
                            ]) !!}

                        </div>
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
