require('./plugins/tinymce/plugins/persons');

require('../../../../../../widgets/entities/widgets/resources/assets/js/mixins/widget');

require('./stores/persons');

window.Vue.component(
    'PersonWidget',
    () => import('./components/partials/PersonWidget/PersonWidget.vue'),
);

let persons = require('./package/persons');
persons.init();
