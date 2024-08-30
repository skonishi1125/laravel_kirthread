import { createStore } from 'vuex';

export default createStore({
  state: {
    isInBattle: false
  },
  mutations: {
    startBattle(state) {
      state.isInBattle = true;
    },
    endBattle(state) {
      state.isInBattle = false;
    }
  },
  actions: {
    startBattle({ commit }) {
      commit('startBattle');
    },
    endBattle({ commit }) {
      commit('endBattle');
    },
  },
});
