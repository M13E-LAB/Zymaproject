<template>
  <div class="shopping-list">
    <h2>Liste de Courses</h2>
    
    <div class="search-container">
      <div class="autocomplete">
        <input 
          type="text" 
          v-model="searchQuery" 
          placeholder="Rechercher un produit..."
          @input="handleSearch"
          @focus="showSuggestions = true"
          @blur="handleBlur"
        >
        <div v-if="showSuggestions && suggestions.length > 0" class="suggestions">
          <div 
            v-for="(suggestion, index) in suggestions" 
            :key="index"
            class="suggestion-item"
            @mousedown="selectSuggestion(suggestion)"
          >
            {{ suggestion.name }}
          </div>
        </div>
      </div>
      <button @click="addItem" class="add-btn">
        <i class="fas fa-plus"></i> Ajouter
      </button>
    </div>
    
    <div class="list-container">
      <transition-group name="list" tag="div" class="items-list">
        <div v-for="(item, index) in items" :key="item.id" class="item">
          <input 
            type="checkbox" 
            v-model="item.checked"
            @change="updateItem(index)"
          >
          <span :class="{ 'checked': item.checked }">{{ item.name }}</span>
          <button @click="removeItem(index)" class="delete-btn">
            <i class="fas fa-times"></i>
          </button>
        </div>
      </transition-group>
      
      <div v-if="items.length === 0" class="empty-list">
        <i class="fas fa-shopping-cart"></i>
        <p>Votre liste de courses est vide</p>
      </div>
    </div>

    <div v-if="recommendation" class="recommendation">
      <h3>Recommandation</h3>
      <div class="recommendation-card">
        <img :src="recommendation.image_url" :alt="recommendation.name">
        <div class="recommendation-info">
          <h4>{{ recommendation.name }}</h4>
          <p>{{ recommendation.description }}</p>
          <button @click="addRecommendation" class="add-recommendation-btn">
            Ajouter cette recommandation
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ShoppingList',
  data() {
    return {
      searchQuery: '',
      items: [],
      suggestions: [],
      showSuggestions: false,
      recommendation: null,
      searchTimeout: null
    }
  },
  methods: {
    async handleSearch() {
      clearTimeout(this.searchTimeout)
      this.searchTimeout = setTimeout(async () => {
        if (!this.searchQuery.trim()) {
          this.suggestions = []
          return
        }
        
        try {
          const response = await fetch(`/api/products/suggestions?q=${encodeURIComponent(this.searchQuery)}`)
          const data = await response.json()
          this.suggestions = data.slice(0, 5) // Limite à 5 suggestions
        } catch (err) {
          console.error('Erreur lors de la recherche des suggestions:', err)
        }
      }, 300)
    },
    
    handleBlur() {
      setTimeout(() => {
        this.showSuggestions = false
      }, 200)
    },
    
    selectSuggestion(suggestion) {
      this.searchQuery = suggestion.name
      this.showSuggestions = false
      this.addItem()
    },
    
    async addItem() {
      if (!this.searchQuery.trim()) return
      
      const newItem = {
        id: Date.now(),
        name: this.searchQuery.trim(),
        checked: false
      }
      
      this.items.push(newItem)
      this.searchQuery = ''
      this.saveList()
      
      // Jouer le son d'ajout
      const audio = new Audio('/sounds/add-to-cart.mp3')
      audio.play()
      
      // Obtenir une recommandation
      await this.getRecommendation(newItem.name)
    },
    
    async getRecommendation(productName) {
      try {
        const response = await fetch(`/api/recommendations?search_term=${encodeURIComponent(productName)}`)
        const data = await response.json()
        if (data.length > 0) {
          this.recommendation = data[0]
        }
      } catch (err) {
        console.error('Erreur lors de la récupération de la recommandation:', err)
      }
    },
    
    addRecommendation() {
      if (!this.recommendation) return
      
      const newItem = {
        id: Date.now(),
        name: this.recommendation.name,
        checked: false
      }
      
      this.items.push(newItem)
      this.recommendation = null
      this.saveList()
      
      // Jouer le son d'ajout
      const audio = new Audio('/sounds/add-to-cart.mp3')
      audio.play()
    },
    
    removeItem(index) {
      this.items.splice(index, 1)
      this.saveList()
    },
    
    updateItem(index) {
      this.saveList()
    },
    
    saveList() {
      localStorage.setItem('shoppingList', JSON.stringify(this.items))
    },
    
    loadList() {
      const savedList = localStorage.getItem('shoppingList')
      if (savedList) {
        this.items = JSON.parse(savedList)
      }
    }
  },
  mounted() {
    this.loadList()
  }
}
</script>

<style scoped>
.shopping-list {
  max-width: 800px;
  margin: 0 auto;
  padding: 20px;
}

.search-container {
  display: flex;
  gap: 10px;
  margin-bottom: 30px;
  position: relative;
}

.autocomplete {
  flex: 1;
  position: relative;
}

input[type="text"] {
  width: 100%;
  padding: 12px;
  border: 2px solid #ddd;
  border-radius: 8px;
  font-size: 16px;
  transition: border-color 0.3s;
}

input[type="text"]:focus {
  border-color: #4CAF50;
  outline: none;
}

.suggestions {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 1px solid #ddd;
  border-radius: 8px;
  margin-top: 5px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  z-index: 1000;
}

.suggestion-item {
  padding: 10px 15px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.suggestion-item:hover {
  background-color: #f5f5f5;
}

.add-btn {
  padding: 12px 24px;
  background-color: #4CAF50;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 16px;
  display: flex;
  align-items: center;
  gap: 5px;
  transition: background-color 0.3s;
}

.add-btn:hover {
  background-color: #45a049;
}

.list-container {
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  overflow: hidden;
}

.items-list {
  padding: 10px;
}

.item {
  display: flex;
  align-items: center;
  padding: 15px;
  border-bottom: 1px solid #eee;
  background: white;
}

.item:last-child {
  border-bottom: none;
}

.item span {
  flex: 1;
  margin: 0 15px;
  font-size: 16px;
}

.checked {
  text-decoration: line-through;
  color: #888;
}

.delete-btn {
  background-color: #ff4444;
  color: white;
  border: none;
  border-radius: 4px;
  padding: 8px 12px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.delete-btn:hover {
  background-color: #cc0000;
}

.empty-list {
  text-align: center;
  padding: 40px;
  color: #666;
}

.empty-list i {
  font-size: 48px;
  margin-bottom: 15px;
  color: #ddd;
}

/* Animations */
.list-enter-active,
.list-leave-active {
  transition: all 0.5s ease;
}

.list-enter-from {
  opacity: 0;
  transform: translateX(30px);
}

.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

/* Recommandation */
.recommendation {
  margin-top: 30px;
  padding: 20px;
  background: #f8f9fa;
  border-radius: 12px;
}

.recommendation h3 {
  margin-bottom: 15px;
  color: #333;
}

.recommendation-card {
  display: flex;
  gap: 20px;
  background: white;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.recommendation-card img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 4px;
}

.recommendation-info {
  flex: 1;
}

.recommendation-info h4 {
  margin: 0 0 10px 0;
  color: #333;
}

.recommendation-info p {
  color: #666;
  margin-bottom: 15px;
  font-size: 14px;
}

.add-recommendation-btn {
  background-color: #2196F3;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 4px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.add-recommendation-btn:hover {
  background-color: #1976D2;
}
</style> 