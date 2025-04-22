<style>
/*
 * 画面を大きく作りすぎたので、scaleで調整している
 * scaleはbootstrapと相性が悪く、scaleを使用すると、bootstrapのmodal-backdropの優先度が崩れてしまう。
 * 対応するため、modalを使用する際はVue3のTeleportを使用して、bodyの外にmodalを出している。
 * 
*/
.app-root {
    transform: scale(0.85);
}

.modal {
    transform: scale(0.85);
}

.main-screen {
    background-color: rgb(255, 255, 255); 
    border: 1px solid black; 
    /* 4:3 */
    min-width: 1280px;
    min-height: 720px; 
    position: relative;
}

.sub-sucreen-text-space {
    height: 120px;
    border: 1px solid rgba(0, 0, 0, .5);
}

.sub-sucreen-main-space {
    height: 500px;
    border: 1px solid rgba(0, 0, 0, .5);
    overflow-y: scroll;
}



</style>

<template>
    <div class="app-root">
        <!-- titleなど? 現状デバッグ要素の表示 -->
        <div class="container" style="background-color: whitesmoke; border: 1px solid black; min-width: 1280px">
            <div class="row">
                <div class="col-12" style="text-align: center;">
                    <!-- メニュー: ステータス画面デバッグ  -->
                    <h4>
                    App.vue
                      <!-- <small style="font-size: 12px;">{{ screen.current }}.{{ status.status }} 戦闘状況: {{ battleCommands }}</small> -->
                    </h4>
                </div>
            </div>
        </div>
        
        <!-- メイン画面 4:3 -->
        <div class="container main-screen">
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    import { mapState } from 'vuex';
    export default {
        data() { // script内で使用する変数を定義する。
            return {}
        },
        // メソッドを定義できる(算出プロパティ)。キャッシュが効くので頻繁に再利用する処理を書く
        // vuexに存在するmapStateメソッドでstore.jsで定義したstate.screen.currentを取得。thisで参照できるようになる。
        computed: { 
            ...mapState(['screen']), // state.screen 全体を取得
            ...mapState({
                status: state => state.menu.status,
                battleCommands: myState => myState.battle.selectedCommands, // 検証 myStateで定義しても問題なく取得できるかチェック
            }),
        },
        created() { // DOMに依存しない処理を書く(state処理など。)
        },
        mounted() { // DOMがレンダリングされた後に必要な処理を書く(element取得など。)
            console.log('app.vue', this.screen.current);
        },
        methods: { // メソッド定義できる。結果を再利用しないメソッドなどを書く。
        }
    }
</script>
