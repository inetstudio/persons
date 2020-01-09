window.tinymce.PluginManager.add('persons', function(editor) {
  let widgetData = {
    widget: {
      events: {
        widgetSaved: function(model) {
          editor.execCommand(
              'mceReplaceContent',
              false,
              '<img class="content-widget" data-type="person" data-id="' + model.id + '" alt="Виджет-персона: '+model.additional_info.name+'" />',
          );
        },
      },
    },
  };

  function initPersonsComponents() {
    if (typeof window.Admin.vue.modulesComponents.$refs['persons-package_PersonWidget'] == 'undefined') {
      window.Admin.vue.modulesComponents.modules['persons-package'].components = _.union(
          window.Admin.vue.modulesComponents.modules['persons-package'].components, [
            {
              name: 'PersonWidget',
              data: widgetData,
            },
          ]);
    } else {
      let component = window.Admin.vue.modulesComponents.$refs['persons-package_PersonWidget'][0];

      component.$data.model.id = widgetData.model.id;
    }
  }
  
  editor.addButton('add_person_widget', {
    title: 'Персоны',
    icon: 'fa fa-address-book',
    onclick: function() {
      editor.focus();

      let content = editor.selection.getContent();
      let isPerson = /<img class="content-widget".+data-type="person".+>/g.test(content);

      if (content === '' || isPerson) {
        widgetData.model = {
          id: parseInt($(content).attr('data-id')) || 0,
        };

        initPersonsComponents();

        window.waitForElement('#add_person_widget_modal', function() {
          $('#add_person_widget_modal').modal();
        });
      } else {
        swal({
          title: 'Ошибка',
          text: 'Необходимо выбрать виджет-ингредиент',
          type: 'error',
        });

        return false;
      }
    }
  });
});
