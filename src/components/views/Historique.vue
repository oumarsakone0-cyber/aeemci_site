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
          <h1>L'Histoire de l'<span class="highlight">AEEMCI</span></h1>
          <p class="subtitle">Un demi-siècle d'engagement pour la jeunesse musulmane ivoirienne</p>
          <div class="breadcrumbs">
            <a href="#">Accueil</a> <span class="separator">/</span> <a href="#">AEEMCI</a> <span class="separator">/</span> <span class="current">Histoire</span>
          </div>
        </div>
      </div>
    </section>
  
      <!-- Contenu principal -->
      <main class="main-content">
        <div class="container">
          <!-- Introduction avec citation -->
          <section class="intro-section">
            <div class="quote-box">
              <div class="quote-mark">"</div>
              <blockquote>
                L'histoire de l'AEEMCI est celle d'une vision, d'un engagement et d'une détermination sans faille pour préserver l'identité islamique tout en embrassant l'excellence académique.
              </blockquote>
              <div class="quote-author">— Fondateurs de l'AEEMCI</div>
            </div>
            
            <div class="intro-text">
              <p>Découvrez l'histoire fascinante de l'Association des Élèves et Étudiants Musulmans de Côte d'Ivoire (AEEMCI), une organisation qui a façonné la vie de milliers de jeunes musulmans ivoiriens vivant en Côte d'Ivoire depuis sa naissance en 1972.</p>
              <p>De ses modestes débuts dans une chambre universitaire à son statut actuel d'organisation influente à l'échelle nationale, l'AEEMCI a traversé les décennies en restant fidèle à sa mission fondamentale : contribuer à la formation religieuse et managériale des élèves et étudiants musulmans, pour l’émergence d’une élite musulmane consciente des valeurs républicaines et de sa responsabilité communautaire et ce, par des cours et autres activités extra scolaires</p>
            </div>
          </section>
          
          <!-- Timeline horizontale pour navigation rapide -->
          <section class="timeline-nav">
            <div class="timeline-track">
              <div class="timeline-line"></div>
              <div 
                v-for="(period, index) in timelinePeriods" 
                :key="index"
                :class="['timeline-point', { active: activePeriod === index }]"
                @click="scrollToPeriod(index)"
              >
                <div class="point-year">{{ period.year }}</div>
                <div class="point-label">{{ period.label }}</div>
              </div>
            </div>
          </section>
          
          <!-- Périodes historiques -->
          <section class="history-periods">
            <!-- Période 1: Naissance -->
            <div id="period-0" class="period-card" ref="periodRefs">
              <div class="period-header">
                <div class="period-year">1972</div>
                <h2 class="period-title">Naissance de l'AEEMCI</h2>
              </div>
              
              <div class="period-content">
                <div class="period-gallery">
                  
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758349618/aeemci_photo_1758349617992_Vassiriki_tourefd.png.png" alt="Première assemblée" />
                    <div class="image-caption">1er Président</div>
                  </div>
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350279/aeemci_photo_1758350277814_fffd.jpg.jpg" alt="Fondateurs de l'AEEMCI" />
                    <div class="image-caption">Rencontre</div>
                  </div>
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758349497/aeemci_photo_1758349495440_WhatsApp_Image_2025-09-20_%C3%A0_06.23.37_0cd293c9.jpg.jpg" alt="Premiers événements" />
                    <div class="image-caption">Activité SIFRAM</div>
                  </div>
                </div>
                
                <div class="period-text">
                  <p>À la faveur de l'organisation du mois de Ramadan sur le campus de l'université de Cocody, un groupe de jeunes étudiants musulmans réunis dans une chambre de cité universitaire font lancer les bases d'une structure qui sera en quelques décennies, hissée au diapason des grands mouvements musulmans crédibles de la sous-région Africaine.</p>

                   <p>De la JEM à l’AEEMCI en passant par l’AEEMA, la jeunesse scolaire et estudiantine musulmane de Côte d’Ivoire a une histoire émaillée d’instants mémorables aussi bien heureux que moins heureux. Près d’un demi-siècle durant, en dépit des nombreuses difficultés, l’AEEMCI sans grands moyens a su réaliser des exploits considérables dans les domaines de l’engagement militant et citoyens, de l’éducation et de la formation aussi bien au niveau national que sous régional</p>
                  
                  <p>La problématique au cœur de la réflexion des précurseurs du mouvement suscité par une poignée d'étudiants musulmans dont Mory BAH et Vassiriki TOURÉ, était une question de survie de toute une communauté : comment continuer d'aller à l'école des blancs sans perdre son âme, sa foi et son identité islamique ?</p>

                  <p>Le tableau sombre de la forte déperdition du potentiel humain musulman, de l’abandon de la foi islamique par de nombreux jeunes étudiants et cadres musulmans du fait de l’école des blancs, était déjà révélateur de la portée de la haute mission qui attendait les précurseurs du mouvement. </p>

                  <!-- Périodes historiques 
                  <div class="key-figures">
                    <h3>Figures clés</h3>
                    <div class="figures-grid">
                      <div class="figure-card">
                        <div class="figure-avatar">
                          <img src="https://aeemci.org/storage/images_anciens_leader/President_1.png" alt="Vassiriki Touré" />
                        </div>
                        <div class="figure-info">
                          <div class="figure-name">Vassiriki Touré</div>
                          <div class="figure-role">Premier président (1972-1975)</div>
                        </div>
                      </div>
                      <div class="figure-card">
                        <div class="figure-avatar">
                          <img src="https://aeemci.org/storage/images_anciens_leader/President_2.png" alt="Abdoul Kader Keita" />
                        </div>
                        <div class="figure-info">
                          <div class="figure-name">Abdoul Kader Keita</div>
                          <div class="figure-role">Président (1975-1978)</div>
                        </div>
                      </div>
                    </div>
                  </div>
                  -->
                </div>
              </div>
            </div>
            
            <!-- Période 2: Développement -->
            <div id="period-1" class="period-card" ref="periodRefs">
              <div class="period-header">
                <div class="period-year">1975</div>
                <h2 class="period-title">Réconnaissance</h2>
              </div>
              
              <div class="period-content">
                <div class="period-gallery">
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758349682/aeemci_photo_1758349682241_PR_KADER_KEITAjnk.png.png" alt="Activités éducatives" />
                    <div class="image-caption">2ème Président</div>
                  </div>
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350614/aeemci_photo_1758350612316_reunion.jpg.jpg" alt="Expansion régionale" />
                    <div class="image-caption">Rencontre</div>
                  </div>
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350685/aeemci_photo_1758350683723_conference_75.jpg.jpg" alt="Conférences nationales" />
                    <div class="image-caption">Conférences nationales</div>
                  </div>
                </div>
                
                <div class="period-text">
                   <p>Il fallait donc dans l’urgence, susciter au cœur de l’espace scolaire et estudiantin ivoirien, un écosystème favorable pour le jeune musulman à son appartenance religieuse sans complexe ni crainte, nonobstant les hostilités du milieu : l’AEEMCI naquit. 
                    Après plusieurs années de lutte et d’endurance, l’AEEMCI par un arrêté Ministériel N° : 576/INT/AT/AG/1 en date du 12 avril 1979 du ministre de l’Intérieur, l’AEEMCI obtint la reconnaissance de l’Etat de Côte d’Ivoire. Déjà, deux assemblée générales constitutives avait scellé l’existence juridique de la structure : celle de 1972 avait désigné Monsieur Vassiriki TOURE, étudiant en science économique comme premier président et celle de 1975 ayant investi le Président Abdoul Kader KEÏTA, alors étudiant en faculté de Médecine, comme président. 
                    </p>
                  
                   <div class="key-event">
                    <div class="event-date">12 avril 1979</div>
                    <div class="event-description">
                      <p>L'AEEMCI obtient la reconnaissance officielle de l'État de Côte d'Ivoire par arrêté Ministériel N° : 576/INT/AT/AG/1 du 12 avril 1979</p>
                    </div>
                  </div>

                  <!-- Période 2: Développement 
                  <div class="milestone-list">
                    <h3>Réalisations majeures</h3>
                    <ul>
                      <li>
                        <div class="milestone-year">1985</div>
                        <div class="milestone-text">Premier Séminaire National de Formation Islamique (SENAFOI)</div>
                      </li>
                      <li>
                        <div class="milestone-year">1988</div>
                        <div class="milestone-text">Création du premier journal de l'association "La Plume"</div>
                      </li>
                      <li>
                        <div class="milestone-year">1992</div>
                        <div class="milestone-text">Célébration du 20ème anniversaire et expansion à 15 régions</div>
                      </li>
                      <li>
                        <div class="milestone-year">1995</div>
                        <div class="milestone-text">Premiers partenariats internationaux avec des organisations islamiques</div>
                      </li>
                    </ul>
                  </div>
                 
                  <div class="impact-box">
                    <h3>Impact sur l'éducation</h3>
                    <p>Durant cette période, l'AEEMCI a commencé à mettre en place des programmes de soutien scolaire et d'excellence académique qui ont significativement amélioré les performances des élèves et étudiants musulmans. Les cours de renforcement, les concours et les bourses d'études ont permis à de nombreux jeunes de poursuivre leurs études avec succès.</p>
                  </div>
 -->
                </div>
              </div>
            </div>
            
            <!-- Période 3: Quarante ans après -->
            <div id="period-2" class="period-card" ref="periodRefs">
              <div class="period-header">
                <div class="period-year">2015</div>
                <h2 class="period-title">Quarante Ans d'Excellence</h2>
              </div>
              
              <div class="period-content">
                <div class="period-gallery">
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758350841/aeemci_photo_1758350839817_amirbabam.jpg.jpg" alt="Célébration 40ème anniversaire" />
                    <div class="image-caption">Rencontre avec l'Amir</div>
                  </div>
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351163/aeemci_photo_1758351161265_400.jpg.jpg" alt="Conférence nationale" />
                    <div class="image-caption">Logo 40 ans</div>
                  </div>
                  <div class="gallery-item">
                    <img style="object-fit: contain;" src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351364/aeemci_photo_1758351362800_dddd%C3%A0.jpg.jpg" alt="Actions sociales" />
                    <div class="image-caption">Célébration des 40 ans</div>
                  </div>
                </div>
                
                <div class="period-text">
                  <p>En 2016, l'AEEMCI célèbre ses 40 ans d'existence avec fierté. L'association est devenue un acteur incontournable de la vie associative et éducative en Côte d'Ivoire, avec un réseau plus vaste que de nombreux services de l'administration ivoirienne.</p>
                  
                  <div class="stats-grid">
                    <div class="stat-item animate-count">
                      <div class="stat-number" data-count="31">31</div>
                      <div class="stat-label">Secrétariats régionaux</div>
                    </div>
                    <div class="stat-item animate-count">
                      <div class="stat-number" data-count="236">200+</div>
                      <div class="stat-label">Sous-comités</div>
                    </div>
                    <div class="stat-item animate-count">
                      <div class="stat-number" data-count="1032">1 000+</div>
                      <div class="stat-label">Sections</div>
                    </div>
                    <div class="stat-item animate-count">
                      <div class="stat-number" data-count="522678">500 000+</div>
                      <div class="stat-label">Membres</div>
                    </div>
                  </div>
                  
                  <p>Plus de 40 ans après le début de cette marche, qu’on pourrait dire glorieuse, l’AEEMCI a réussi à mettre en place cette communauté d’Elèves et Etudiants musulmans disposant d’une formation religieuse de base, doublée d’une maitrise managériale non négligeable. Une jeunesse musulmane pourvue de valeurs religieuses, éthiques et consciente de ses responsabilités citoyennes. Et oui à travers ces quelques quatre décennies de vie, l’AEEMCI a réussi à mettre en place un réseau plus vaste que tous les services de l’administration ivoirienne. En effet déjà en 2008 nous avions un réseau plus grand que celui de La Poste de Côte d’Ivoire. Ce sont 31 secrétariats régionaux, 269 sous-comités et 1032 sections que nous comptons à ce jour. En sus les cadres de formation à l’AEEMCI sont innombrables. Au moins une séance de formation hebdomadaire dans chacune de nos sections et sous-comités, un (1) séminaire de formation annuel, d’au moins 300 participants, dans chacune de nos régions et un séminaire national annuel, qui de plus en plus tend à refuser du monde. Si l’AEEMCI réclame aujourd’hui plus de cinq cent vingt-deux mille six-cent soixante-dix-huit    (522.678) membres et autant de sympathisants, ce sont au moins 3 millions de personnes qui depuis ces quarante dernières années ont été en contact au moins une fois avec notre structure et qui en ont subi l’influence positive autant à l’école que dans la vie socio-professionnelle.  </p>
                  
                  <div class="achievement-box">
                    <h3>Contributions majeures</h3>
                    <div class="achievements-grid">
                      <div class="achievement-card">
                        <div class="achievement-icon">🎓</div>
                        <div class="achievement-text">
                          <h4>Acteur de la société civile</h4>
                          <p style="text-align: justify;">L’AEEMCI est également membre fondateur de la Convention de la Société Civile 
                            Ivoirienne (CSCI) dans laquelle elle œuvre, avec d’autres organisations, pour la réconciliation nationale et la cohésion sociale. C’est à travers des dizaines de conférences et de séance de formations que l’AEEMCI véhicule le message de paix et de cohabitation pacifique, gage de développement, tel que défini par la religion. 
                            Au plan éducatif, cette structure de jeunes s’est engagée pour la scolarisation de la jeune fille et même pour la transformation en réalité du slogan « l’école pour tous ». Dans cette perspective de faciliter l’accès à l’école à la majorité des enfants, l’AEEMCI a lancé un projet de construction d’une école qui, après des années de somnolence, connait aujourd’hui une seconde vie.  
                            Plus récemment c’est la lutte contre le terrorisme qui a mobilisé les AEEMCIstes. 
                            Également nous nous sommes constitués, au côté de l’Etat de Côte d’Ivoire, en premiers remparts contre toutes les formes de radicalisation et d’extrémisme religieux. De même que pour la dissuasion de la jeunesse ivoirienne quant à la volonté de « se lancer à l’eau » pour tenter une expérience européenne clandestine à bord de bateaux. Il s’agit de mener des présentations illustrant les dangers encourus dans ces aventures suicidaires et d’y apporter des solutions   palliatives par l’entrepreneuriat.   
                            </p>
                        </div>
                      </div>
                      <div class="achievement-card">
                        <div class="achievement-icon">🤝</div>
                        <div class="achievement-text">
                          <h4>L’AEEMCI, modèle d’intégration sous régionale</h4>
                          <p>L’AEEMCI a exporté son savoir-faire dans toute la sous-région à travers des élèves qui une fois rentrés dans leur pays respectif pour entamer le cursus universitaire y ont installé des AEEM pour faire bénéficier leur système éducatif des bienfaits d’une telle association. La bonne santé des systèmes éducatifs de pays de la sous-région est en partie due à l’exportation de nos valeurs associatives de civisme et de citoyenneté dans le respect des injonctions divines. 
                            De ce qui précède, nous voyons le rôle par l’AEEMCI dans ce pays. De telles actions méritent, humblement, une reconnaissance et un accompagnement de l’Etat pour des actions plus poussées et des résultats encore mieux profitables à la patrie. 
                            </p>
                        </div>
                      </div>
                      <div class="achievement-card">
                        <div class="achievement-icon">🌍</div>
                        <div class="achievement-text">
                          <h4>Partenariats internationaux</h4>
                          <p>Membre fondateur de l’Organisation de la Jeunesse Musulmane de l’Afrique de l’Ouest (OJEMAO).</p>
<p>Pivot idéologique et organisationnel dans la création des Associations d’Elèves et d’Etudiants (AEEM) dans la sous-région ouest-africaine :</p>
<ul>
  <li>L’Association des Elèves et Etudiants Musulmans du Sénégal (AEEMS)</li>
  <li>L’Association des Elèves et Etudiants Musulmans du Burkina (AEEMB)</li>
  <li>La Ligue Islamique des Elèves et Etudiants du Mali (LIEEMA)</li>
  <li>L’Association des Elèves et Etudiants Musulmans de la Guinée (AEEMG)</li>
  <li>L’Association des Elèves et Etudiants Musulmans du Niger (AEEMUN)</li>
  <li>L’Association des Elèves et Etudiants Musulmans du Togo (AEEMT)</li>
  <li>L’Association Culturelle des Elèves et Etudiants Musulmans du Bénin (ACEEMUB)</li>
  <hr/>
   <li>BID : Banque Islamique de Développement  </li>
    <li>IIFSO : Fédération Internationale Islamique des Organisations Estudiantines  </li>
</ul>
                        </div>
                      </div>
                      <div class="achievement-card">
                        <div class="achievement-icon">👥</div>
                        <div class="achievement-text">
                          <h4>Education</h4>
                          <p style="text-align: justify;">Après plus de 40 ans d’existence, l’AEEMCI a réussi à mettre en place cette communauté d’Elèves et Etudiants musulmans disposant d’une formation religieuse de base, doublée d’une maitrise managériale non négligeable. Une jeunesse musulmane pourvue de valeurs religieuses, éthiques et consciente de ses responsabilités citoyennes. En sus les cadres de formation à l’AEEMCI sont innombrables. Au moins une séance de formation hebdomadaire dans chacune de nos sections et sous-comités, un (1) séminaire de formation annuel d’au moins 300 participants dans chacune de nos régions et un séminaire national annuel qui de plus en plus tend à refuser du monde (700 participants). Si l’AEEMCI réclame aujourd’hui environ cent mille (100.000) membres et autant de sympathisants, ce sont environ 3 millions de personnes qui depuis ces quarante dernières années ont été en contact au moins une fois avec notre structure et qui en ont subi l’influence positive autant à l’école que dans la vie socio-professionnelle.  </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Période 4: Aujourd'hui -->
            <div id="period-3" class="period-card" ref="periodRefs">
              <div class="period-header">
                <div class="period-year">{{ currentYear }}</div>
                <h2 class="period-title">L'AEEMCI Aujourd'hui</h2>
              </div>
              
              <div class="period-content">
                <div class="period-gallery">
                  <div class="gallery-item">
                    <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351484/aeemci_photo_1758351483523_fpf.jpg.jpg" alt="Activités actuelles" />
                    <div class="image-caption">Rentrée Solennelle</div>
                  </div>
                  <div class="gallery-item">
                    <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351663/aeemci_photo_1758351660482_hgl.jpg.jpg" alt="Jeunesse engagée" />
                    <div class="image-caption">Jeunesse engagée</div>
                  </div>
                  <div class="gallery-item">
                    <img src="https://res.cloudinary.com/r-sidence-meubl-e/image/upload/v1758351709/aeemci_photo_1758351709028_rupt.jpg.jpg" alt="Éducation moderne" />
                    <div class="image-caption">Rupture Collective</div>
                  </div>
                </div>
                
                <div class="period-text">
                  <p><strong>𝐀𝐝𝐫𝐞𝐬𝐬𝐞 𝐚̀ 𝐥𝐚 𝐜𝐨𝐦𝐦𝐮𝐧𝐚𝐮𝐭𝐞́ 𝐢𝐬𝐥𝐚𝐦𝐢𝐪𝐮𝐞 𝐞𝐭 𝐚̀ 𝐥𝐚 𝐩𝐨𝐩𝐮𝐥𝐚𝐭𝐢𝐨𝐧 𝐢𝐯𝐨𝐢𝐫𝐢𝐞𝐧𝐧𝐞</strong></p>
<p><em>𝘊𝘩𝘦𝘳𝘴 𝘧𝘳𝘦̀𝘳𝘦𝘴 𝘦𝘵 𝘴œ𝘶𝘳𝘴 𝘥𝘦 𝘭𝘢 𝘤𝘰𝘮𝘮𝘶𝘯𝘢𝘶𝘵𝘦́ 𝘪𝘴𝘭𝘢𝘮𝘪𝘲𝘶𝘦,<br>
𝘊𝘩𝘦̀𝘳𝘦𝘴 𝘱𝘰𝘱𝘶𝘭𝘢𝘵𝘪𝘰𝘯𝘴 𝘥𝘦 𝘊𝘰̂𝘵𝘦 𝘥’𝘐𝘷𝘰𝘪𝘳𝘦,</em></p>
<p>Au nom d’Allah, le Clément et le Miséricordieux, je m’adresse à vous en ce jour solennel marqué par ma prise de fonction à la tête de notre prestigieuse organisation, à la suite de la passation de charge qui s’est déroulée hier au siège de l’Association des Élèves et Étudiants Musulmans de Côte d’Ivoire (AEEMCI).</p>
<p>Cette année revêt une importance particulière pour nous tous, car elle marque les 50 ans de l’AEEMCI, un demi-siècle d’engagement, de sacrifices et de lutte au service de l’éducation, de la formation et de la promotion des valeurs islamiques. C’est avec une profonde émotion et une immense gratitude envers Allah que j’assume cette lourde responsabilité, héritée de générations de leaders visionnaires et dévoués.</p>
<p>Je voudrais saisir cette occasion pour rendre un vibrant hommage à tous ceux qui, depuis la création de notre association en 1975, ont œuvré sans relâche pour bâtir cette institution solide et respectée. Leur travail, leur dévouement et leur persévérance demeurent une source d’inspiration pour les jeunes musulmans de Côte d’Ivoire et d’ailleurs.</p>
<p>Aujourd’hui, alors que nous célébrons ce jubilé d’or, il est crucial de réfléchir aux défis qui nous attendent et aux responsabilités qui nous incombent pour perpétuer cet héritage. Ces défis incluent le renforcement de notre unité, l’approfondissement de nos connaissances religieuses et académiques, ainsi que la promotion des valeurs d’intégrité, de solidarité et de paix dans notre pays.</p>
<p>Je tiens à exprimer ma gratitude à mes prédécesseurs pour le travail exceptionnel qu’ils ont accompli, ainsi qu’à la communauté islamique et à toutes les populations ivoiriennes pour leur soutien constant. Ensemble, nous continuerons à œuvrer pour une jeunesse épanouie, responsable et engagée, fidèle aux idéaux qui ont toujours guidé l’AEEMCI.</p>
<p>Je lance un appel à chacun d’entre vous : unissons nos forces pour construire une société fondée sur la paix, la tolérance et le progrès. Que ce jubilé d’or soit pour nous une source d’inspiration renouvelée pour bâtir une AEEMCI encore plus forte, plus unie et toujours au service de la communauté et de la nation.</p>
<p>Qu’Allah, dans Sa miséricorde infinie, nous guide, nous inspire et nous accompagne dans cette noble mission.</p>
<p>Je vous remercie.</p>
<p><strong>𝐄𝐥 𝐡𝐚𝐝𝐣 𝐘𝐨𝐮𝐬𝐬𝐨𝐮𝐟 𝐁𝐀𝐌𝐁𝐀</strong><br>
Amir (Président national) de l'AEEMCI</p>
                  
                  <div class="vision-mission">
                    <div class="vision-box">
                      <h3>Notre Vision</h3>
                      <p>AEEMCI : un esprit, des leaders ! </p>
                    </div>
                    <div class="vision-box">
                      <h3>Notre Slogan</h3>
                      <p>AEEMCI : pour une identité islamique ! </p>
                    </div>
                    <div class="mission-box" style="margin-top: -70px;">
                      <h3>Notre Mission</h3>
                      <p>L’AEEMCI est une structure islamique en Côte d’Ivoire, qui contribue à la formation religieuse et managériale des élèves et étudiants musulmans, pour l’émergence d’une élite musulmane consciente des valeurs républicaines et de sa responsabilité communautaire et ce, par des cours et autres activités extra scolaires. </p>
                    </div>
                    <div class="mission-box" style="margin-top: -70px;">
                      <h3>Nos Objectifs</h3>
                      <p>
                        <ol>
  <li>Susciter et sauvegarder la foi et la croyance en ALLAH le Dieu Unique ;</li>
  <li>Créer des relations de solidarité et de fraternité entre les élèves et étudiants musulmans de Côte d’Ivoire et d’ailleurs ;</li>
  <li>Sauvegarder et défendre les intérêts de ses membres et en cas de nécessité ceux de la communauté musulmane ;</li>
  <li>Contribuer à une meilleure pratique de la religion musulmane ;</li>
  <li>Former des citoyens capables de promouvoir l’islam ;</li>
  <li>Constituer un pôle d’excellence dans les études scolaires et universitaires ;</li>
  <li>Contribuer à la promotion des droits de l’homme ;</li>
  <li>Lutter contre les fléaux sociaux (VIH/SIDA et les autres pandémies, pauvreté, etc.)</li>
  <li>Lutter pour une scolarisation des enfants et surtout de la jeune fille ;</li>
  <li>Lutter contre le chômage des diplômés.</li>
  <li>Contribuer auprès des autorités à la lutte contre la désertification et le réchauffement climatique.</li>
  <li>Contribuer à la promotion de la paix, de la cohésion sociale, de la solidarité et de la tolérance.</li>
</ol>
                      </p>
                    </div>
                  </div>
                  
                  <div class="current-programs">
                    <h3>Programmes actuels</h3>
                    <div class="programs-grid">
                      <div class="program-card">
                        <div class="program-icon">📚</div>
                        <div class="program-info">
                          <h4>SENAFOI</h4>
                          <p>Séminaire National de Formation Islamique, organisé chaque année dans différentes régions</p>
                        </div>
                      </div>
                      <div class="program-card">
                        <div class="program-icon">🎓</div>
                        <div class="program-info">
                          <h4>CIMA</h4>
                          <p>Conseil des Imams de L'AEEMCI</p>
                        </div>
                      </div>
                      <div class="program-card">
                        <div class="program-icon">🌱</div>
                        <div class="program-info">
                          <h4>JMA</h4>
                          <p>Journée de la militante Aeemciste</p>
                        </div>
                      </div>
                      <div class="program-card">
                        <div class="program-icon">❤️</div>
                        <div class="program-info">
                          <h4>Actions Sociales</h4>
                          <p>Initiatives humanitaires et sociales pour les communautés défavorisées</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="cta-box">
                    <h3>Rejoignez l'aventure AEEMCI</h3>
                    <p>Vous souhaitez faire partie de cette histoire et contribuer à façonner l'avenir de notre association ? Rejoignez-nous dès aujourd'hui !</p>
                    <div class="cta-buttons">
                      <a href="#" class="btn btn-primary">Devenir membre</a>
                      <a href="#" class="btn btn-outline">En savoir plus</a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
          
          <!-- Témoignages -->
          <section class="testimonials-section">
            <h2 class="section-title"><span class="highlight">T</span>émoignages d'anciens membres</h2>
            
            <div class="testimonials-slider" ref="testimonialSlider">
              <div class="slider-track" :style="{ transform: `translateX(-${currentTestimonial * 33.33}%)` }">
                <div class="testimonial-card" v-for="(testimonial, index) in testimonials" :key="index">
                  <div class="testimonial-content">
                    <div class="testimonial-quote">❝</div>
                    <p>{{ testimonial.text }}</p>
                    <div class="testimonial-author">
                      <div class="testimonial-avatar">
                        <img :src="testimonial.avatar" alt="Avatar" />
                      </div>
                      <div class="testimonial-info">
                        <div class="testimonial-name">{{ testimonial.name }}</div>
                        <div class="testimonial-role">{{ testimonial.role }}</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <button @click="prevTestimonial" class="testimonial-nav prev">❮</button>
              <button @click="nextTestimonial" class="testimonial-nav next">❯</button>
            </div>
            
            <div class="testimonial-dots">
              <span 
                v-for="(_, index) in testimonials" 
                :key="index" 
                :class="{ active: currentTestimonial === index }"
                @click="setTestimonial(index)"
                class="testimonial-dot"
              ></span>
            </div>
          </section>
          
        </div>
      </main>
  
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
  
  // État pour les dropdowns
  const dropdowns = ref({
    aeemci: true, // Ouvert par défaut car nous sommes sur la page Histoire
    activites: false,
    mediatheque: false
  });
  
  // Année actuelle pour le copyright et la section "Aujourd'hui"
  const currentYear = computed(() => {
    return new Date().getFullYear();
  });
  
  // Périodes de la timeline
  const timelinePeriods = [
    { year: '1972', label: 'Naissance' },
    { year: '1975', label: 'Réconnaissance' },
    { year: '2015', label: '40 ans' },
    { year: currentYear.value, label: '50 ans après' }
  ];
  
  const activePeriod = ref(0);
  const periodRefs = ref([]);
  
  // Fonction pour faire défiler jusqu'à une période
  const scrollToPeriod = (index) => {
    activePeriod.value = index;
    periodRefs.value[index].scrollIntoView({ behavior: 'smooth', block: 'start' });
  };
  
  // Animation des compteurs
  const animateCounters = () => {
    const counters = document.querySelectorAll('.animate-count .stat-number');
    
    counters.forEach(counter => {
      const target = parseInt(counter.getAttribute('data-count'));
      const duration = 2000; // ms
      const step = target / (duration / 16); // 60fps
      let current = 0;
      
      const updateCounter = () => {
        current += step;
        if (current < target) {
          counter.textContent = Math.ceil(current);
          requestAnimationFrame(updateCounter);
        } else {
          counter.textContent = target;
          if (target >= 1000) {
            counter.textContent = target.toLocaleString('fr-FR');
          } else {
            counter.textContent = target;
          }
        }
      };
      
      updateCounter();
    });
  };
  
  // Témoignages
  const testimonials = [
    {
      text: "L'AEEMCI a été pour moi une véritable école de vie. J'y ai appris non seulement à approfondir ma foi, mais aussi à développer des compétences en leadership qui me servent aujourd'hui dans ma carrière professionnelle.",
      name: "Dr. Abdoul Kader Keita",
      role: "Ancien président (1975-1978)",
      avatar: "https://i.ibb.co/YQnkf7L/islamic-conference.jpg"
    },
    {
      text: "Grâce à l'AEEMCI, j'ai pu concilier mes études universitaires avec une solide éducation islamique. Les formations et les séminaires m'ont permis de rester connecté à mes valeurs tout en poursuivant mon parcours académique avec excellence.",
      name: "Ibrahim Touré",
      role: "Membre depuis 2005, Ingénieur",
      avatar: "https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg"
    },
    {
      text: "Mon passage à l'AEEMCI a été déterminant dans mon parcours. J'y ai trouvé une famille, un cadre d'épanouissement et des mentors qui m'ont guidée. Aujourd'hui, je suis fière de transmettre ces valeurs à la nouvelle génération.",
      name: "Fatima Diallo",
      role: "Ancienne secrétaire générale (2010-2012)",
      avatar: "https://i.ibb.co/Qj9JNHW/quran-recitation.jpg"
    }
  ];
  
  const currentTestimonial = ref(0);
  let testimonialInterval;
  
  const setTestimonial = (index) => {
    currentTestimonial.value = index;
  };
  
  const nextTestimonial = () => {
    currentTestimonial.value = (currentTestimonial.value + 1) % testimonials.length;
  };
  
  const prevTestimonial = () => {
    currentTestimonial.value = (currentTestimonial.value - 1 + testimonials.length) % testimonials.length;
  };
  
  const startTestimonialSlider = () => {
    testimonialInterval = setInterval(() => {
      nextTestimonial();
    }, 5000);
  };
  
  // Galerie photos
  const galleryImages = [
    {
      thumbnail: "https://i.ibb.co/YQnkf7L/islamic-conference.jpg",
      full: "https://i.ibb.co/YQnkf7L/islamic-conference.jpg",
      caption: "Première assemblée générale de l'AEEMCI",
      year: "1972"
    },
    {
      thumbnail: "https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg",
      full: "https://i.ibb.co/Jj1Bv8S/islamic-charity.jpg",
      caption: "Séminaire national de formation",
      year: "1985"
    },
    {
      thumbnail: "https://i.ibb.co/Qj9JNHW/quran-recitation.jpg",
      full: "https://i.ibb.co/Qj9JNHW/quran-recitation.jpg",
      caption: "Concours de récitation du Coran",
      year: "1990"
    },
    {
      thumbnail: "https://i.ibb.co/Lk5M0bL/islamic-students.jpg",
      full: "https://i.ibb.co/Lk5M0bL/islamic-students.jpg",
      caption: "Membres de l'AEEMCI lors d'une action sociale",
      year: "2000"
    },
    {
      thumbnail: "https://i.ibb.co/Qf7mZwV/islamic-education.jpg",
      full: "https://i.ibb.co/Qf7mZwV/islamic-education.jpg",
      caption: "Célébration du 40ème anniversaire",
      year: "2012"
    },
    {
      thumbnail: "https://i.ibb.co/YQnkf7L/islamic-conference.jpg",
      full: "https://i.ibb.co/YQnkf7L/islamic-conference.jpg",
      caption: "Conférence internationale sur l'éducation islamique",
      year: "2018"
    }
  ];
  
  const lightboxOpen = ref(false);
  const currentImage = ref(0);
  
  const openLightbox = (index) => {
    currentImage.value = index;
    lightboxOpen.value = true;
    document.body.style.overflow = 'hidden';
  };
  
  const closeLightbox = () => {
    lightboxOpen.value = false;
    document.body.style.overflow = 'auto';
  };
  
  const nextImage = () => {
    currentImage.value = (currentImage.value + 1) % galleryImages.length;
  };
  
  const prevImage = () => {
    currentImage.value = (currentImage.value - 1 + galleryImages.length) % galleryImages.length;
  };
  
  // Observer pour détecter quelle section est visible
  const observePeriods = () => {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          const id = entry.target.id;
          const index = parseInt(id.split('-')[1]);
          activePeriod.value = index;
        }
      });
    }, { threshold: 0.5 });
    
    periodRefs.value.forEach(el => {
      observer.observe(el);
    });
  };
  
  // Initialisation des animations au chargement de la page
  onMounted(() => {
    // Récupérer les références des périodes
    periodRefs.value = Array.from(document.querySelectorAll('.period-card'));
    
    // Observer les périodes pour la navigation
    observePeriods();
    
    // Fermer les dropdowns quand on clique en dehors
    document.addEventListener('click', (event) => {
      const isDropdownButton = event.target.closest('.dropdown');
      if (!isDropdownButton) {
        Object.keys(dropdowns.value).forEach(key => {
          dropdowns.value[key] = false;
        });
      }
    });
    
    // Observer pour déclencher les animations au scroll
    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-visible');
          
          // Si c'est la section des statistiques, démarrer l'animation des compteurs
          if (entry.target.classList.contains('stats-grid')) {
            animateCounters();
          }
        }
      });
    }, { threshold: 0.1 });
    
    // Observer les éléments avec animation
    document.querySelectorAll('.period-card, .intro-section, .testimonials-section, .gallery-section, .stats-grid').forEach(el => {
      observer.observe(el);
    });
    
    // Démarrer le slider de témoignages
    startTestimonialSlider();
    
    // Écouter les touches du clavier pour la galerie
    document.addEventListener('keydown', (e) => {
      if (!lightboxOpen.value) return;
      
      if (e.key === 'Escape') {
        closeLightbox();
      } else if (e.key === 'ArrowRight') {
        nextImage();
      } else if (e.key === 'ArrowLeft') {
        prevImage();
      }
    });
  });
  
  onBeforeUnmount(() => {
    // Nettoyer les intervalles
    if (testimonialInterval) {
      clearInterval(testimonialInterval);
    }
    
    // Nettoyer les écouteurs d'événements
    document.removeEventListener('keydown', () => {});
  });
  </script>
  <script>
  // Pour respecter la règle multi-word
  export default {
    name: 'HistoRique'
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
  
  .btn {
    display: inline-block;
    padding: 0.6rem 1.2rem;
    border-radius: 4px;
    font-weight: 600;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    font-size: 0.9rem;
    letter-spacing: 0.3px;
  }
  
  .btn-primary {
    background-color: var(--primary);
    color: white;
    box-shadow: 0 2px 4px rgba(0, 100, 0, 0.1);
  }
  
  .btn-primary:hover {
    background-color: var(--primary-dark);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 3px 6px rgba(0, 100, 0, 0.15);
  }
  
  .btn-outline {
    background-color: transparent;
    border: 1px solid var(--primary);
    color: var(--primary);
  }
  
  .btn-outline:hover {
    background-color: var(--primary);
    color: white;
    transform: translateY(-2px);
  }
  
  /* Header Styles */
  .header {
    background-color: white;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
  }
  
  .header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.8rem 0;
  }
  
  .logo {
    display: flex;
    align-items: center;
  }
  
  .logo-img {
    width: 35px;
    height: 35px;
    object-fit: contain;
    transition: transform 0.3s ease;
  }
  
  .logo-text {
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--primary);
    margin-left: 0.3rem;
  }
  
  .main-nav {
    flex-grow: 1;
    display: flex;
    justify-content: flex-end;
  }
  
  .nav-list {
    display: flex;
    list-style: none;
    margin: 0;
    padding: 0;
    align-items: center;
  }
  
  .nav-item {
    position: relative;
    margin-left: 1.2rem;
  }
  
  .nav-link {
    color: var(--text-primary);
    font-weight: 500;
    padding: 0.4rem 0;
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    transition: all 0.2s ease;
  }
  
  .nav-link.active {
    color: var(--primary);
    font-weight: 600;
  }
  
  .nav-link:hover {
    color: var(--primary);
  }
  
  .dropdown-arrow {
    font-size: 0.5rem;
    margin-left: 0.2rem;
    opacity: 0.7;
  }
  
  .dropdown-menu {
    position: absolute;
    top: 100%;
    left: 0;
    background-color: white;
    min-width: 160px;
    box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    border-radius: 3px;
    padding: 0.4rem 0;
    z-index: 100;
    list-style: none;
    transform-origin: top center;
    animation: dropdown-anim 0.2s ease;
    border: 1px solid var(--border);
  }
  
  .dropdown-menu a.active {
    background-color: var(--background-alt);
    color: var(--primary);
    font-weight: 600;
  }
  
  @keyframes dropdown-anim {
    from {
      opacity: 0;
      transform: translateY(-6px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  
  .dropdown-menu a {
    display: block;
    padding: 0.4rem 0.8rem;
    color: var(--text-primary);
    font-size: 0.8rem;
    transition: all 0.2s ease;
  }
  
  .dropdown-menu a:hover {
    background-color: var(--background-alt);
    color: var(--primary);
    padding-left: 1rem;
  }
  
  .nav-buttons {
    display: flex;
    align-items: center;
    gap: 0.6rem;
  }
  
  .btn-donate, .btn-espace {
    padding: 0.3rem 0.7rem;
    border-radius: 2px;
    font-size: 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
  }
  
  .btn-donate {
    background-color: var(--secondary);
    color: var(--text-primary);
  }
  
  .btn-donate:hover {
    background-color: var(--secondary-dark);
    transform: translateY(-2px);
  }
  
  .btn-espace {
    background-color: var(--accent);
    color: white;
  }
  
  .btn-espace:hover {
    background-color: #d35400;
    transform: translateY(-2px);
  }
  
  .mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1.1rem;
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
  
  /* Main Content */
  .main-content {
    padding: 4rem 0;
    background-color: var(--background);
  }
  
  /* Introduction Section */
  .intro-section {
    display: flex;
    gap: 2rem;
    margin-bottom: 4rem;
    align-items: center;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }
  
  .quote-box {
    flex: 0 0 40%;
    background-color: var(--primary-lighter);
    padding: 2rem;
    border-radius: 8px;
    position: relative;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  }
  
  .quote-mark {
    position: absolute;
    top: 1rem;
    left: 1rem;
    font-size: 4rem;
    color: var(--primary);
    opacity: 0.2;
    line-height: 1;
  }
  
  blockquote {
    font-size: 1.2rem;
    font-style: italic;
    line-height: 1.6;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
    color: var(--primary-dark);
  }
  
  .quote-author {
    text-align: right;
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--primary);
  }
  
  .intro-text {
    flex: 1;
  }
  
  .intro-text p {
    margin-bottom: 1rem;
    font-size: 1rem;
    line-height: 1.8;
  }
  
  /* Timeline Navigation */
  .timeline-nav {
    margin-bottom: 3rem;
  }
  
  .timeline-track {
    position: relative;
    display: flex;
    justify-content: space-between;
    padding: 2rem 0;
  }
  
  .timeline-line {
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 2px;
    background-color: var(--primary-lighter);
    z-index: 1;
  }
  
  .timeline-point {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    align-items: center;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .point-year {
    width: 60px;
    height: 60px;
    background-color: var(--primary-lighter);
    color: var(--primary);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    margin-bottom: 0.5rem;
    transition: all 0.3s ease;
    font-size: 0.9rem;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  }
  
  .timeline-point.active .point-year {
    background-color: var(--primary);
    color: white;
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }
  
  .point-label {
    font-size: 0.9rem;
    font-weight: 600;
    color: var(--text-secondary);
    transition: all 0.3s ease;
  }
  
  .timeline-point.active .point-label {
    color: var(--primary);
  }
  
  .timeline-point:hover .point-year {
    transform: scale(1.1);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
  }
  
  /* Period Cards */
  .period-card {
    margin-bottom: 4rem;
    background-color: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    opacity: 0;
    transform: translateY(30px);
    transition: opacity 0.8s ease, transform 0.8s ease;
  }
  
  .period-header {
    background-color: var(--primary);
    color: white;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
  }
  
  .period-year {
    font-size: 2rem;
    font-weight: 700;
    line-height: 1;
    padding: 0.8rem 1.2rem;
    background-color: rgba(255, 255, 255, 0.1);
    border-radius: 8px;
  }
  
  .period-title {
    font-size: 1.8rem;
    margin: 0;
  }
  
  .period-content {
    padding: 2rem;
  }
  
  .period-gallery {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
    margin-bottom: 2rem;
  }
  
  .period-gallery .gallery-item {
    border-radius: 8px;
    overflow: contain;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
  }
  
  .period-gallery .gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .period-gallery img {
    width: 100%;
    height: 180px;
    object-fit: contain;
    transition: transform 0.5s ease;
  }
  
  .period-gallery .gallery-item:hover img {
    transform: scale(1.05);
  }
  
  .image-caption {
    background-color: var(--primary);
    color: white;
    padding: 0.5rem;
    font-size: 0.8rem;
    text-align: center;
  }
  
  .period-text {
    line-height: 1.8;
  }
  
  .period-text p {
    margin-bottom: 1.5rem;
    font-size: 1rem;
  }
  
  .period-text h3 {
    font-size: 1.4rem;
    color: var(--primary);
    margin: 2rem 0 1rem;
    position: relative;
    padding-bottom: 0.5rem;
  }
  
  .period-text h3::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 2px;
    background-color: var(--primary);
    border-radius: 2px;
  }
  
  /* Key Event */
  .key-event {
    background-color: var(--primary-lighter);
    border-left: 4px solid var(--primary);
    padding: 1.5rem;
    margin: 2rem 0;
    border-radius: 0 8px 8px 0;
  }
  
  .event-date {
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.5rem;
    font-size: 1.2rem;
  }
  
  .event-description p {
    margin-bottom: 0.8rem;
  }
  
  /* Key Figures */
  .key-figures {
    margin: 2rem 0;
  }
  
  .figures-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .figure-card {
    display: flex;
    align-items: center;
    background-color: var(--background-alt);
    padding: 1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  
  .figure-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .figure-avatar {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 1rem;
    border: 2px solid var(--primary);
  }
  
  .figure-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .figure-name {
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--primary);
    margin-bottom: 0.3rem;
  }
  
  .figure-role {
    font-size: 0.85rem;
    color: var(--text-secondary);
  }
  
  /* Milestone List */
  .milestone-list {
    margin: 2rem 0;
  }
  
  .milestone-list ul {
    list-style: none;
  }
  
  .milestone-list li {
    display: flex;
    margin-bottom: 1rem;
    padding-left: 2rem;
    position: relative;
  }
  
  .milestone-list li::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0.5rem;
    width: 10px;
    height: 10px;
    background-color: var(--primary);
    border-radius: 50%;
  }
  
  .milestone-year {
    font-weight: 700;
    color: var(--primary);
    margin-right: 1rem;
    min-width: 50px;
  }
  
  /* Impact Box */
  .impact-box {
    background-color: var(--primary-lighter);
    padding: 1.5rem;
    border-radius: 8px;
    margin: 2rem 0;
  }
  
  /* Stats Grid */
  .stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin: 2rem 0;
  }
  
  .stat-item {
    text-align: center;
    padding: 1.5rem;
    background-color: var(--background-alt);
    border-radius: 8px;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .stat-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .stat-label {
    font-size: 0.9rem;
    color: var(--text-secondary);
  }
  
  /* Vision & Mission */
  .vision-mission {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
    margin: 2rem 0;
  }
  
  .vision-box, .mission-box {
    background-color: var(--background-alt);
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
  }
  
  .vision-box:hover, .mission-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  /* Achievement Box */
  .achievement-box {
    margin: 2rem 0;
  }
  
  .achievements-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .achievement-card {
    display: flex;
    align-items: flex-start;
    background-color: var(--background-alt);
    padding: 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  
  .achievement-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .achievement-icon {
    font-size: 2rem;
    margin-right: 1rem;
    color: var(--primary);
  }
  
  .achievement-text h4 {
    font-size: 1.1rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .achievement-text p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0;
  }
  
  /* Current Programs */
  .current-programs {
    margin: 2rem 0;
  }
  
  .programs-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1.5rem;
  }
  
  .program-card {
    display: flex;
    align-items: flex-start;
    background-color: var(--background-alt);
    padding: 1.5rem;
    border-radius: 8px;
    transition: all 0.3s ease;
  }
  
  .program-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .program-icon {
    font-size: 2rem;
    margin-right: 1rem;
    color: var(--primary);
  }
  
  .program-info h4 {
    font-size: 1.1rem;
    color: var(--primary);
    margin-bottom: 0.5rem;
  }
  
  .program-info p {
    font-size: 0.9rem;
    color: var(--text-secondary);
    margin-bottom: 0;
  }
  
  /* CTA Box */
  .cta-box {
    background-color: var(--primary);
    color: white;
    padding: 2rem;
    border-radius: 8px;
    text-align: center;
    margin-top: 3rem;
  }
  
  .cta-box h3 {
    font-size: 1.5rem;
    margin-bottom: 1rem;
  }
  
  .cta-box p {
    margin-bottom: 1.5rem;
    font-size: 1rem;
  }
  
  .cta-buttons {
    display: flex;
    justify-content: center;
    gap: 1rem;
  }
  
  .cta-box .btn-outline {
    border-color: white;
    color: white;
  }
  
  .cta-box .btn-outline:hover {
    background-color: white;
    color: var(--primary);
  }
  
  .cta-box .btn-primary {
    background-color: white;
    color: var(--primary);
  }
  
  .cta-box .btn-primary:hover {
    background-color: var(--background-alt);
  }
  
  /* Testimonials */
  .testimonials-section {
    margin: 4rem 0;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  
  .section-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 2rem;
    position: relative;
    padding-bottom: 1rem;
  }
  
  .section-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 3px;
    background-color: var(--primary);
    border-radius: 3px;
  }
  
  .testimonials-slider {
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
  }
  
  .slider-track {
    display: flex;
    transition: transform 0.5s ease;
  }
  
  .testimonial-card {
    flex: 0 0 33.33%;
    padding: 0 1rem;
  }
  
  .testimonial-content {
    background-color: var(--background-alt);
    padding: 2rem;
    border-radius: 8px;
    position: relative;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
    height: 100%;
  }
  
  .testimonial-quote {
    position: absolute;
    top: 1rem;
    left: 1rem;
    font-size: 3rem;
    color: var(--primary);
    opacity: 0.2;
    line-height: 1;
  }
  
  .testimonial-content p {
    font-style: italic;
    margin-bottom: 1.5rem;
    font-size: 1rem;
    line-height: 1.6;
    position: relative;
    z-index: 1;
  }
  
  .testimonial-author {
    display: flex;
    align-items: center;
  }
  
  .testimonial-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 1rem;
    border: 2px solid var(--primary);
  }
  
  .testimonial-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .testimonial-name {
    font-weight: 700;
    font-size: 1rem;
    color: var(--primary);
  }
  
  .testimonial-role {
    font-size: 0.85rem;
    color: var(--text-secondary);
  }
  
  .testimonial-dots {
    display: flex;
    justify-content: center;
    gap: 0.5rem;
    margin-top: 1rem;
  }
  
  .testimonial-dot {
    width: 10px;
    height: 10px;
    border-radius: 50%;
    background-color: var(--border);
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  .testimonial-dot.active {
    background-color: var(--primary);
    transform: scale(1.2);
  }
  
  .testimonial-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(0, 100, 0, 0.5);
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
    transition: background 0.3s ease;
    z-index: 10;
  }
  
  .testimonial-nav:hover {
    background: rgba(0, 100, 0, 0.8);
  }
  
  .testimonial-nav.prev {
    left: 10px;
  }
  
  .testimonial-nav.next {
    right: 10px;
  }
  
  /* Gallery */
  .gallery-section {
    margin: 4rem 0 2rem;
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.6s ease, transform 0.6s ease;
  }
  
  .gallery-masonry {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
  }
  
  .gallery-masonry .gallery-item {
    border-radius: 8px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
  }
  
  .gallery-masonry .gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.1);
  }
  
  .gallery-image {
    position: relative;
    overflow: hidden;
  }
  
  .gallery-image img {
    width: 100%;
    height: 220px;
    object-fit: contain;
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
  
  .image-year {
    font-weight: 700;
    font-size: 1.2rem;
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
    z-index: 2000;
  }
  
  .lightbox-content {
    position: relative;
    max-width: 90%;
    max-height: 90%;
  }
  
  .lightbox-img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 4px;
  }
  
  .lightbox-caption {
    color: white;
    text-align: center;
    padding: 1rem 0;
    font-size: 1rem;
  }
  
  .lightbox-year {
    font-weight: 700;
    font-size: 1.2rem;
    margin-bottom: 0.3rem;
  }
  
  .lightbox-close {
    position: absolute;
    top: -40px;
    right: 0;
    background: none;
    border: none;
    color: white;
    font-size: 2rem;
    cursor: pointer;
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
    transition: background 0.3s ease;
  }
  
  .lightbox-nav:hover {
    background: rgba(0, 0, 0, 0.8);
  }
  
  .lightbox-nav.prev {
    left: -60px;
  }
  
  .lightbox-nav.next {
    right: -60px;
  }
  
  /* Animations */
  .animate-visible {
    opacity: 1;
    transform: translate(0);
  }
  
  /* Footer */
  .footer {
    background-color: #333;
    color: white;
    padding: 3rem 0 1rem;
    position: relative;
    overflow: hidden;
  }
  
  .footer-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    opacity: 0.1;
  }
  
  .footer-content {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin-bottom: 2rem;
    position: relative;
    z-index: 1;
  }
  
  .footer-logo {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
  }
  
  .footer-description {
    font-size: 0.9rem;
    opacity: 0.8;
    line-height: 1.6;
  }
  
  .footer-title {
    font-size: 1.1rem;
    margin-bottom: 1.2rem;
    position: relative;
    padding-bottom: 0.5rem;
  }
  
  .footer-title::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 30px;
    height: 2px;
    background-color: var(--primary);
    border-radius: 2px;
  }
  
  .footer-links {
    list-style: none;
    padding: 0;
  }
  
  .footer-links li {
    margin-bottom: 0.6rem;
  }
  
  .footer-links a {
    color: white;
    opacity: 0.8;
    transition: all 0.3s ease;
    font-size: 0.9rem;
  }
  
  .footer-links a:hover {
    opacity: 1;
    padding-left: 3px;
  }
  
  .contact-info {
    list-style: none;
    padding: 0;
  }
  
  .contact-info li {
    display: flex;
    align-items: flex-start;
    margin-bottom: 1rem;
    font-size: 0.9rem;
  }
  
  .opening-hours {
    list-style: none;
    padding: 0;
  }
  
  .opening-hours li {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.6rem;
    font-size: 0.9rem;
  }
  
  .footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.85rem;
    position: relative;
    z-index: 1;
  }
  
  .footer-nav {
    display: flex;
    gap: 1.5rem;
  }
  
  .footer-nav a {
    color: white;
    opacity: 0.8;
    transition: opacity 0.3s ease;
  }
  
  .footer-nav a:hover {
    opacity: 1;
  }
  
  /* Responsive Styles */
  @media (max-width: 992px) {
    .intro-section {
      flex-direction: column;
    }
    
    .quote-box {
      width: 100%;
    }
    
    .stats-grid, .figures-grid, .achievements-grid, .programs-grid {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .gallery-masonry {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .footer-content {
      grid-template-columns: repeat(2, 1fr);
    }
    
    .testimonial-card {
      flex: 0 0 50%;
    }
    
    .slider-track {
      transform: translateX(-${currentTestimonial * 50}%) !important;
    }
  }
  
  @media (max-width: 768px) {
    .mobile-menu-btn {
      display: block;
    }
    
    .main-nav {
      position: fixed;
      top: 60px;
      left: 0;
      right: 0;
      background-color: white;
      box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
      padding: 1rem;
      display: none;
      flex-direction: column;
    }
    
    .main-nav.mobile-open {
      display: flex;
      animation: slide-down 0.3s ease;
    }
    
    @keyframes slide-down {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .nav-list {
      flex-direction: column;
      width: 100%;
      margin-bottom: 1rem;
    }
    
    .nav-item {
      margin: 0;
      margin-bottom: 0.8rem;
    }
    
    .dropdown-menu {
      position: static;
      box-shadow: none;
      padding-left: 1rem;
      animation: none;
      border: none;
      border-left: 2px solid var(--primary);
    }
    
    .nav-buttons {
      width: 100%;
      justify-content: space-between;
    }
    
    .btn-donate, .btn-espace {
      width: 48%;
      text-align: center;
    }
    
    .page-title h1 {
      font-size: 2.2rem;
    }
    
    .period-gallery {
      grid-template-columns: 1fr;
    }
    
    .vision-mission {
      grid-template-columns: 1fr;
    }
    
    .stats-grid, .figures-grid, .achievements-grid, .programs-grid {
      grid-template-columns: 1fr;
    }
    
    .gallery-masonry {
      grid-template-columns: 1fr;
    }
    
    .testimonial-card {
      flex: 0 0 100%;
    }
    
    .slider-track {
      transform: translateX(-${currentTestimonial * 100}%) !important;
  }
    
    .lightbox-nav.prev {
      left: 10px;
    }
    
    .lightbox-nav.next {
      right: 10px;
    }
    
    .footer-content {
      grid-template-columns: 1fr;
    }
    
    .footer-bottom {
      flex-direction: column;
      gap: 1rem;
      text-align: center;
    }
    
    .footer-nav {
      flex-direction: column;
      gap: 0.6rem;
    }
  }
  
  @media (max-width: 576px) {
    .page-title h1 {
      font-size: 1.8rem;
    }
    
    .timeline-track {
      overflow-x: auto;
      padding-bottom: 1rem;
    }
    
    .timeline-point {
      margin: 0 1rem;
    }
    
    .point-year {
      width: 50px;
      height: 50px;
      font-size: 0.8rem;
    }
    
    .period-header {
      flex-direction: column;
      align-items: flex-start;
      gap: 0.5rem;
    }
    
    .period-year {
      font-size: 1.5rem;
      padding: 0.5rem 0.8rem;
    }
    
    .period-title {
      font-size: 1.5rem;
    }
    
    .cta-buttons {
      flex-direction: column;
      gap: 1rem;
    }
  }

  .page-banner {
  position: relative;
  overflow: hidden;
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

/* Assurez-vous que le contenu de la bannière reste au-dessus des animations */
.page-title {
  position: relative;
  z-index: 2;
}
  </style>