import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router'
import './assets/style.css'
import { installMotion } from './composables/useReveal'

createApp(App)
  .use(router)
  .mount('#app')

// Motifs + révélations au défilement, pour toute l'application.
installMotion()
