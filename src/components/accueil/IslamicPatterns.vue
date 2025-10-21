<template>
    <div class="islamic-patterns">
      <div v-for="n in 8" :key="`star-${n}`" class="islamic-pattern star" :style="{ 
        left: `${Math.random() * 100}%`, 
        top: `${Math.random() * 100}%`,
        animationDelay: `${Math.random() * 5}s`,
        animationDuration: `${15 + Math.random() * 20}s`
      }"></div>
      
      <div v-for="n in 5" :key="`crescent-${n}`" class="islamic-pattern crescent" :style="{ 
        left: `${Math.random() * 100}%`, 
        top: `${Math.random() * 100}%`,
        animationDelay: `${Math.random() * 5}s`,
        animationDuration: `${15 + Math.random() * 20}s`
      }"></div>
      
      <div v-for="n in 10" :key="`geometric-${n}`" class="islamic-pattern geometric" :style="{ 
        left: `${Math.random() * 100}%`, 
        top: `${Math.random() * 100}%`,
        animationDelay: `${Math.random() * 5}s`,
        animationDuration: `${15 + Math.random() * 20}s`,
        transform: `rotate(${Math.random() * 360}deg)`
      }"></div>
    </div>
  </template>
  
  <style scoped>
  /* Motifs islamiques animés */
  .islamic-patterns {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: hidden;
  }
  
  .islamic-pattern {
    position: absolute;
    opacity: 0.15;
  }
  
  .islamic-pattern.star {
    width: 30px;
    height: 30px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-diagonal 25s infinite linear;
  }
  
  .islamic-pattern.crescent {
    width: 25px;
    height: 25px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 1 0 0 18 9 9 0 0 0 0-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-horizontal 30s infinite linear;
  }
  
  .islamic-pattern.geometric {
    width: 40px;
    height: 40px;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0 L100 50 L50 100 L0 50 Z'/%3E%3C/svg%3E");
    background-size: contain;
    animation: float-vertical 20s infinite linear;
  }
  
  @keyframes float-diagonal {
    0% {
      transform: translate(0, 0) rotate(0deg);
    }
    25% {
      transform: translate(100px, 100px) rotate(90deg);
    }
    50% {
      transform: translate(200px, 200px) rotate(180deg);
    }
    75% {
      transform: translate(100px, 300px) rotate(270deg);
    }
    100% {
      transform: translate(0, 400px) rotate(360deg);
    }
  }
  
  @keyframes float-horizontal {
    0% {
      transform: translate(-100px, 0) rotate(0deg);
    }
    50% {
      transform: translate(calc(100vw + 100px), 0) rotate(180deg);
    }
    100% {
      transform: translate(-100px, 0) rotate(360deg);
    }
  }
  
  @keyframes float-vertical {
    0% {
      transform: translate(0, -100px) rotate(0deg);
    }
    50% {
      transform: translate(0, calc(100vh + 100px)) rotate(180deg);
    }
    100% {
      transform: translate(0, -100px) rotate(360deg);
    }
  }
  </style>
  