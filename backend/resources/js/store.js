import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    // メイン画面の状態 'title', 'beginning', 'menu', 'battle'
    currentScreen: 'title',
    beginningStatus: 'start', // 'beginning状態のサブステータス 'start', 'prologue', 'setCharacter', '
      currentDisplayRoleIndex: 0, // 0-5. +1した値がrole_idになる。6になったら0に戻す。 -1になったら5に調整する。
      currentDecidedMemberIndex: 0, // 現在パーティのロールを選択しているかのindex
      selectedRoleInformations: [], // キャラ選択時に設定したパーティメンバーの情報

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
    setBeginningStatus(state, status) {
      state.beginningStatus = status;
    },
    incrementCurrentDisplayRoleIndex(state) { // "→"クリック
      state.currentDisplayRoleIndex += 1;
      if (state.currentDisplayRoleIndex > 5) {
        state.currentDisplayRoleIndex = 0;
      }
    },
    decrementCurrentDisplayRoleIndex(state) { // "←"クリック
      state.currentDisplayRoleIndex -= 1;
      if (state.currentDisplayRoleIndex < 0) {
        state.currentDisplayRoleIndex = 5;
      }
    },
     // キャラ設定中のメンバーのindex操作
    incrementCurrentDecidedMemberIndex(state) {
      state.currentDecidedMemberIndex += 1;
    },
    decrementDecidedMemberIndex(state) {
      state.currentDecidedMemberIndex -= 1;
    },
    // 選択の状態例: [ { "roleId": "1", "name": "スト" } ]
    setSelectedRoleInformation(state, { roleId, roleClassJapanese, partyName }) {
      state.selectedRoleInformations.push({ roleId, roleClassJapanese, partyName });
    },
    resetBeginningDecidedData(state) {
      state.selectedRoleInformations = [];
      state.currentDecidedMemberIndex = 0;
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
    setSelectedCommandItem(state, { partyId, command, itemId }) {
      state.selectedCommands.push({ partyId, command, itemId });
    },

    // RETURN選択
    resetSelectedCommands(state) {
      state.selectedCommands = [];
    },
    resetPartyMemberIndex(state) {
      state.currentPartyMemberIndex = 0;
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
    setBeginningStatus({ commit }, status) {
      commit('setBeginningStatus', status);
    },
    incrementCurrentDisplayRoleIndex({commit}) {
      commit('incrementCurrentDisplayRoleIndex')
    },
    decrementCurrentDisplayRoleIndex({commit}) {
      commit('decrementCurrentDisplayRoleIndex')
    },
    incrementCurrentDecidedMemberIndex({commit}) {
      commit('incrementCurrentDecidedMemberIndex')
    },
    decrementCurrentDecidedMemberIndex({commit}) {
      commit('decrementCurrentDecidedMemberIndex')
    },
    setSelectedRoleInformation({ commit }, { roleId, roleClassJapanese, partyName, }) {
      commit('setSelectedRoleInformation', { roleId, roleClassJapanese, partyName  });
    },
    resetBeginningDecidedData({ commit }) {
      commit('resetBeginningDecidedData');
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
    // ITEM選択
    setSelectedCommandItem({ commit }, { partyId, command, itemId }) {
      commit('setSelectedCommandItem', { partyId, command, itemId  });
    },

    // RETURN選択
    resetSelectedCommands({ commit }) {
      commit('resetSelectedCommands')
    },
    resetPartyMemberIndex({ commit }) {
      commit('resetPartyMemberIndex');
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
