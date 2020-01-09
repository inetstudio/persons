let persons = {};

persons.init = function() {
  if (!window.Admin.vue.modulesComponents.modules.hasOwnProperty('persons-package')) {
    window.Admin.vue.modulesComponents.modules = Object.assign(
        {}, window.Admin.vue.modulesComponents.modules, {
          'persons-package': {
            components: [],
          },
        });
  }
};

module.exports = persons;
