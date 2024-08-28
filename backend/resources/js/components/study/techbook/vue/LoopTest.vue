<template>
  <div>
    <ul>
      <li v-for="task in tasks">{{ task }}</li>
    </ul>
    <input type="text" v-model="newTask" @keyup.enter="addTask">
    <button @click="addTask">Add Task</button>
  </div>
</template>

<script>
export default {
    props: {

    },
    data() {
      return {
        newTask: '',
        tasks:[]
      }
    },
    methods: {
      addTask() {
        this.tasks.push(this.newTask);
        this.newTask = '';
      }
    }
};
</script>