<template>
    <div class="news-carousel">
      <div class="carousel-container" ref="newsCarousel">
        <div class="carousel-slide" v-for="article in articles" :key="article.id">
          <div class="news-card animate-hover">
            <div class="news-card-img">
              <img :src="article.image" :alt="article.title" />
            </div>
            <div class="news-card-content">
              <h4>{{ article.title }}</h4>
              <div class="news-meta">
                <span class="icon-calendar">📅</span>
                <span>{{ article.date }}</span>
              </div>
              <p>{{ article.excerpt }}</p>
              <a href="#" class="read-more">Lire plus →</a>
            </div>
          </div>
        </div>
      </div>
      <div class="carousel-controls">
        <button @click="moveCarousel('prev')" class="carousel-control prev">❮</button>
        <button @click="moveCarousel('next')" class="carousel-control next">❯</button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps({
    articles: {
      type: Array,
      required: true,
      default: () => []
    }
  })
  
  const newsCarousel = ref(null)
  
  const moveCarousel = (direction) => {
    const carousel = newsCarousel.value
    if (!carousel) return
    
    const slideWidth = carousel.querySelector('.carousel-slide')?.offsetWidth || 0
    
    if (direction === 'next') {
      carousel.scrollBy({ left: slideWidth, behavior: 'smooth' })
    } else {
      carousel.scrollBy({ left: -slideWidth, behavior: 'smooth' })
    }
  }
  </script>
  
  <style scoped>
  /* News Carousel */
  .news-carousel {
    position: relative;
    margin-bottom: 1.2rem;
  }
  
  .carousel-container {
    display: flex;
    overflow-x: auto;
    scroll-snap-type: x mandatory;
    scrollbar-width: none;
    -ms-overflow-style: none;
    gap: 1rem;
    padding: 0.5rem 0;
    -webkit-overflow-scrolling: touch;
  }
  
  .carousel-container::-webkit-scrollbar {
    display: none;
  }
  
  .carousel-slide {
    flex: 0 0 calc(33.333% - 0.67rem);
    scroll-snap-align: start;
  }
  
  .carousel-controls {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 0.8rem;
  }
  
  .carousel-control {
    background-color: #16a34a;
    color: white;
    border: none;
    width: 25px;
    height: 25px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.3s ease;
  }
  
  .carousel-control:hover {
    background-color: var(--primary-dark);
    transform: scale(1.1);
  }
  
  .news-card {
    border: none;
    border-radius: 6px;
    overflow: hidden;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    background-color: white;
    height: 100%;
  }
  
  .news-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .news-card-img img {
    width: 100%;
    height: 120px;
    object-fit: cover;
    transition: transform 0.5s ease;
  }
  
  .news-card:hover .news-card-img img {
    transform: scale(1.05);
  }
  
  .news-card-content {
    padding: 0.8rem;
  }
  
  .news-card-content h4 {
    margin-bottom: 0.4rem;
    font-size: 0.9rem;
    color: #16a34a;
  }
  
  .news-meta {
    display: flex;
    align-items: center;
    color: var(--text-light);
    font-size: 0.75rem;
    margin-bottom: 0.6rem;
  }
  
  .icon-calendar {
    margin-right: 0.3rem;
  }
  
  .news-card-content p {
    font-size: 0.8rem;
    color: var(--text-secondary);
    margin-bottom: 0.6rem;
    line-height: 1.4;
  }
  
  .read-more {
    display: flex;
    align-items: center;
    margin-top: 0.6rem;
    font-weight: 600;
    font-size: 0.75rem;
    color: #16a34a;
    text-decoration: none;
    transition: color 0.3s ease;
  }
  
  .read-more:hover {
    color: var(--primary-dark);
    text-decoration: underline;
  }
  
  .animate-hover {
    transition: all 0.3s ease;
  }
  
  .animate-hover:hover {
    transform: translateY(-3px);
  }
  
  /* Responsive */
  @media (max-width: 768px) {
    .carousel-slide {
      flex: 0 0 calc(100% - 1rem);
    }
  }
  
  @media (max-width: 576px) {
    .carousel-slide {
      flex: 0 0 100%;
    }
    
    .carousel-container {
      gap: 0.5rem;
    }
  }
  </style>
  