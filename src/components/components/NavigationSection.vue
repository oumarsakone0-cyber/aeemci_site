<template>
  <section class="navigation-section">
    <div class="container">
      <div class="nav-buttons">
        <button 
          v-for="navItem in navigationItems" 
          :key="navItem.id"
          :class="['nav-button', { active: activeNavItem === navItem.id }]"
          @click="$emit('update:activeNavItem', navItem.id)"
        >
          <span class="nav-icon">{{ navItem.icon }}dd</span>
          <span class="nav-text">{{ navItem.name }}ff</span>
        </button>
      </div>
      <div class="separator"></div>
    </div>
  </section>
</template>

<script setup>
import { ref, defineProps, defineEmits } from 'vue'

const props = defineProps({
  activeNavItem: { type: String, default: 'accueil' },
  navigationItems: { type: Array, required: true }
})

const emit = defineEmits(['update:activeNavItem'])
</script>

<style scoped>
.navigation-section {
  padding: 3rem 0;
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
}

.nav-buttons {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1.5rem;
  max-width: 1200px;
  margin: 0 auto;
}

.nav-button {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 2rem 1rem;
  background: white;
  border: 2px solid var(--line);
  border-radius: 0;
  cursor: pointer;
  transition: all 0.3s ease;
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--ink-soft);
  text-align: center;
  box-shadow: var(--shadow);
}

.nav-button:hover,
.nav-button.active {
  border-color: var(--green);
  background: linear-gradient(135deg, var(--green-mist), var(--green-mist));
  color: var(--green);
  transform: translateY(-6px) scale(1.02);
  box-shadow: var(--shadow);
}

.nav-icon { 
  font-size: 2rem; 
  margin-bottom: 0.5rem; 
}

.nav-text { 
  font-size: 1rem; 
  font-weight: 600; 
}

.separator {
  height: 3px;
  background: linear-gradient(90deg, transparent, var(--green), transparent);
  margin: 2.5rem auto 0;
  max-width: 500px;
  border-radius: 0;
}

/* Enhanced responsive design for better mobile adaptation */
@media (max-width: 1200px) {
  .nav-buttons {
    grid-template-columns: repeat(4, 1fr);
    gap: 1.2rem;
  }
}

@media (max-width: 1024px) {
  .navigation-section {
    padding: 2rem 0;
  }
  
  .nav-buttons {
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
  }
  
  .nav-button {
    padding: 1.5rem 0.8rem;
  }
  
  .nav-icon {
    font-size: 1.8rem;
  }
}

/* Force 2 columns layout on mobile screens */
@media (max-width: 768px) {
  .navigation-section {
    padding: 1.5rem 0;
  }
  
  .nav-buttons {
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 0.8rem;
    padding: 0 1rem;
  }
  
  .nav-button {
    padding: 1.2rem 0.6rem;
    border-radius: 0;
  }
  
  .nav-icon {
    font-size: 1.6rem;
    margin-bottom: 0.3rem;
  }
  
  .nav-text {
    font-size: 0.9rem;
  }
  
  .separator {
    margin: 1.5rem auto 0;
    max-width: 300px;
  }
}

/* Maintain 2 columns even on smallest screens */
@media (max-width: 480px) {
  .nav-buttons {
    grid-template-columns: repeat(2, 1fr) !important;
    gap: 0.6rem;
  }
  
  .nav-button {
    padding: 1rem 0.5rem;
    border-radius: 0;
  }
  
  .nav-icon {
    font-size: 1.4rem;
    margin-bottom: 0.3rem;
  }
  
  .nav-text {
    font-size: 0.8rem;
  }
}
</style>
