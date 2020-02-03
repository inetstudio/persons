<template>
    <div id="add_person_widget_modal" tabindex="-1" role="dialog" aria-hidden="true" class="modal inmodal fade" ref="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрыть</span></button>
                    <h1 class="modal-title">Выберите персону</h1>
                </div>
                <div class="modal-body">
                    <div class="ibox-content" v-bind:class="{ 'sk-loading': options.loading }">
                        <div class="sk-spinner sk-spinner-double-bounce">
                            <div class="sk-double-bounce1"></div>
                            <div class="sk-double-bounce2"></div>
                        </div>

                        <base-autocomplete
                            ref="person_suggestion"
                            label="Персона"
                            name="person_suggestion"
                            v-bind:value="model.additional_info.name"
                            v-bind:attributes="{
                                'data-search': suggestionsUrl,
                                'placeholder': 'Выберите персону',
                                'autocomplete': 'off'
                            }"
                            v-on:select="suggestionSelect"
                        />

                        <base-wysiwyg
                            label = "Текст"
                            name = "person_opinion"
                            v-bind:value.sync="model.params.opinion"
                            v-bind:simple = true
                            v-bind:attributes = "{
                                'id': 'person_opinion',
                                'cols': '50',
                                'rows': '10',
                            }"
                        />
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-dismiss="modal">Закрыть</button>
                    <a href="#" class="btn btn-primary" v-on:click.prevent="save">Сохранить</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'PersonWidget',
    data() {
      return {
        model: this.getDefaultModel(),
        options: {
          loading: true,
        },
        events: {
          widgetLoaded: function(component) {
            let url = route('back.persons.show', component.model.params.id).toString();

            component.options.loading = true;

            axios.get(url).then(response => {
              $(component.$refs.person_suggestion.$refs.autocomplete).val(response.data.name).trigger('change');
              window.tinymce.get('person_opinion').setContent(_.get(component.model.params, 'opinion', ''));

              component.options.loading = false;
            });
          },
        },
      };
    },
    computed: {
      suggestionsUrl() {
        return route('back.persons.getSuggestions').toString();
      },
      modalPersonState() {
        return window.Admin.vue.stores['persons-package_persons'].state.mode;
      },
    },
    watch: {
      modalPersonState: function(newMode) {
        if (newMode === 'person_created') {
          let person = window.Admin.vue.stores['persons-package_persons'].state.person;

          this.model.params.id = person.model.id;

          this.save();
        }
      },
    },
    methods: {
      getDefaultModel() {
        return _.merge(this.getDefaultWidgetModel(), {
          view: 'admin.module.persons::front.partials.content.person_widget'
        });
      },
      initComponent() {
        let component = this;

        component.model = _.merge(component.model, this.widget.model);
        component.options.loading = false;
      },
      suggestionSelect(payload) {
        let component = this;

        let data = payload.data;

        component.model.params.id = data.id;
        component.model.additional_info = _.merge(component.model.additional_info, data);
      },
      save() {
        let component = this;

        if (! _.get(component.model.params, 'id')) {
          $(component.$refs.modal).modal('hide');

          return;
        }

        component.saveWidget(function() {
          $(component.$refs.modal).modal('hide');
        });
      }
    },
    created: function() {
      this.initComponent();
    },
    mounted() {
      let component = this;

      this.$nextTick(function() {
        $(component.$refs.modal).on('hide.bs.modal', function() {
          component.model = component.getDefaultModel();
          window.tinymce.get('person_opinion').setContent('');
        });
      });
    },
    mixins: [
      window.Admin.vue.mixins['widget'],
    ],
  };
</script>
