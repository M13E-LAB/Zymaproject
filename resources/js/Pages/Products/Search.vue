<template>
  <div class="container">
    <h1>Recherche de Produits</h1>
    <ProductSearch />
  </div>
</template>

<script>
import ProductSearch from '@/components/ProductSearch.vue'

export default {
  name: 'ProductsSearch',
  components: {
    ProductSearch
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