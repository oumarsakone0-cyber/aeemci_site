<template>
  <div class="main-sections">
    <!-- Actualités -->
    <div class="content-section">
      <h2 class="section-title"><span class="title-icon">📰</span> Actualités</h2>
      <div class="articles-grid">
        <article v-for="article in actualites" :key="article.id" class="article-card">
          <div class="article-date">{{ article.date }}</div>
          <h3 class="article-title">{{ article.title }}</h3>
          <p class="article-excerpt">{{ article.excerpt }}</p>
          <a href="#" class="read-more">Lire la suite →</a>
        </article>
      </div>
    </div>

    <!-- Informations -->
    <div class="content-section">
      <h2 class="section-title"><span class="title-icon">ℹ️</span> Informations</h2>
      <div class="info-grid">
        <div v-for="info in informations" :key="info.id" class="info-card">
          <div class="info-icon">{{ info.icon }}</div>
          <div class="info-content">
            <h4 class="info-title">{{ info.title }}</h4>
            <p class="info-description">{{ info.description }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Activités -->
    <div class="content-section">
      <h2 class="section-title"><span class="title-icon">🎯</span> Activités</h2>
      <div class="activities-grid">
        <div v-for="activity in activites" :key="activity.id" class="activity-card">
          <div class="activity-header">
            <h4 class="activity-title">{{ activity.title }}</h4>
            <span class="activity-status" :class="activity.status">{{ activity.statusText }}</span>
          </div>
          <p class="activity-description">{{ activity.description }}</p>
          <div class="activity-meta">
            <span class="activity-date">📅 {{ activity.date }}</span>
            <span class="activity-location">📍 {{ activity.location }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  actualites: Array,
  informations: Array,
  activites: Array
})
</script>
