import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    // 現在のメイン画面の状態
    // 'title', 'menu', 'battle'
    currentScreen: 'title',
    // 'battle'状態のサブステータスを定義する
    // 'encount', 'command', 'enemySelect', 'exec', 'result'
    battleStatus: 'encount'
  },
  mutations: {
    setScreen(state, screen) {
      state.currentScreen = screen;
    },
    setBattleStatus(state, status) {
      state.battleStatus = status;
    }
  },
  actions: {
    // commitで引数を渡す場合
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    },
    setBattleStatus({ commit }, status) {
      commit('setBattleStatus', status);
    }
  },
});
