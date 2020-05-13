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
        }
      }
    }
  };

  function loadWidget() {
    let component = window.Admin.vue.helpers.getVueComponent('persons-package', 'PersonWidget');

    component.$data.model.id = widgetData.model.id;
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

        window.Admin.vue.helpers.initComponent('persons-package', 'PersonWidget', widgetData);

        window.waitForElement('#add_person_widget_modal', function() {
          loadWidget();

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
