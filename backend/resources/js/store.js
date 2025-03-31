import { createStore } from 'vuex';

// 戦闘状態の管理
export default createStore({
  state: {
    // メイン画面
    screen: {
      current: 'title', // 'title', 'beginning', 'menu', 'battle'
    },
    beginning: {
      status: 'start', // 'beginning状態のサブステータス 'start', 'prologue', 'setCharacter', 'monologue'
      currentDisplayRoleIndex: 0, // 0-5. +1した値がrole_idになる。6になったら0に戻す。 -1になったら5に調整する。
      currentDecidedMemberIndex: 0, // 現在パーティのロールを選択しているかのindex
      selectedRoleInformations: [], // キャラ選択時に設定したパーティメンバーの情報
    },
    menu: {
      // ショップ画面
      shop: {
        status: 'start',  // 'start' 'buy' 'sell'とかかな。
      },
      // スキル|ステ振り画面
      status: {
        status: 'start',  // 'start', 'status', 'skill', 'updating'
        currentSelectedPartyMemberIndex: 0, // 味方画面の配列のインデックス 0, 1, 2
      }
    },
    // 戦闘画面
    battle: {
      status: 'start', // 'start' 'encount', 'command', 'enemySelect', 'partySelect', 'exec', 'outputLog', 'resultWin', 'resultLose', 'escaped'
      selectedCommands: [], // 味方の選択コマンド
      selectedEnemies: [],  // 味方が選択したEnemy情報
      currentPartyMemberIndex: 0, // どの味方のコマンドを選択しているかのindex [0]か[1]か[2]
      clearStage: '', // 1-1, 1-2, 2-1という文字列で記入される
      battleSessionId: '', // 戦闘データのセッション
    },


  },
  mutations: {
    // メイン画面
    setScreen(state, screen) {
      state.screen.current = screen;
    },

    // beginning サブステータス
    setBeginningStatus(state, status) {
      state.beginning.status = status;
    },
    incrementCurrentDisplayRoleIndex(state) { // "→"クリック
      state.beginning.currentDisplayRoleIndex += 1;
      if (state.beginning.currentDisplayRoleIndex > 5) {
        state.beginning.currentDisplayRoleIndex = 0;
      }
    },
    decrementCurrentDisplayRoleIndex(state) { // "←"クリック
      state.beginning.currentDisplayRoleIndex -= 1;
      if (state.beginning.currentDisplayRoleIndex < 0) {
        state.beginning.currentDisplayRoleIndex = 5;
      }
    },
     // キャラ設定中のメンバーのindex操作
    incrementCurrentDecidedMemberIndex(state) {
      state.beginning.currentDecidedMemberIndex += 1;
    },
    decrementDecidedMemberIndex(state) {
      state.beginning.currentDecidedMemberIndex -= 1;
    },
    // 選択の状態例: [ { "roleId": "1", "name": "スト" } ]
    setSelectedRoleInformation(state, { roleId, roleClassJapanese, partyName }) {
      state.beginning.selectedRoleInformations.push({ roleId, roleClassJapanese, partyName });
    },
    resetBeginningDecidedData(state) {
      state.beginning.selectedRoleInformations = [];
      state.beginning.currentDecidedMemberIndex = 0;
    },

    // menu 
    // ------------- ショップ画面 -------------
    setMenuShopStatus(state, status) {
      state.menu.shop.status = status;
    },

    // ------------- スキル|ステ振り -------------
    setMenuStatusStatus(state, status) {
      state.menu.status.status = status;
    },
    setCurrentSelectedPartyMemberIndex(state, number) {
      state.menu.status.currentSelectedPartyMemberIndex = number;
    },

    // battle サブステータス
    setClearStage(state, stage) {
      state.battle.clearStage = stage;
    },
    setBattleSessionId(state, sessionId) {
      state.battle.battleSessionId = sessionId;
    },
    setBattleStatus(state, status) {
      state.battle.status = status;
    },
    setSelectedCommand(state, { partyId, command }) {
      state.battle.selectedCommands.push({ partyId, command });
      // この時点でコマンド状態: 
      // [ { "partyId": "1", "command": "ATTACK" } ]
    },
    setSelectedCommandSkill(state, { partyId, command, skillId }) {
      state.battle.selectedCommands.push({ partyId, command, skillId });
    },
    // todo: ITEM選択
    setSelectedCommandItem(state, { partyId, command, itemId }) {
      state.battle.selectedCommands.push({ partyId, command, itemId });
    },

    // RETURN選択
    resetSelectedCommands(state) {
      state.battle.selectedCommands = [];
    },
    resetPartyMemberIndex(state) {
      state.battle.currentPartyMemberIndex = 0;
    },

    setSelectedEnemy(state, { partyId, enemyIndex }) {
      const commandIndex = state.battle.selectedCommands.findIndex(c => c.partyId === partyId);
      // 下と同じ意味
      // const commandIndex = state.selectedCommands.findIndex(function(c) {
      //   return c.partyId === partyId;
      // });
      if (commandIndex !== -1) {
        state.battle.selectedCommands[commandIndex].enemyIndex = enemyIndex;
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
      const commandIndex = state.battle.selectedCommands.findIndex(c => c.partyId === partyId);
      if (commandIndex !== -1) {
        state.battle.selectedCommands[commandIndex].playerIndex = playerIndex;
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
      state.battle.currentPartyMemberIndex += 1;
    },

    // コマンドが2週目以降続く場合、選択履歴をリセット
    resetBattleStatus(state) {
      state.battle.currentPartyMemberIndex = 0;
      state.battle.selectedCommands = [];
      state.battle.selectedEnemies= [];
    },

    // 戦闘を途中で終了する(逃げる)場合は初期ステータスに全て戻す
    // また別途DB側のbattle_statesを削除する
    resetAllBattleStatus(state) {
      state.battle.currentPartyMemberIndex = 0;
      state.battle.selectedCommands = [];
      state.battle.selectedEnemies= [];
      state.battle.status = 'escape'; 
      state.battle.battleSessionId =  '';
    }

  },
  actions: {
    // メイン画面
    setScreen({ commit }, screen) {
      commit('setScreen', screen);
    },

    // beginning サブステータス
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

    // menu
    // ------------- ショップ画面 -------------
    setMenuShopStatus({ commit }, status) {
      commit('setMenuShopStatus', status);
    },

    // ------------- スキル|ステ振り -------------
    setMenuStatusStatus({ commit }, status) {
      commit('setMenuStatusStatus', status);
    },
    setCurrentSelectedPartyMemberIndex({ commit }, number) {
      commit('setCurrentSelectedPartyMemberIndex', number);
    },

    // battle サブステータス
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
