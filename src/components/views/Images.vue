<template> 
    <div class="app"> 
      <!-- Bannière élégante avec motif islamique --> 
      <section class="page-banner"> 
        <div class="banner-pattern"></div> 
         
        <!-- Ajout des motifs islamiques animés --> 
        <div class="islamic-patterns"> 
          <!-- Étoiles --> 
          <div v-for="n in 8" :key="`star-${n}`" class="islamic-pattern star" :style="{  
            left: `${Math.random() * 100}%`,  
            top: `${Math.random() * 100}%`, 
            animationDelay: `${Math.random() * 5}s`, 
            animationDuration: `${15 + Math.random() * 20}s` 
          }"></div> 
           
          <!-- Croissants --> 
          <div v-for="n in 5" :key="`crescent-${n}`" class="islamic-pattern crescent" :style="{  
            left: `${Math.random() * 100}%`,  
            top: `${Math.random() * 100}%`, 
            animationDelay: `${Math.random() * 5}s`, 
            animationDuration: `${15 + Math.random() * 20}s` 
          }"></div> 
           
          <!-- Motifs géométriques --> 
          <div v-for="n in 10" :key="`geometric-${n}`" class="islamic-pattern geometric" :style="{  
            left: `${Math.random() * 100}%`,  
            top: `${Math.random() * 100}%`, 
            animationDelay: `${Math.random() * 5}s`, 
            animationDuration: `${15 + Math.random() * 20}s`, 
            transform: `rotate(${Math.random() * 360}deg)` 
          }"></div> 
        </div> 
         
        <div class="container"> 
          <div class="page-title"> 
            <h1>Médiathèque <span class="highlight">AEEMCI</span></h1> 
            <p class="subtitle">Découvrez notre collection de photos d'événements</p> 
            <div class="breadcrumbs"> 
              <a href="#">Accueil</a> <span class="separator">/</span> <span class="current">Médiathèque</span> 
            </div> 
          </div> 
        </div> 
      </section> 
  
      <!-- Contenu principal --> 
      <main class="main-content"> 
        <div class="container"> 
          <!-- Introduction --> 
          <section class="intro-section"> 
            <div class="intro-text"> 
              <p>Bienvenue dans la médiathèque de l'AEEMCI, votre espace dédié à la découverte visuelle de nos activités, événements et moments forts. Explorez notre riche collection de photos qui témoignent de l'engagement et du dynamisme de notre association.</p> 
            </div> 
             
            <!-- Filtre par événement uniquement --> 
            <div class="event-filter-section"> 
              <div class="filter-container">
                <label for="event-select" class="filter-label">Choisir un événement :</label> 
                <select id="event-select" v-model="selectedEvent" class="event-select"> 
                  <option value="all">Tous les événements</option> 
                  <option value="senafoi">SENAFOI</option> 
                  <option value="conferences">Conférences</option> 
                  <option value="formations">Formations</option> 
                  <option value="actions-sociales">Actions sociales</option> 
                </select> 
              </div>
            </div>
          </section> 
           
          <!-- Galerie de photos uniquement --> 
          <section class="media-gallery"> 
            <div class="gallery-grid photos-grid"> 
              <div v-for="(photo, index) in filteredPhotos" :key="index" class="gallery-item" @click="openLightbox('photos', index)"> 
                <div class="gallery-image"> 
                  <img :src="photo.thumbnail" :alt="photo.caption" /> 
                  <div class="image-overlay"> 
                    <div class="image-date">{{ photo.date }}</div> 
                    <div class="image-caption">{{ photo.caption }}</div> 
                  </div> 
                </div> 
              </div> 
            </div> 
          </section> 
        </div> 
      </main> 
       
      <!-- Lightbox pour les photos --> 
      <div class="lightbox" v-if="lightboxOpen" @click="closeLightbox"> 
        <div class="lightbox-content" @click.stop> 
          <button class="lightbox-close" @click="closeLightbox">×</button> 
          <img :src="currentMedia.full" :alt="currentMedia.caption" class="lightbox-img" /> 
          <div class="lightbox-caption"> 
            <div class="lightbox-date">{{ currentMedia.date }}</div> 
            <div class="lightbox-text">{{ currentMedia.caption }}</div> 
          </div> 
          <button class="lightbox-nav prev" @click.stop="prevImage">❮</button> 
          <button class="lightbox-nav next" @click.stop="nextImage">❯</button> 
        </div> 
      </div> 
    </div> 
  </template> 
  
  <script setup> 
  import { ref, computed, onMounted } from 'vue'; 
  
  // État pour le filtre événement uniquement 
  const selectedEvent = ref('all'); 
  
  // Données des photos 
  const photos = ref([ 
    { 
      id: 1, 
      thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/490196253_3453405321457455_8738441569432249276_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeHLCI6NM5uQbMaddVvT-05SU1H-NnS43hFTUf42dLjeEeaV0tY1P7bZ-kzJ4tGpPflJEd-q5jopN6W0cFwj5mvY&_nc_ohc=uDYCtFhDiggQ7kNvwEVevgN&_nc_oc=Adk0pyMEDFTWEdP9mK5hSlMF_X0ERhsc-7L2biLJYX4ebeFZmfkuXuwQrg-egIqSejQ&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=pnvhv4wPSsUUOb0hQqKoFA&oh=00_AfGtyn_7eOhNluiN0El6j0vNmfwLhdVFhpWab-7ssLFZEQ&oe=680F1183', 
      full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/490196253_3453405321457455_8738441569432249276_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeHLCI6NM5uQbMaddVvT-05SU1H-NnS43hFTUf42dLjeEeaV0tY1P7bZ-kzJ4tGpPflJEd-q5jopN6W0cFwj5mvY&_nc_ohc=uDYCtFhDiggQ7kNvwEVevgN&_nc_oc=Adk0pyMEDFTWEdP9mK5hSlMF_X0ERhsc-7L2biLJYX4ebeFZmfkuXuwQrg-egIqSejQ&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=pnvhv4wPSsUUOb0hQqKoFA&oh=00_AfGtyn_7eOhNluiN0El6j0vNmfwLhdVFhpWab-7ssLFZEQ&oe=680F1183', 
      caption: 'Séminaire SENAFOI 2023', 
      date: '15/08/2023', 
      category: 'senafoi', 
      year: 2023 
    }, 
    { 
      id: 2, 
      thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t1.6435-9/197506105_1022086955282712_7942794850543615823_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeE5G7aqmaBh86BYMGbKhvWwKRnNGhAdYg4pGc0aEB1iDgeyNAofR0D62-9SYkUHCObMhCm4BT2sn3ljy1UPsqhb&_nc_ohc=ZbhfB3TIkMkQ7kNvwGzGbSb&_nc_oc=AdmpeoKzu2UCf3h3KeJoQFA_TSyNa4kchJi1MPC0Mbv9uwU2bjpZJd9QRoJzucv1Jno&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=b5NuQDdDnFiNvF69_NrK2w&oh=00_AfHRV5Pa6LwYpCVBRz7QIwNFCy3795tAsl5oRDCfRVKJUQ&oe=6830A06D', 
      full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t1.6435-9/197506105_1022086955282712_7942794850543615823_n.jpg?_nc_cat=107&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeE5G7aqmaBh86BYMGbKhvWwKRnNGhAdYg4pGc0aEB1iDgeyNAofR0D62-9SYkUHCObMhCm4BT2sn3ljy1UPsqhb&_nc_ohc=ZbhfB3TIkMkQ7kNvwGzGbSb&_nc_oc=AdmpeoKzu2UCf3h3KeJoQFA_TSyNa4kchJi1MPC0Mbv9uwU2bjpZJd9QRoJzucv1Jno&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=b5NuQDdDnFiNvF69_NrK2w&oh=00_AfHRV5Pa6LwYpCVBRz7QIwNFCy3795tAsl5oRDCfRVKJUQ&oe=6830A06D', 
      caption: 'Conférence sur l\'éducation islamique', 
      date: '20/03/2024', 
      category: 'conferences', 
      year: 2024 
    }, 
    { 
      id: 4, 
      thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/491970025_975113301450989_9055158565514544768_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFDzzJ74J5r03B7bfNQrlPp1GNmp2nwOKrUY2anafA4qlDxRuWvJUpecfCHKl-osJGb69wOFosAHPby4q8-VIgb&_nc_ohc=8gJAYTWXD8QQ7kNvwGzMH05&_nc_oc=Admyf7L5LMzlzvI2IMF7ka-cUoEHwyprz5UglaBmZW6BGUsJZNKFpL-irKTX9vDim7I&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=TOQ6Ue-QZg6mizGoplo3Vw&oh=00_AfEgWnbn7eC28uck5ye1oeFlbVgO-7qdyk1yJUITeijp9g&oe=680F0F14', 
      full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/491970025_975113301450989_9055158565514544768_n.jpg?_nc_cat=110&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeFDzzJ74J5r03B7bfNQrlPp1GNmp2nwOKrUY2anafA4qlDxRuWvJUpecfCHKl-osJGb69wOFosAHPby4q8-VIgb&_nc_ohc=8gJAYTWXD8QQ7kNvwGzMH05&_nc_oc=Admyf7L5LMzlzvI2IMF7ka-cUoEHwyprz5UglaBmZW6BGUsJZNKFpL-irKTX9vDim7I&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=TOQ6Ue-QZg6mizGoplo3Vw&oh=00_AfEgWnbn7eC28uck5ye1oeFlbVgO-7qdyk1yJUITeijp9g&oe=680F0F14', 
      caption: 'Formation en leadership islamique', 
      date: '12/01/2024', 
      category: 'formations', 
      year: 2024 
    }, 
    { 
      id: 5, 
      thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/478403972_942003521378083_4019226809911013937_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeENuI4QvGM2nDSItQGEKi5KVHyBsxr0LLlUfIGzGvQsufnZTS9UvkZgM124negPgK5eUPbDdGLhBVKLLKG7U_2w&_nc_ohc=22CQJktk9h4Q7kNvwFNfwD8&_nc_oc=Adly1T5rld9Yl6zqcrQ3gj4c1GLcptWQeVsRadouIs5ugxxE7MN9W-PS98gVB37G2lI&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=dbqLp9Q_yFvnyyVjzoPz9Q&oh=00_AfGiLN3ZYMScjEtus4MuQ_A3cFOgvCzG6ReufvngDOJLmA&oe=680F03C5', 
      full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/478403972_942003521378083_4019226809911013937_n.jpg?_nc_cat=101&ccb=1-7&_nc_sid=833d8c&_nc_eui2=AeENuI4QvGM2nDSItQGEKi5KVHyBsxr0LLlUfIGzGvQsufnZTS9UvkZgM124negPgK5eUPbDdGLhBVKLLKG7U_2w&_nc_ohc=22CQJktk9h4Q7kNvwFNfwD8&_nc_oc=Adly1T5rld9Yl6zqcrQ3gj4c1GLcptWQeVsRadouIs5ugxxE7MN9W-PS98gVB37G2lI&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=dbqLp9Q_yFvnyyVjzoPz9Q&oh=00_AfGiLN3ZYMScjEtus4MuQ_A3cFOgvCzG6ReufvngDOJLmA&oe=680F03C5', 
      caption: 'Concours de récitation du Coran', 
      date: '25/12/2023', 
      category: 'formations', 
      year: 2023 
    }, 
    { 
      id: 6, 
      thumbnail: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/482032246_1821894445299192_5407720889437853097_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeEUVwuXWo_WaP_iiKYDLEB16HRyT2Dz743odHJPYPPvjafLUy-l7W5gAsDaPOli5r_BBrUWVq83cDtwXAJ1GQQZ&_nc_ohc=adL00ma2GPoQ7kNvwFzTH3_&_nc_oc=AdkYny9Ya9sIJbNIwLId0YclSg2Lr1LzYUnPvs26gdfTLxiz9CvRrlcwCk59DyLmOh4&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=_MvS7yMidRAO0w8EVzRTIA&oh=00_AfHgwfdQP6ajgj7SNSebI5TEIR1OtF-r9kDxxPloc6xr9w&oe=680F01E2', 
      full: 'https://scontent.fabj3-2.fna.fbcdn.net/v/t39.30808-6/482032246_1821894445299192_5407720889437853097_n.jpg?_nc_cat=102&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeEUVwuXWo_WaP_iiKYDLEB16HRyT2Dz743odHJPYPPvjafLUy-l7W5gAsDaPOli5r_BBrUWVq83cDtwXAJ1GQQZ&_nc_ohc=adL00ma2GPoQ7kNvwFzTH3_&_nc_oc=AdkYny9Ya9sIJbNIwLId0YclSg2Lr1LzYUnPvs26gdfTLxiz9CvRrlcwCk59DyLmOh4&_nc_zt=23&_nc_ht=scontent.fabj3-2.fna&_nc_gid=_MvS7yMidRAO0w8EVzRTIA&oh=00_AfHgwfdQP6ajgj7SNSebI5TEIR1OtF-r9kDxxPloc6xr9w&oe=680F01E2', 
      caption: 'Séminaire SENAFOI 2022', 
      date: '18/08/2022', 
      category: 'senafoi', 
      year: 2022 
    },
    { 
      id: 7, 
      thumbnail: 'https://scontent.fabj1-1.fna.fbcdn.net/v/t39.30808-6/480784758_1248895170572912_7590366694065536393_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGA6X_FS4AHBecU2dMhHUbVq0EGkziSpqarQQaTOJKmpisnuI6TgmzRK4PgX2CdcZ_hEpMDy2GsQ-A08h9BA7TO&_nc_ohc=OZ8DylP5bb8Q7kNvwE1nLPm&_nc_oc=AdmLfcU-J32yQ97HBNQ0bAunNgEt6rJEI6s-U1-31XSu4dN_k_K9Yxo0W7OZoFB47W0&_nc_zt=23&_nc_ht=scontent.fabj1-1.fna&_nc_gid=XFGsWEiLOGWWvThosvR8Tg&oh=00_AfF95xZUQMw8_N5Sh0zf_zuHQBDpCtgnDAN9HUS_cYNXhA&oe=680864F4', 
      full: 'https://scontent.fabj1-1.fna.fbcdn.net/v/t39.30808-6/480784758_1248895170572912_7590366694065536393_n.jpg?_nc_cat=103&ccb=1-7&_nc_sid=127cfc&_nc_eui2=AeGA6X_FS4AHBecU2dMhHUbVq0EGkziSpqarQQaTOJKmpisnuI6TgmzRK4PgX2CdcZ_hEpMDy2GsQ-A08h9BA7TO&_nc_ohc=OZ8DylP5bb8Q7kNvwE1nLPm&_nc_oc=AdmLfcU-J32yQ97HBNQ0bAunNgEt6rJEI6s-U1-31XSu4dN_k_K9Yxo0W7OZoFB47W0&_nc_zt=23&_nc_ht=scontent.fabj1-1.fna&_nc_gid=XFGsWEiLOGWWvThosvR8Tg&oh=00_AfF95xZUQMw8_N5Sh0zf_zuHQBDpCtgnDAN9HUS_cYNXhA&oe=680864F4', 
      caption: 'Action sociale à Yopougon', 
      date: '15/02/2024', 
      category: 'actions-sociales', 
      year: 2024 
    }
  ]); 
  
  // Filtrer les photos par événement uniquement 
  const filteredPhotos = computed(() => { 
    let result = [...photos.value]; 
     
    if (selectedEvent.value !== 'all') { 
      result = result.filter(photo => photo.category === selectedEvent.value); 
    } 
     
    return result; 
  }); 
  
  // Lightbox pour les photos 
  const lightboxOpen = ref(false); 
  const currentMediaType = ref(''); 
  const currentMediaIndex = ref(0); 
  const currentMedia = computed(() => { 
    if (currentMediaType.value === 'photos') { 
      return filteredPhotos.value[currentMediaIndex.value] || {}; 
    } 
    return {}; 
  }); 
  
  const openLightbox = (type, index) => { 
    currentMediaType.value = type; 
    currentMediaIndex.value = index; 
    lightboxOpen.value = true; 
    document.body.style.overflow = 'hidden'; 
  }; 
  
  const closeLightbox = () => { 
    lightboxOpen.value = false; 
    document.body.style.overflow = 'auto'; 
  }; 
  
  const nextImage = () => { 
    if (currentMediaType.value === 'photos') { 
      currentMediaIndex.value = (currentMediaIndex.value + 1) % filteredPhotos.value.length; 
    } 
  }; 
  
  const prevImage = () => { 
    if (currentMediaType.value === 'photos') { 
      currentMediaIndex.value = (currentMediaIndex.value - 1 + filteredPhotos.value.length) % filteredPhotos.value.length; 
    } 
  }; 
  
  // Initialisation 
  onMounted(() => { 
    // Écouter les touches du clavier pour la galerie 
    document.addEventListener('keydown', (e) => { 
      if (lightboxOpen.value) { 
        if (e.key === 'Escape') { 
          closeLightbox(); 
        } else if (e.key === 'ArrowRight') { 
          nextImage(); 
        } else if (e.key === 'ArrowLeft') { 
          prevImage(); 
        } 
      } 
    }); 
  }); 
  
  </script> 
  
  <script> 
  // Pour respecter la règle multi-word 
  export default { 
    name: 'ImaGes' 
  } 
  </script> 
  
  <style> 
  :root { 
    --primary: #006400; 
    --primary-dark: #004d00; 
    --primary-light: #008000; 
    --primary-lighter: #e6f0e6; 
    --secondary: #f8b400; 
    --secondary-dark: #d99b00; 
    --secondary-light: #ffc933; 
    --accent: #e67e22; 
    --text-primary: #333333; 
    --text-secondary: #666666; 
    --text-light: #999999; 
    --background: #ffffff; 
    --background-alt: #f5f5f5; 
    --border: #e0e0e0; 
    --success: #28a745; 
    --danger: #dc3545; 
    --warning: #ffc107; 
    --info: #17a2b8; 
  } 
  
  * { 
    margin: 0; 
    padding: 0; 
    box-sizing: border-box; 
  } 
  
  body { 
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
    color: var(--text-primary); 
    line-height: 1.6; 
    background-color: var(--background); 
    font-size: 14px; 
  } 
  
  .container { 
    width: 100%; 
    max-width: 1200px; 
    margin: 0 auto; 
    padding: 0 1rem; 
  } 
  
  a { 
    color: var(--primary); 
    text-decoration: none; 
    transition: all 0.3s ease; 
  } 
  
  a:hover { 
    color: var(--primary-dark); 
  } 
  
  img { 
    max-width: 100%; 
    height: auto; 
  } 
  
  /* Page Banner */ 
  .page-banner { 
    background: linear-gradient(rgba(0, 100, 0, 0.9), rgba(0, 100, 0, 0.9)); 
    color: white; 
    padding: 4rem 0; 
    text-align: center; 
    position: relative; 
    overflow: hidden; 
  } 
  
  .banner-pattern { 
    position: absolute; 
    top: 0; 
    left: 0; 
    right: 0; 
    bottom: 0; 
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E"); 
    opacity: 0.1; 
  } 
  
  .page-title { 
    position: relative; 
    z-index: 1; 
  } 
  
  .page-title h1 { 
    font-size: 3rem; 
    margin-bottom: 0.5rem; 
    font-weight: 700; 
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3); 
  } 
  
  .subtitle { 
    font-size: 1.2rem; 
    margin-bottom: 1rem; 
    opacity: 0.9; 
  } 
  
  .breadcrumbs { 
    font-size: 0.85rem; 
    opacity: 0.8; 
  } 
  
  .breadcrumbs a { 
    color: white; 
    opacity: 0.8; 
    transition: opacity 0.3s ease; 
  } 
  
  .breadcrumbs a:hover { 
    opacity: 1; 
  } 
  
  .separator { 
    margin: 0 0.5rem; 
    opacity: 0.6; 
  } 
  
  .current { 
    opacity: 1; 
    font-weight: 600; 
  } 
  
  .highlight { 
    color: var(--secondary); 
    font-weight: 800; 
  } 
  
  /* Motifs islamiques animés */ 
  .islamic-patterns { 
    position: absolute; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    pointer-events: none; 
    overflow: hidden; 
    z-index: 1; 
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
  
  /* Main Content */ 
  .main-content { 
    padding: 4rem 0; 
    background-color: var(--background); 
  } 
  
  /* Introduction Section */ 
  .intro-section { 
    margin-bottom: 3rem; 
  } 
  
  .intro-text { 
    margin-bottom: 2rem; 
    font-size: 1rem; 
    line-height: 1.8; 
  } 
  
  /* Event Filter Section */
  .event-filter-section {
    margin-bottom: 3rem;
    display: block;
    padding: 2rem;
    background-color: var(--background-alt);
    border-radius: 8px;
    border: 1px solid var(--border);
  }
  
  .filter-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
  }
  
  .filter-label {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
    text-align: center;
  }
  
  .event-select {
    padding: 0.8rem 1.2rem;
    border: 2px solid var(--primary);
    border-radius: 6px;
    font-size: 1rem;
    background-color: white;
    color: var(--text-primary);
    cursor: pointer;
    min-width: 250px;
    text-align: center;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  .event-select:focus {
    outline: none;
    border-color: var(--primary-dark);
    box-shadow: 0 0 0 3px rgba(0, 100, 0, 0.1);
    transform: translateY(-2px);
  }
  
  .event-select:hover {
    border-color: var(--primary-dark);
    transform: translateY(-1px);
  }
  
  @media (max-width: 768px) {
    .event-select {
      min-width: 200px;
      font-size: 0.9rem;
    }
  }
  
  /* Gallery Grid */ 
  .gallery-grid { 
    display: grid; 
    gap: 1.5rem; 
    margin-bottom: 2rem; 
  } 
  
  .photos-grid { 
    grid-template-columns: repeat(4, 1fr); 
  } 
  
  .gallery-item { 
    border-radius: 8px; 
    overflow: hidden; 
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1); 
    transition: all 0.3s ease; 
    cursor: pointer; 
  } 
  
  .gallery-item:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1); 
  } 
  
  .gallery-image { 
    position: relative; 
    overflow: hidden; 
  } 
  
  .gallery-image img { 
    width: 100%; 
    height: 200px; 
    object-fit: cover; 
    transition: transform 0.5s ease; 
  } 
  
  .gallery-item:hover .gallery-image img { 
    transform: scale(1.05); 
  } 
  
  .image-overlay { 
    position: absolute; 
    bottom: 0; 
    left: 0; 
    right: 0; 
    background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent); 
    padding: 1rem; 
    color: white; 
    transform: translateY(0); 
    transition: transform 0.3s ease; 
  } 
  
  .image-date { 
    font-weight: 700; 
    font-size: 0.9rem; 
    margin-bottom: 0.3rem; 
  } 
  
  /* Lightbox */ 
  .lightbox { 
    position: fixed; 
    top: 0; 
    left: 0; 
    right: 0; 
    bottom: 0; 
    background-color: rgba(0, 0, 0, 0.9); 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    z-index: 1000; 
  } 
  
  .lightbox-content { 
    position: relative; 
    max-width: 90%; 
    max-height: 90vh; 
    display: flex; 
    flex-direction: column; 
  } 
  
  .lightbox-close { 
    position: absolute; 
    top: 10px; 
    right: 10px; 
    background: rgba(0, 0, 0, 0.5); 
    color: white; 
    border: none; 
    width: 30px; 
    height: 30px; 
    border-radius: 50%; 
    font-size: 1.5rem; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    cursor: pointer; 
    z-index: 10; 
  } 
  
  .lightbox-img { 
    max-width: 100%; 
    max-height: 80vh; 
    object-fit: contain; 
  } 
  
  .lightbox-caption { 
    background-color: rgba(0, 0, 0, 0.7); 
    color: white; 
    padding: 1rem; 
    margin-top: 1rem; 
    border-radius: 4px; 
  } 
  
  .lightbox-date { 
    font-weight: 700; 
    margin-bottom: 0.5rem; 
  } 
  
  .lightbox-nav { 
    position: absolute; 
    top: 50%; 
    transform: translateY(-50%); 
    background: rgba(0, 0, 0, 0.5); 
    color: white; 
    border: none; 
    width: 40px; 
    height: 40px; 
    border-radius: 50%; 
    font-size: 1.5rem; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    cursor: pointer; 
  } 
  
  .lightbox-nav.prev { 
    left: 20px; 
  } 
  
  .lightbox-nav.next { 
    right: 20px; 
  } 
  
  /* Responsive Styles */ 
  @media (max-width: 992px) { 
    .photos-grid { 
      grid-template-columns: repeat(3, 1fr); 
    } 
  } 
  
  @media (max-width: 768px) { 
    .page-title h1 { 
      font-size: 2rem; 
    } 
  
    .photos-grid { 
      grid-template-columns: repeat(2, 1fr); 
    } 
  
    .media-filters { 
      flex-direction: column; 
    } 
  } 
  
  @media (max-width: 576px) { 
    .photos-grid { 
      grid-template-columns: 1fr; 
    } 
  } 
  </style>
  