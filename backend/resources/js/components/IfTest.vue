<template>
  <div>
    <p>iftestのコンポーネントです</p>
    <div v-if="checkFlag">
      <p>{{ checkFlag }}</p>
      <p>フラグは現在trueです</p>
    </div>
    <div v-else-if="checkFlag === 'false'"> <!-- !checkFlagとすると、nullの場合も条件一致する -->
      <p>{{ checkFlag }}</p>
      <p>フラグは現在falseです</p>
    </div>
    <div v-else>
      <p>{{ checkFlag }}</p> <!-- nullなので写らない -->
      <p>フラグ不明の状態です。</p>
    </div>
    
    <button @click="flagOn">フラグON</button>
    <button @click="flagOff">フラグOFF</button>
  </div>
</template>

<script>
export default {
    props: {

    },
    data() {
      return {
        checkFlag: null
      };
    },
    methods: {
      flagOn () {
        console.log('flag ON 直前のフラグ値: ', this.checkFlag);
        this.checkFlag = true;
      },

      flagOff () {
        console.log('flag OFF 直前のフラグ値: ', this.checkFlag);
        this.checkFlag = false;
      }

    }
};
</script>