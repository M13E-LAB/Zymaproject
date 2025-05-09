<template>
  <div class="product-search">
    <h2>Recherche de Produits</h2>
    <div class="search-container">
      <input 
        type="text" 
        v-model="searchQuery" 
        placeholder="Rechercher un produit..."
        @input="debounceSearch"
      >
      <button @click="searchProducts" class="search-btn">Rechercher</button>
    </div>

    <div v-if="loading" class="loading">
      <div class="spinner"></div>
      <p>Recherche de recommandations...</p>
    </div>

    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else-if="recommendations.length" class="recommendations">
      <div v-for="(product, index) in recommendations" :key="product.id" class="product-card">
        <div class="product-image">
          <img :src="product.image_url" :alt="product.name">
        </div>
        
        <div class="product-info">
          <h3>{{ product.name }}</h3>
          <p class="description">{{ product.description }}</p>
          
          <div class="scores">
            <div class="score-item">
              <span>Santé</span>
              <div class="progress-bar">
                <div :class="['progress', getScoreClass(product.health_score)]" 
                     :style="{ width: product.health_score + '%' }">
                </div>
              </div>
              <span class="score-value">{{ product.health_score }}%</span>
            </div>

            <div class="score-item">
              <span>Prix</span>
              <div class="progress-bar">
                <div :class="['progress', getScoreClass(product.price_score)]" 
                     :style="{ width: product.price_score + '%' }">
                </div>
              </div>
              <span class="score-value">{{ product.price_score }}%</span>
            </div>

            <div class="score-item">
              <span>Compromis</span>
              <div class="progress-bar">
                <div :class="['progress', getScoreClass(product.compromise_score)]" 
                     :style="{ width: product.compromise_score + '%' }">
                </div>
              </div>
              <span class="score-value">{{ product.compromise_score }}%</span>
            </div>
          </div>

          <div class="actions">
            <button @click="addToShoppingList(product)" class="add-btn">
              <i class="fas fa-plus"></i> Ajouter
            </button>
            <button @click="showDetails(product)" class="details-btn">
              <i class="fas fa-info-circle"></i> Détails
            </button>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="no-results">
      <p>Aucune recommandation trouvée</p>
      <p class="sub-text">Essayez une autre recherche</p>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProductSearch',
  data() {
    return {
      searchQuery: '',
      recommendations: [],
      loading: false,
      error: null,
      searchTimeout: null
    }
  },
  methods: {
    debounceSearch() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(() => {
        this.searchProducts()
      }, 300)
    },
    async searchProducts() {
      if (!this.searchQuery.trim()) return
      
      this.loading = true
      this.error = null
      
      try {
        const response = await fetch(`/api/recommendations?search_term=${encodeURIComponent(this.searchQuery)}`)
        if (!response.ok) throw new Error('Erreur lors de la recherche')
        
        const data = await response.json()
        this.recommendations = data.slice(0, 3) // Limite à 3 recommandations
      } catch (err) {
        this.error = 'Erreur lors de la recherche des recommandations'
        console.error(err)
      } finally {
        this.loading = false
      }
    },
    getScoreClass(score) {
      if (score >= 70) return 'good'
      if (score >= 40) return 'medium'
      return 'bad'
    },
    addToShoppingList(product) {
      // Émettre un événement pour ajouter au panier
      this.$emit('add-to-list', product)
      // Jouer un son de succès
      const audio = new Audio('/sounds/add-to-cart.mp3')
      audio.play()
    },
    showDetails(product) {
      // Émettre un événement pour afficher les détails
      this.$emit('show-details', product)
    }
  }
}
</script>

<style scoped>
.product-search {
  padding: 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.search-container {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
}

input {
  flex: 1;
  padding: 12px;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.3s;
}

input:focus {
  border-color: #4CAF50;
  outline: none;
}

.search-btn {
  padding: 12px 24px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  transition: background-color 0.3s;
}

.search-btn:hover {
  background-color: #45a049;
}

.recommendations {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 30px;
  margin-top: 20px;
}

.product-card {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  transition: transform 0.3s;
}

.product-card:hover {
  transform: translateY(-5px);
}

.product-image {
  height: 200px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.product-info {
  padding: 20px;
}

.product-info h3 {
  margin: 0 0 10px 0;
  font-size: 18px;
  color: #333;
}

.description {
  color: #666;
  margin-bottom: 15px;
  font-size: 14px;
}

.scores {
  margin: 15px 0;
}

.score-item {
  display: flex;
  align-items: center;
  margin-bottom: 10px;
}

.score-item span {
  width: 80px;
  font-size: 14px;
  color: #666;
}

.progress-bar {
  flex: 1;
  height: 8px;
  background: #f0f0f0;
  border-radius: 4px;
  overflow: hidden;
  margin: 0 10px;
}

.progress {
  height: 100%;
  transition: width 0.3s ease;
}

.progress.good {
  background-color: #4CAF50;
}

.progress.medium {
  background-color: #FFC107;
}

.progress.bad {
  background-color: #F44336;
}

.score-value {
  width: 40px !important;
  text-align: right;
  font-weight: bold;
}

.actions {
  display: flex;
  gap: 10px;
  margin-top: 20px;
}

.add-btn, .details-btn {
  flex: 1;
  padding: 10px;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 5px;
  transition: background-color 0.3s;
}

.add-btn {
  background-color: #4CAF50;
  color: white;
}

.details-btn {
  background-color: #2196F3;
  color: white;
}

.add-btn:hover {
  background-color: #45a049;
}

.details-btn:hover {
  background-color: #1976D2;
}

.loading {
  text-align: center;
  padding: 40px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #4CAF50;
  border-radius: 50%;
  margin: 0 auto 20px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.error {
  text-align: center;
  color: #F44336;
  padding: 20px;
  background: #ffebee;
  border-radius: 8px;
  margin: 20px 0;
}

.no-results {
  text-align: center;
  padding: 40px;
  color: #666;
}

.sub-text {
  font-size: 14px;
  margin-top: 10px;
  color: #999;
}
</style> 