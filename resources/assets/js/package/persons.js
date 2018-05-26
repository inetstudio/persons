let persons = {};

persons.init = function () {
    $('#choose_person_modal').on('hidden.bs.modal', function (e) {
        let modal = $(this);

        modal.find('.choose-data').val('');
        modal.find('input[name=person]').val('');
        window.tinymce.get('person_opinion').setContent('');
    })
};

module.exports = persons;
