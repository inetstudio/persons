require('./plugins/tinymce/plugins/persons');

require('../../../../../../widgets/resources/assets/js/mixins/widget');

require('./stores/persons');

Vue.component(
    'PersonWidget',
    require('./components/partials/PersonWidget/PersonWidget.vue').default,
);

let persons = require('./package/persons');
persons.init();
