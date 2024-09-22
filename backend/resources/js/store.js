import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    // メイン画面の状態 'title', 'menu', 'battle'
    currentScreen: 'title',
    battleStatus: 'start', // 'battle'状態のサブステータス 'start' 'encount', 'command', 'enemySelect', 'partySelect', 'exec', 'outputLog', 'resultWin', 'resultLose', 'escape'
    selectedCommands: [], // 味方の選択コマンド
    selectedEnemies: [],  // コマンドで選択した敵
    currentPartyMemberIndex: 0, // どの味方のコマンドを選択しているかのindex [0]か[1]か[2]
    clearStage: '', // 1-1, 1-2, 2-1という文字列で記入される
    battleSessionId: '', // 戦闘データのセッション
  },
  mutations: {
    setScreen(state, screen) {
      state.currentScreen = screen;
    },
    setClearStage(state, stage) {
      state.clearStage = stage;
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
    setSelectedCommandSkill(state, { partyId, command, skillId }) {
      state.selectedCommands.push({ partyId, command, skillId });
    },
    // todo: ITEM選択
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
        /* 
          1人選んだ時点でのコマンド状態:
          [ { "partyId": 1, "command": "ATTACK", "enemyIndex": 1 } ]
          3人選んだ状態だと下記。
          [ 
            { "partyId": 1, "command": "ATTACK", "enemyIndex": 1 },
            { "partyId": 2, "command": "ATTACK", "enemyIndex": 1 },
            { "partyId": 3, "command": "ATTACK", "enemyIndex": 1 } 
          ]
        */
      }
    },
    // playerIndexは、jsonでエンカウント時に作成したパーティの並び順
    setSelectedParty(state, { partyId, playerIndex }) {
      const commandIndex = state.selectedCommands.findIndex(c => c.partyId === partyId);
      if (commandIndex !== -1) {
        state.selectedCommands[commandIndex].playerIndex = playerIndex;
      /* 
        [ 
          { "partyId": 1, "command": "SKILL", "playerIndex": 1 },
          { "partyId": 2, "command": "SKILL", "playerIndex": 1 },
          { "partyId": 3, "command": "SKILL", "playerIndex": 1 } 
        ]
      */
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

    // 戦闘を途中で終了する(逃げる)場合は初期ステータスに全て戻す
    // また別途DB側のbattle_statesを削除する
    resetAllBattleStatus(state) {
      state.currentPartyMemberIndex = 0;
      state.selectedCommands = [];
      state.selectedEnemies= [];
      state.battleStatus = 'escape'; 
      state.battleSessionId =  '';
    }

  },
  actions: {
    // commitで引数を渡す場合
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    },
    setClearStage({ commit }, stage) {
      commit('setClearStage', stage)
    },
    setBattleSessionId({ commit }, sessionId) {
      commit('setBattleSessionId', sessionId)
    },
    setBattleStatus({ commit }, status) {
      commit('setBattleStatus', status);
    },
    // ATTACK, DEFENCE選択
    setSelectedCommand({ commit }, { partyId, command }) {
      commit('setSelectedCommand', { partyId, command  });
    },
    // SKILL選択
    setSelectedCommandSkill({ commit }, { partyId, command, skillId }) {
      commit('setSelectedCommandSkill', { partyId, command, skillId  });
    },

    clearSelectedCommands({ commit }) {
      commit('clearSelectedCommands')
    },

    setSelectedEnemy({ commit },  { partyId, enemyIndex }) {
      commit('setSelectedEnemy', { partyId, enemyIndex})
    },
    setSelectedParty({ commit },  { partyId, playerIndex }) {
      commit('setSelectedParty', { partyId, playerIndex})
    },
    incrementPartyMemberIndex({commit}) {
      commit('incrementPartyMemberIndex')
    },

    resetBattleStatus({ commit }) {
      commit('resetBattleStatus');
    },

    resetAllBattleStatus({ commit }) {
      commit('resetAllBattleStatus');
    },

  },
});
