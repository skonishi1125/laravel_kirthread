import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    currentScreen: 'title' // 開始時点のスクリーンを設定
  },
  mutations: {
    // 現在の画面状態
    setScreen(state, screen) {
      state.currentScreen = screen;
    }
  },
  actions: {
    // commitで引数を渡す場合
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    }
  },
});
