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
                            <div class="form-group">
                                <label class="col-sm-2 control-label">Эксперт</label>
                                <div class="col-sm-10">
                                    <input type="hidden" name="expert_data" class="choose-data">
                                    <input type="text" name="expert" data-search="{{ route('back.experts.getSuggestions') }}" class="form-control autocomplete">
                                </div>
                            </div>
                            <div class="form-group ">
                                <label for="expert_opinion" class="col-sm-2 control-label">Текст</label>
                                <div class="col-sm-10">
                                    <textarea class="tinymce-simple" type="simple" id="expert_opinion" properties="[]" name="expert_opinion" cols="50" rows="10"></textarea>
                                </div>
                            </div>
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

@pushonce('scripts:autocomplete')
    <!-- AUTOCOMPLETE -->
    <script src="{!! asset('admin/js/plugins/autocomplete/jquery.autocomplete.min.js') !!}"></script>
@endpushonce
