<style scoped>
  .action-link {
    cursor: pointer;
  }

  .m-b-none {
    margin-bottom: 0;
  }
</style>

<template>
  <div class="panel-heading">
    <div style="display: flex; justify-content: space-between; align-items: center;">
      <span>
        Purchases
      </span>
      
      <a class="action-link">
        Create New Purchase
      </a>
    </div>
  </div>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
