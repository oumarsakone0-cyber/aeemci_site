<template>
  <div class="blob-container">
    <div 
      v-for="blob in blobs" 
      :key="blob.id"
      :class="['blob', `blob-${blob.id}`]"
      :style="blob.style"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const blobs = ref([
  {
    id: 1,
    style: {
      top: '-60px',
      right: '-60px',
      width: '320px',
      height: '320px',
      background: 'linear-gradient(135deg, #22c55e, #16a34a, #15803d)',
      animationDelay: '0s'
    }
  },
  {
    id: 2,
    style: {
      bottom: '-80px',
      left: '-80px',
      width: '380px',
      height: '380px',
      background: 'linear-gradient(135deg, #10b981, #059669, #047857)',
      animationDelay: '1.5s'
    }
  },
  {
    id: 3,
    style: {
      top: '15%',
      left: '8%',
      width: '280px',
      height: '280px',
      background: 'linear-gradient(135deg, #14b8a6, #0d9488, #0f766e)',
      animationDelay: '3s'
    }
  },
  {
    id: 4,
    style: {
      top: '55%',
      right: '15%',
      width: '240px',
      height: '240px',
      background: 'linear-gradient(135deg, #84cc16, #65a30d, #4d7c0f)',
      animationDelay: '4.5s'
    }
  },
  {
    id: 5,
    style: {
      bottom: '25%',
      left: '25%',
      width: '300px',
      height: '300px',
      background: 'linear-gradient(135deg, #06b6d4, #0891b2, #0e7490)',
      animationDelay: '6s'
    }
  },
  {
    id: 6,
    style: {
      top: '40%',
      right: '45%',
      width: '200px',
      height: '200px',
      background: 'linear-gradient(135deg, #8b5cf6, #7c3aed, #6d28d9)',
      animationDelay: '7.5s'
    }
  }
])

onMounted(() => {
  // Animation démarrée automatiquement via CSS
})
</script>

<style scoped>
.blob-container {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
  z-index: -1;
}

.blob {
  position: absolute;
  border-radius: 50%;
  filter: blur(50px);
  mix-blend-mode: multiply;
  opacity: 0.7;
  animation: floatingBlob 8s ease-in-out infinite;
  will-change: transform;
}

.blob-1 {
  animation-duration: 8s;
}

.blob-2 {
  animation-duration: 9s;
}

.blob-3 {
  animation-duration: 7s;
}

.blob-4 {
  animation-duration: 10s;
}

.blob-5 {
  animation-duration: 8.5s;
}

.blob-6 {
  animation-duration: 9.5s;
}

@keyframes floatingBlob {
  0%, 100% {
    transform: translate(0, 0) scale(1) rotate(0deg);
  }
  25% {
    transform: translate(40px, -60px) scale(1.15) rotate(90deg);
  }
  50% {
    transform: translate(-30px, 40px) scale(0.85) rotate(180deg);
  }
  75% {
    transform: translate(50px, 30px) scale(1.05) rotate(270deg);
  }
}

/* Animation alternative pour certains blobs */
.blob-2, .blob-4, .blob-6 {
  animation-name: floatingBlobReverse;
}

@keyframes floatingBlobReverse {
  0%, 100% {
    transform: translate(0, 0) scale(1) rotate(360deg);
  }
  25% {
    transform: translate(-35px, 45px) scale(0.9) rotate(270deg);
  }
  50% {
    transform: translate(45px, -35px) scale(1.2) rotate(180deg);
  }
  75% {
    transform: translate(-25px, -45px) scale(0.95) rotate(90deg);
  }
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .blob {
    filter: blur(30px);
    opacity: 0.5;
  }
  
  .blob-1, .blob-2 {
    width: 250px !important;
    height: 250px !important;
  }
  
  .blob-3, .blob-4, .blob-5, .blob-6 {
    width: 180px !important;
    height: 180px !important;
  }
}

@media (prefers-reduced-motion: reduce) {
  .blob {
    animation: none;
  }
}
</style>