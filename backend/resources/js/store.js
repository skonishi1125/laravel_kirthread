import { createStore } from 'vuex';

// 戦闘状態の管理
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
