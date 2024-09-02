import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    // 現在のメイン画面の状態 'title', 'menu', 'battle'
    currentScreen: 'title',
    // 'battle'状態のサブステータス
    // 'encount', 'command', 'enemySelect', 'exec', 'result'
    battleStatus: 'encount',
    // どのコマンドを選択したのか、さらに状態を管理する
    selectedCommands: [],
    selectedEnemies: [], 
    currentPartyMemberIndex: 0,
  },
  mutations: {
    setScreen(state, screen) {
      state.currentScreen = screen;
    },
    setBattleStatus(state, status) {
      state.battleStatus = status;
    },
    setSelectedCommand(state, { partyMember, command }) {
      state.selectedCommands.push({ partyMember, command });
      // この時点でコマンド状態: 
      // [ { "partyMember": "カア", "command": "ATTACK" } ]
    },
    clearSelectedCommands(state) {
      state.selectedCommands = {};
    },

    setSelectedEnemy(state, { partyMember, enemyIndex }) {
      const commandIndex = state.selectedCommands.findIndex(c => c.partyMember === partyMember);
      // 下と同じ意味
      // const commandIndex = state.selectedCommands.findIndex(function(c) {
      //   return c.partyMember === partyMember;
      // });
      if (commandIndex !== -1) {
        state.selectedCommands[commandIndex].enemyIndex = enemyIndex;
        // この時点でのコマンド状態:
        // [ { "partyMember": "カア", "command": "ATTACK", "enemyIndex": "1" } ]
        // 3人選んだ状態だと下記。
        // [
        //  { "partyMember": "カア", "command": "ATTACK", "enemyIndex": 0 },
        //  { "partyMember": "メイジちゃん", "command": "ATTACK", "enemyIndex": 1 }, 
        //  { "partyMember": "パラ", "command": "ATTACK", "enemyIndex": 1 } 
        // ]
      }
    },
    incrementPartyMemberIndex(state) {
      state.currentPartyMemberIndex += 1;
    },
    // resetPartyMemberIndex(state) {
    //   state.currentPartyMemberIndex = 0;
    // },
    resetBattleStatus(state) {
      state.currentPartyMemberIndex = 0;
      state.selectedCommands = [];
      state.selectedEnemies= [];
    }

  },
  actions: {
    // commitで引数を渡す場合
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    },
    setBattleStatus({ commit }, status) {
      commit('setBattleStatus', status);
    },
    setSelectedCommand({ commit }, { partyMember, command }) {
      commit('setSelectedCommand', { partyMember, command  });
    },
    clearSelectedCommands({ commit }) {
      commit('clearSelectedCommands')
    },

    setSelectedEnemy({ commit },  { partyMember, enemyIndex }) {
      commit('setSelectedEnemy', { partyMember, enemyIndex})
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
