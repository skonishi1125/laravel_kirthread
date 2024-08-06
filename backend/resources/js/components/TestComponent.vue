<template>
  <div class="test-component">
    <h1>{{ message }}</h1>
    <button @click="updateMessage">Click me</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      message: 'Hello, Laravel and Vue!'
    };
  },
  methods: {
    updateMessage() {
      this.message = 'You clicked the button!';
    }
  }
};
</script>

<style scoped>
.test-component {
  text-align: center;
  margin-top: 50px;
}
button {
  padding: 10px 20px;
  font-size: 16px;
}
</style>
