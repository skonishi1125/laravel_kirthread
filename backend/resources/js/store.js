import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    isInBattle: false,
    currentScreen: 'title' // 開始時点のスクリーンを設定
  },
  mutations: {
    startBattle(state) {
      state.isInBattle = true;
    },
    endBattle(state) {
      state.isInBattle = false;
    },

    // 現在の画面状態
    setScreen(state, screen) {
      state.currentScreen = screen;
    }

  },
  actions: {
    startBattle({ commit }) {
      commit('startBattle');
    },
    endBattle({ commit }) {
      commit('endBattle');
    },

    // commitで引数を渡す場合
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    }

  },
});
