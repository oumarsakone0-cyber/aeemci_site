import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import tailwindcss from '@tailwindcss/vite'
import { resolve } from 'path'

export default defineConfig({
  plugins: [
    vue(), 
    tailwindcss()
  ],
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src')
    }
  },
  server: {
    proxy: {
      '/proxy-upload': {
        target: 'https://api.aeemci-ce.ci',
        changeOrigin: true,
        secure: true,
        rewrite: () => '/cloud/upload.php',
      }
    }
  }
})