<template>
  <div class="container">
    <h1>Liste de Courses</h1>
    <ShoppingList />
  </div>
</template>

<script>
import ShoppingList from '@/components/ShoppingList.vue'

export default {
  name: 'ShoppingListPage',
  components: {
    ShoppingList
  }
}
</script>

<style scoped>
.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

h1 {
  margin-bottom: 30px;
  color: #333;
}
</style> 