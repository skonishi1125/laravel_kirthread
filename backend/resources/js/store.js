import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    // メイン画面の状態 'title', 'menu', 'battle'
    currentScreen: 'title',
    battleStatus: 'encount', // 'battle'状態のサブステータス 'encount', 'command', 'enemySelect', 'exec', 'outputLog', 'result'
    selectedCommands: [], // 味方の選択コマンド
    selectedEnemies: [],  // コマンドで選択した敵
    currentPartyMemberIndex: 0, // どの味方のコマンドを選択しているかのindex [0]か[1]か[2]
    battleSessionId: null, // 戦闘データのセッション
  },
  mutations: {
    setScreen(state, screen) {
      state.currentScreen = screen;
    },
    setBattleSessionId(state, sessionId) {
      state.battleSessionId = sessionId;
    },
    setBattleStatus(state, status) {
      state.battleStatus = status;
    },
    setSelectedCommand(state, { partyId, command }) {
      state.selectedCommands.push({ partyId, command });
      // この時点でコマンド状態: 
      // [ { "partyId": "1", "command": "ATTACK" } ]
    },
    clearSelectedCommands(state) {
      state.selectedCommands = {};
    },

    setSelectedEnemy(state, { partyId, enemyIndex }) {
      const commandIndex = state.selectedCommands.findIndex(c => c.partyId === partyId);
      // 下と同じ意味
      // const commandIndex = state.selectedCommands.findIndex(function(c) {
      //   return c.partyId === partyId;
      // });
      if (commandIndex !== -1) {
        state.selectedCommands[commandIndex].enemyIndex = enemyIndex;
        // 1人選んだ時点でのコマンド状態:
        // [ { "partyId": 1, "command": "ATTACK", "enemyIndex": 1 } ]
        // 3人選んだ状態だと下記。
        // [ { "partyId": 1, "command": "ATTACK", "enemyIndex": 1 }, { "partyId": 2, "command": "ATTACK", "enemyIndex": 1 }, { "partyId": 3, "command": "ATTACK", "enemyIndex": 1 } ]
      }
    },
    incrementPartyMemberIndex(state) {
      state.currentPartyMemberIndex += 1;
    },
    resetPartyMemberIndex(state) {
      state.currentPartyMemberIndex = 0;
    },

    // コマンドが2週目以降続く場合、選択履歴をリセット
    resetBattleStatus(state) {
      state.currentPartyMemberIndex = 0;
      state.selectedCommands = [];
      state.selectedEnemies= [];
    },

  },
  actions: {
    // commitで引数を渡す場合
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    },
    setBattleSessionId({ commit }, sessionId) {
      commit('setBattleSessionId', sessionId)
    },
    setBattleStatus({ commit }, status) {
      commit('setBattleStatus', status);
    },
    setSelectedCommand({ commit }, { partyId, command }) {
      commit('setSelectedCommand', { partyId, command  });
    },
    clearSelectedCommands({ commit }) {
      commit('clearSelectedCommands')
    },

    setSelectedEnemy({ commit },  { partyId, enemyIndex }) {
      commit('setSelectedEnemy', { partyId, enemyIndex})
    },
    incrementPartyMemberIndex({commit}) {
      commit('incrementPartyMemberIndex')
    },
    // resetPartyMemberIndex({commit}) {
    //   commit('resetPartyMemberIndex')
    // },

    resetBattleStatus({ commit }) {
      commit('resetBattleStatus');
    },

  },
});
