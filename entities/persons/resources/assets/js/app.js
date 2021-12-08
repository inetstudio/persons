import {persons} from './package/persons';

require('./plugins/tinymce/plugins/persons');

require('../../../../../../widgets/entities/widgets/resources/assets/js/mixins/widget');

require('./stores/persons');

window.Vue.component(
    'PersonWidget',
    () => import('./components/partials/PersonWidget/PersonWidget.vue'),
);

persons.init();
