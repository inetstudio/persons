window.Admin.vue.stores['persons-package_persons'] = new Vuex.Store({
  state: {
    emptyPerson: {
      model: {
        name: '',
        slug: '',
        post: '',
        description: '',
        content: '',
        user_id: 0,
        created_at: null,
        updated_at: null,
        deleted_at: null,
      },
      isModified: false,
      hash: '',
    },
    person: {},
    mode: '',
  },
  mutations: {
    setPerson(state, person) {
      let emptyPerson = JSON.parse(JSON.stringify(state.emptyPerson));
      emptyPerson.model.id = UUID.generate();

      let resultPerson = _.merge(emptyPerson, person);
      resultPerson.hash = window.hash(resultPerson.model);

      state.person = resultPerson;
    },
    setMode(state, mode) {
      state.mode = mode;
    }
  }
});
