<template>
  <div class="privacy-page">
    <!-- Hero Banner -->
    <section class="hero-banner">
      <div class="hero-background">
        <div class="pattern pattern-1"></div>
        <div class="pattern pattern-2"></div>
        <div class="pattern pattern-3"></div>
      </div>
      
      <!-- Islamic Patterns -->
      <div class="islamic-patterns">
        <div v-for="n in 8" :key="`star-${n}`" class="pattern-star" :style="starStyle(n)"></div>
        <div v-for="n in 5" :key="`crescent-${n}`" class="pattern-crescent" :style="crescentStyle(n)"></div>
        <div v-for="n in 10" :key="`geo-${n}`" class="pattern-geometric" :style="geometricStyle(n)"></div>
      </div>
      
      <div class="hero-container">
        <div class="hero-content">
          <h1>Politique de <span class="gradient-text">Confidentialité</span></h1>
          <p class="hero-subtitle">Protection de vos données personnelles</p>
          <div class="breadcrumbs">
            <a href="#">Accueil</a>
            <span class="separator">→</span>
            <span class="current">Politique de Confidentialité</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Main Content -->
    <main class="main-content">
      <div class="content-container">
        <!-- Intro Section -->
        <section class="intro-section">
          <div class="intro-card" style="width: 100%;">
            <div class="intro-icon">🔒</div>
            <h2>Votre vie privée nous tient à cœur</h2>
            <p class="intro-text">
              L'AEEMCI s'engage à protéger vos données personnelles conformément à la règlementation en vigueur 
              en Côte d'Ivoire, notamment la Loi n° 2013-450 du 19 juin 2013 relative à la protection des 
              données à caractère personnel.
            </p>
            <div class="update-info">
              <Ionicons name="calendar-outline" size="18" />
              <span>Dernière mise à jour : {{ lastUpdate }}</span>
            </div>
          </div>
        </section>

        <!-- Quick Navigation -->
        <section class="quick-nav">
          <h3>Navigation rapide</h3>
          <div class="nav-grid">
            <a 
              v-for="(section, index) in sections" 
              :key="index"
              :href="`#section-${index}`"
              class="nav-card"
              @click.prevent="scrollToSection(index)"
            >
              <div class="nav-icon">{{ section.icon }}</div>
              <span>{{ section.title }}</span>
            </a>
          </div>
        </section>

        <!-- Sections Content -->
        <section class="policy-sections">
          <div 
            v-for="(section, index) in sections" 
            :key="index"
            :id="`section-${index}`"
            class="policy-section"
          >
            <div class="section-header">
              <div class="section-number">{{ index + 1 }}</div>
              <h2>
                <span class="first-letter">{{ section.title.charAt(0) }}</span>{{ section.title.slice(1) }}
              </h2>
            </div>
            <div class="title-line"></div>
            
            <div class="section-content" v-html="section.content"></div>

            <!-- Subsections if any -->
            <div v-if="section.subsections" class="subsections">
              <div 
                v-for="(subsection, subIndex) in section.subsections"
                :key="subIndex"
                class="subsection"
              >
                <h3>{{ subsection.title }}</h3>
                <div v-html="subsection.content"></div>
              </div>
            </div>
          </div>
        </section>

        <!-- Contact CTA -->
        <section class="contact-cta">
          <div class="cta-card">
            <div class="cta-icon">💬</div>
            <h2>Des questions sur vos données ?</h2>
            <p>Notre équipe est disponible pour répondre à toutes vos préoccupations concernant la confidentialité et la protection de vos données.</p>
            <div class="cta-actions">
              <a href="/contact" class="btn btn-primary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M20 4H4c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                </svg>
                Nous contacter
              </a>
              <a href="mailto:aeemci@yahoo.fr" class="btn btn-outline">
                aeemci@yahoo.fr
              </a>
            </div>
          </div>
        </section>

        <!-- Rights Summary -->
        <section class="rights-section">
          <h2>
            <span class="first-letter">V</span>os droits en un coup d'œil
          </h2>
          <div class="title-line"></div>
          <div class="rights-grid">
            <div v-for="(right, index) in userRights" :key="index" class="right-card">
              <div class="right-icon">{{ right.icon }}</div>
              <h3>{{ right.title }}</h3>
              <p>{{ right.description }}</p>
            </div>
          </div>
        </section>

        <!-- Download PDF -->
        <section class="download-section">
          <div class="download-card">
            <div class="download-content">
              <h3>Télécharger notre politique</h3>
              <p>Vous pouvez télécharger notre politique de confidentialité complète au format PDF pour la consulter hors ligne.</p>
              <button @click="downloadPDF" class="btn btn-secondary">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M19 12v7H5v-7H3v7c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2v-7h-2zm-6 .67l2.59-2.58L17 11.5l-5 5-5-5 1.41-1.41L11 12.67V3h2z"/>
                </svg>
                Télécharger le PDF
              </button>
            </div>
            <div class="download-illustration">
              <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="40" y="30" width="120" height="140" rx="8" fill="#018404" opacity="0.1"/>
                <rect x="50" y="40" width="100" height="120" rx="4" fill="#018404" opacity="0.2"/>
                <path d="M80 100h40M80 110h40M80 120h30" stroke="#018404" stroke-width="3" stroke-linecap="round"/>
                <circle cx="100" cy="150" r="15" fill="#fbbf24"/>
                <path d="M100 145v10M95 150l5 5 5-5" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
          </div>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const lastUpdate = ref('13 Janvier 2025')

const sections = [
  {
    icon: '📋',
    title: 'Dispositions Générales',
    content: `
      <p>L'Application AEEMCI Militant (le « service » ou « l'application ») est administrée et éditée par l'Association des Élèves et Étudiants Musulmans de Côte d'Ivoire ci-après dénommée « l'AEEMCI ou l'Association ».</p>
      <p>L'utilisation du service est soumise aux présentes conditions générales d'utilisation. Lorsque vous utilisez ce service, vous êtes engagé(e) par ces Conditions Générales d'Utilisation. Vous êtes tenu(e)s de les respecter.</p>
      <p>L'AEEMCI peut à tout moment mettre à jour ces Conditions Générales d'Utilisation et la Politique de Confidentialité. En continuant à utiliser l'application après d'éventuelles modifications, vous vous engagez à respecter les Conditions Générales d'Utilisation et à reconnaître la Politique de Confidentialité.</p>
      <div class="info-box">
        <strong>Important :</strong> L'application est destinée aux jeunes, aux entreprises, à toutes les organisations de la société civile, à toute personne âgée d'au moins quatorze (14) années.
      </div>
    `
  },
  {
    icon: '🔐',
    title: 'Sécurité et Politique de Confidentialité',
    content: `
      <p>La Politique de Confidentialité du service régit l'utilisation des informations personnelles que vous acceptez de communiquer à l'AEEMCI via cette application.</p>
      <p>Nous sommes soucieux de la protection des données personnelles qui nous sont confiées. Nous nous engageons à assurer le meilleur niveau de protection de vos données personnelles en conformité avec la règlementation en vigueur en Côte d'Ivoire, notamment la <strong>Loi n° 2013-450 du 19 juin 2013</strong> relative à la protection des données à caractère personnel.</p>
      <p>On entend par <strong>données à caractère personnel</strong>, toute information de quelque nature qu'elle soit et indépendamment de son support, y compris le son et l'image relative à une personne physique identifiée ou identifiable directement ou indirectement, par référence à un numéro d'identification ou à un ou plusieurs éléments spécifiques, propres à son identifié physique, physiologique, génétique, psychique, culturelle, sociale ou économique.</p>
    `
  },
  {
    icon: '📊',
    title: 'Traitement des Données à Caractère Personnel',
    content: `
      <p>Les données collectées feront l'objet d'un traitement informatisé. Le <strong>traitement informatisé des données à caractère personnel</strong> est toute opération ou ensemble d'opérations effectuées ou non à l'aide de procédés automatisés, et appliquées à des données, telles que :</p>
      <ul class="styled-list">
        <li><strong>La collecte</strong></li>
        <li><strong>L'exploitation et l'enregistrement</strong></li>
        <li><strong>L'organisation et la conservation</strong></li>
        <li><strong>L'adaptation et la modification</strong></li>
        <li><strong>L'extraction et la sauvegarde</strong></li>
        <li><strong>La copie et la consultation</strong></li>
        <li><strong>L'utilisation et la communication par transmission</strong></li>
        <li><strong>La diffusion ou toute autre forme de mise à disposition</strong></li>
        <li><strong>Le rapprochement ou l'interconnexion</strong></li>
        <li><strong>Le verrouillage et le cryptage</strong></li>
        <li><strong>L'effacement ou la destruction de données</strong></li>
      </ul>
      <p class="emphasis-text">Vos données sont utilisées dans le cadre de l'exécution de mesures précontractuelles ou contractuelles, la gestion et le suivi de votre compte utilisateur, la facturation des services souscrits, la réalisation d'actions commerciales et marketing, des analyses statistiques, et la gestion de vos réclamations.</p>
    `
  },
  {
    icon: '👤',
    title: 'Renseignement de l\'Email et Création de Compte',
    subsections: [
      {
        title: '📧 En l\'absence de création de Compte',
        content: `
          <p>Lorsque vous utilisez le Service, vous devez renseigner votre adresse e-mail si vous souhaitez :</p>
          <ul>
            <li>Enregistrer votre document modélisé ou votre étape dans le parcours d'un questionnaire avant paiement (vous recevrez un e-mail contenant un lien URL pour retrouver votre document)</li>
            <li>Recevoir votre document modélisé en version numérique après paiement (vous recevrez un e-mail contenant un document PDF ou WORD)</li>
          </ul>
          <p><strong>Important :</strong> Vous vous engagez à fournir une adresse e-mail valable et personnelle. L'Association ne saurait être tenue pour responsable des conséquences de la communication d'un email erroné ou inexact. Vous êtes responsable du maintien de la confidentialité de l'URL et des documents que vous recevez.</p>
          <p>Le renseignement de l'e-mail est <strong>gratuit</strong> et ne donne pas accès à d'autres services que ceux mentionnés ci-dessus, et ne crée pas automatiquement un Compte.</p>
        `
      },
      {
        title: '🔐 Création d\'un Compte',
        content: `
          <p>Vous pouvez être invité(e) à créer un Compte dans le cadre d'un Service donné. Cette création, et/ou l'utilisation d'un Service entraine l'acceptation, sans aucune réserve, des Conditions générales.</p>
          <p>Pour créer un Compte, vous devez vous inscrire en remplissant le formulaire et vous engager à fournir des informations sincères et exactes vous concernant :</p>
          <ul>
            <li>Dénomination sociale / État civil</li>
            <li>Coordonnées complètes (dont adresse email)</li>
          </ul>
          <p>Vous êtes seul responsable de la mise à jour des informations fournies. Vous pouvez les modifier à tout moment en vous connectant à votre Compte.</p>
          <div class="warning-box">
            <strong>⚠️ Attention :</strong> Votre nom d'utilisateur et mot de passe sont strictement personnels et vous seront communiqués après votre inscription. Vous vous en interdisez toute divulgation. Dans le cas contraire, vous resterez seul responsable de l'usage qui en sera fait.
          </div>
          <p>Vous pourrez solliciter votre désinscription en vous rendant à la page dédiée sur votre Compte ou en envoyant un email à l'adresse disponible sur l'Application. Cette désinscription sera effective dans un délai raisonnable.</p>
          <p>En cas de non-respect des Conditions générales, l'AEEMCI pourra suspendre voire fermer votre Compte après mise en demeure par voie électronique restée sans effet.</p>
        `
      }
    ]
  },
  {
    icon: '✉️',
    title: 'Communications et Prospection',
    content: `
      <p>En utilisant notre application, vous autorisez l'AEEMCI à vous adresser des emails dans le cadre :</p>
      <ul class="styled-list">
        <li>Des Services fournis par l'application</li>
        <li>De la prospection commerciale</li>
        <li>Des communications de nos partenaires (y compris les Partenaires professionnels)</li>
      </ul>
      <p>Les transmissions de toutes données à des partenaires tiers, à l'exclusion des Partenaires professionnels, seront subordonnées à votre consentement.</p>
      <p>Vous pouvez à tout moment demander à l'AEEMCI de supprimer votre e-mail de ses fichiers de plein droit en :</p>
      <ul>
        <li>Adressant un message à l'adresse email disponible sur l'application</li>
        <li>Suivant la procédure simple prévue à cet effet dans les emails reçus</li>
      </ul>
      <p>Cette suppression entrera en vigueur dans un délai raisonnable après réception de votre demande.</p>
    `
  },
  {
    icon: '🛡️',
    title: 'Propriété Intellectuelle',
    content: `
      <p>L'AEEMCI est seule propriétaire des éléments suivants, qui sont protégés par les lois et règlements en droit ivoirien relatifs notamment à la propriété intellectuelle :</p>
      <ul class="styled-list">
        <li>Les documents et documents modélisés qui en sont issus (y compris après mise à disposition sur support PDF ou WORD)</li>
        <li>L'application et son contenu (textes, données, images, logos, marques, etc.)</li>
        <li>Les marques, identité visuelle, charte graphique</li>
        <li>Les logiciels, bases de données et structure de l'application</li>
        <li>Tous autres éléments de propriété intellectuelle</li>
      </ul>
      <div class="warning-box">
        <strong>⚠️ Interdictions :</strong> Aucun document, document modélisé ou élément ne pourra, en tout ou partie, être reproduit, copié, dupliqué, vendu, revendu, transmis, publié, communiqué, distribué, diffusé, loué, représenté ou réutilisé sans l'autorisation préalable expresse, non équivoque et écrite de l'AEEMCI au cas par cas.
      </div>
      <p>L'utilisateur est seul responsable de toute utilisation et/ou exploitation non autorisée. Toute représentation, reproduction, adaptation ou exploitation partielle ou totale par quelque procédé que ce soit, sans l'autorisation préalable, expresse, non équivoque et écrite de l'Association est susceptible de constituer une contrefaçon au sens de la loi ivoirienne relative à la propriété intellectuelle.</p>
      <p class="emphasis-text">L'accès à l'Application ne vaut pas reconnaissance d'un droit et ne confère aucun droit de propriété intellectuelle portant sur l'un quelconque des Éléments, lesquels restent la propriété exclusive de l'Association.</p>
      <p>Vous pouvez faire une seule copie des pages Internet publiées sur le Service pour votre usage privé, personnel et non commercial, sous réserve que toute copie conserve toutes les mentions de "copyright" (droit d'auteur) et de droits exclusifs.</p>
    `
  },
  {
    icon: '⚖️',
    title: 'Limitation de Responsabilité',
    content: `
      <p>L'AEEMCI ne pourra en aucun cas être tenue responsable :</p>
      <div class="limitation-grid">
        <div class="limitation-item">
          <div class="limitation-icon">💻</div>
          <h4>Défaillances techniques</h4>
          <p>Défaillance technique ou informatique, défaut de compatibilité avec votre matériel ou logiciel</p>
        </div>
        <div class="limitation-item">
          <div class="limitation-icon">📁</div>
          <h4>Données et fichiers</h4>
          <p>Inexactitude ou corruption de fichiers ou données, destruction de vos données</p>
        </div>
        <div class="limitation-item">
          <div class="limitation-icon">🔒</div>
          <h4>Sécurité réseau</h4>
          <p>Manque de fiabilité ou défaut de sécurisation des informations circulant sur Internet</p>
        </div>
        <div class="limitation-item">
          <div class="limitation-icon">👤</div>
          <h4>Usage de l'utilisateur</h4>
          <p>Utilisation que vous faites de l'application ou de ses services</p>
        </div>
        <div class="limitation-item">
          <div class="limitation-icon">🦠</div>
          <h4>Virus et malwares</h4>
          <p>Virus, programmes d'écrasement, chevaux de Troie ou autre matériel de destruction</p>
        </div>
        <div class="limitation-item">
          <div class="limitation-icon">📄</div>
          <h4>Documents et informations</h4>
          <p>Efficacité, exhaustivité, exactitude des documents modélisés et informations</p>
        </div>
      </div>
      <div class="info-box">
        <strong>Garanties :</strong>
        <ul>
          <li>L'AEEMCI garantit la confidentialité des informations renseignées et s'interdit de les divulguer à des tiers, sauf pour les besoins de l'exécution du Service</li>
          <li>L'AEEMCI n'a dans le cadre des services qu'une obligation de moyens</li>
          <li>L'utilisateur porte l'entière responsabilité du choix, de l'utilisation, de l'interprétation et de la personnalisation des documents</li>
        </ul>
      </div>
    `,
    subsections: [
      {
        title: '⚠️ Absence de Garanties',
        content: `
          <p>L'AEEMCI ne peut garantir et affirmer que :</p>
          <ul>
            <li>L'Application est exempte de virus, programmes d'écrasement des données, chevaux de Troie ou autre matériel de destruction</li>
            <li>Les informations contenues dans le Service sont exactes, complètes ou à jour</li>
          </ul>
          <p>L'Application et son contenu sont présentés <strong>"en l'état"</strong> et <strong>"en fonction des disponibilités"</strong>. L'AEEMCI exclut expressément toute garantie de toute nature, expresse ou implicite.</p>
        `
      },
      {
        title: '⚖️ Conseil Juridique',
        content: `
          <p>L'AEEMCI et ses dirigeants, militants et partenaires professionnels non avocats ou conseillers juridiques agréés :</p>
          <ul>
            <li>Ne sont pas un cabinet d'avocats ou de conseil juridique et ne fournissent aucun conseil juridique</li>
            <li>Ne sont pas rédacteurs d'actes</li>
            <li>N'exercent pas d'activité de consultation en matière juridique, de rédaction d'actes sous seing privé ou de représentation</li>
            <li>Ne garantissent en aucun cas le contenu des modèles de documents, leur conformité, ni leur efficacité juridique</li>
          </ul>
          <p class="warning-box"><strong>Recommandation :</strong> Pour toute consultation juridique, il convient de se rapprocher d'un avocat ou de toute autre personne habilitée par la loi à faire des prestations de conseil juridique.</p>
        `
      }
    ]
  },
  {
    icon: '👶',
    title: 'Protection des Enfants',
    content: `
      <p>L'Association est soucieuse de la protection des enfants. Ainsi, elle s'efforce de se conformer pleinement aux lois et réglementations nationales en vigueur dans le cadre de la protection des enfants.</p>
      <p>Elle travaille activement à faire de l'Application un endroit plus sûr pour les enfants à travers les initiatives suivantes :</p>
      <div class="protection-grid">
        <div class="protection-item">
          <div class="protection-icon">🚫</div>
          <h4>Blocage des contenus inappropriés</h4>
          <p>À l'aide d'un logiciel tiers neutre, elle bloquera toute personne publiant des contenus à caractères sexuels sur l'Application</p>
        </div>
        <div class="protection-item">
          <div class="protection-icon">📢</div>
          <h4>Portail de signalement</h4>
          <p>Un portail de signalement confidentiel permet à toute personne de signaler en toute sécurité et de manière anonyme les abus sexuels commis sur des enfants</p>
        </div>
        <div class="protection-item">
          <div class="protection-icon">🔍</div>
          <h4>Enquêtes et collaboration</h4>
          <p>L'AEEMCI enquêtera sur tous les signalements et collaborera avec les services d'assistance téléphonique des partenaires nationaux ou les organismes internationaux chargés de l'application de la loi</p>
        </div>
        <div class="protection-item">
          <div class="protection-icon">📚</div>
          <h4>Éducation et sensibilisation</h4>
          <p>Éducation des enfants, parents, tuteurs et soignants sur la manière d'utiliser la technologie numérique de manière responsable et de rester en sécurité en ligne</p>
        </div>
      </div>
    `
  },
  {
    icon: '📝',
    title: 'Réclamations',
    content: `
      <p>Les utilisateurs doivent adresser leurs éventuelles réclamations par voie électronique à l'e-mail disponible sur l'Application.</p>
      <div class="info-box">
        <strong>Délai de réclamation :</strong> Vous disposez d'un délai de <strong>trente (30) jours</strong> à compter de la fourniture des services pour émettre, par voie électronique, des réclamations, avec tous les justificatifs y afférents, auprès de l'Association.
      </div>
      <p>Aucune réclamation ne pourra être valablement acceptée en cas de non-respect de ces formalités et délais.</p>
      <div class="warning-box">
        <strong>⚠️ Limitations :</strong> Toute réclamation portant sur les éléments suivants ne pourra pas être favorablement accueillie :
        <ul>
          <li>Les effets d'un document modélisé ou du choix d'un service</li>
          <li>Des mentions fausses ou erronées dans le cadre d'un service</li>
          <li>L'opportunité d'un service</li>
        </ul>
      </div>
    `
  },
  {
    icon: '⚡',
    title: 'Force Majeure',
    content: `
      <p>L'exécution par l'Association de ses obligations sera suspendue en cas de survenance d'un cas fortuit ou de force majeure qui en gênerait ou en retarderait l'exécution.</p>
      <p>De façon expresse sont considérés comme cas de force majeure ou cas fortuits, outre ceux habituellement retenus par la jurisprudence des Cours et Tribunaux ivoiriens :</p>
      <div class="force-majeure-grid">
        <div class="fm-item">🌪️ Intempéries</div>
        <div class="fm-item">🦠 Épidémies</div>
        <div class="fm-item">🚚 Blocages des moyens de transport</div>
        <div class="fm-item">🌍 Tremblements de terre</div>
        <div class="fm-item">🔥 Incendies</div>
        <div class="fm-item">🌊 Tempêtes et inondations</div>
        <div class="fm-item">💧 Dégâts des eaux</div>
        <div class="fm-item">⚖️ Restrictions gouvernementales</div>
        <div class="fm-item">📜 Modifications légales ou règlementaires</div>
        <div class="fm-item">📡 Blocages des télécommunications</div>
      </div>
      <p>L'AEEMCI ne saurait être tenue pour responsable de l'inexécution des Conditions générales ou des services, due à la survenance d'un cas de force majeure.</p>
      <p class="emphasis-text">L'AEEMCI et l'utilisateur auront la faculté de résilier de plein droit les Conditions générales en cas de force majeure d'une durée supérieure à deux (2) mois.</p>
    `
  },
  {
    icon: '🔗',
    title: 'Liens avec d\'Autres Applications',
    content: `
      <p>L'Application peut contenir des liens vers des sites, Applications ou Services tiers non édités par l'Association. De tels liens sont uniquement fournis à titre de commodité.</p>
      <div class="warning-box">
        <strong>⚠️ Important :</strong> Ces liens ne peuvent et ne doivent pas être interprétés comme un aval exprès ou implicite de ces sites, de leur contenu ou de tout produit ou service qui y est offert.
      </div>
      <p>Vous ne pouvez mettre en place un lien vers ce service qu'avec l'autorisation écrite et expresse de l'AEEMCI.</p>
    `
  },
  {
    icon: '📜',
    title: 'Droit Applicable et Litiges',
    content: `
      <p>Les présentes Conditions Générales d'Utilisation sont régies et interprétées conformément et exclusivement au <strong>droit positif ivoirien</strong> sans tenir compte des principes de conflits de lois.</p>
      <p>Ces Conditions Générales d'Utilisation sont écrites en français.</p>
      <p>En cas de litige, les tribunaux de droit positif ivoirien ont compétence exclusive et vous reconnaissez accepter leur compétence exclusive.</p>
      <p class="emphasis-text">L'AEEMCI se réserve le droit de requérir et d'obtenir des injonctions ou ordonnances pour la défense de ses droits aux termes des présentes.</p>
    `
  },
  {
    icon: '🔧',
    title: 'Conditions Techniques',
    content: `
      <p>Préalablement à toute utilisation des services, vous devez vous assurer que vous disposez des prérequis techniques nécessaires :</p>
      <ul class="styled-list">
        <li>Un accès Internet à haut débit</li>
        <li>Un routeur (indépendant ou intégré dans une box) disposant d'une connexion Ethernet laissée en permanence sous tension</li>
        <li>Un ordinateur équipé d'un système d'exploitation (PC Windows, Mac OS ou Linux)</li>
        <li>Un navigateur Internet à jour (Internet Explorer, Safari, Firefox, Chrome…)</li>
        <li>Un réseau GSM de qualité suffisante pour bénéficier des services de communication GPRS (si option disponible)</li>
        <li>Des logiciels permettant de lire les documents acquis au terme des services</li>
      </ul>
      <div class="warning-box">
        <strong>⚠️ Important :</strong> L'ensemble des équipements susvisés permettant l'accès aux services sont à votre charge exclusive. L'Association décline toute responsabilité en cas de non-conformité de vos équipements aux prérequis techniques. Aucune annulation ou résiliation de la commande ne sera recevable pour défaut de compatibilité.
      </div>
      <p>Vous devez également prendre toutes les mesures appropriées de façon à protéger vos propres données et/ou logiciels de la contamination par des virus circulant sur les réseaux de communications électroniques, notamment sur Internet.</p>
    `
  },
  {
    icon: '💡',
    title: 'Dispositions Diverses',
    subsections: [
      {
        title: '📢 Communication',
        content: `
          <p>Dans le respect de notre politique de confidentialité, il est reconnu que l'AEEMCI pourra communiquer, à des fins promotionnelles, sur son Application et/ou sur tout autre support de son choix, que vous avez eu recours à ses services.</p>
        `
      },
      {
        title: '✅ Sincérité des Informations',
        content: `
          <p>Les informations communiquées lors du processus de commande de services sont indispensables au bon traitement de la commande et de l'exécution des services.</p>
          <p>Vous vous engagez à fournir des informations sincères et véritables, et à les tenir à jour. L'Association ne saurait être tenue pour responsable des conséquences de la communication d'informations erronées.</p>
        `
      },
      {
        title: '✂️ Divisibilité des Clauses',
        content: `
          <p>Si une ou plusieurs stipulations des Conditions générales étaient déclarées nulles, inexistantes ou inopposables, la validité des Conditions générales n'en serait pas entachée. Les autres clauses continueront à produire leurs effets.</p>
        `
      },
      {
        title: '🚫 Renonciation',
        content: `
          <p>Le fait pour l'Association de ne pas se prévaloir, à titre temporaire ou à titre permanent, de l'Application d'une ou plusieurs stipulations des Conditions générales ne pourra en aucun cas être interprété comme :</p>
          <ul>
            <li>Une renonciation par celle-ci à ses droits pour l'avenir</li>
            <li>Dispensant l'utilisateur d'accomplir la ou les obligation(s) contenue(s) dans ladite ou lesdites stipulation(s)</li>
            <li>Une renonciation par l'Association à se prévaloir du reste des Conditions générales</li>
          </ul>
        `
      }
    ]
  }
]

const userRights = [
  {
    icon: '👁️',
    title: 'Droit d\'accès',
    description: 'Vous avez le droit d\'accéder à vos données personnelles et d\'obtenir une copie de celles-ci.'
  },
  {
    icon: '✏️',
    title: 'Droit de rectification',
    description: 'Vous pouvez demander la correction de données inexactes ou incomplètes vous concernant.'
  },
  {
    icon: '🗑️',
    title: 'Droit à l\'effacement',
    description: 'Vous pouvez demander la suppression de vos données personnelles dans certains cas.'
  },
  {
    icon: '⛔',
    title: 'Droit d\'opposition',
    description: 'Vous pouvez vous opposer au traitement de vos données pour des raisons légitimes.'
  },
  {
    icon: '🔒',
    title: 'Droit à la limitation',
    description: 'Vous pouvez demander la limitation du traitement de vos données dans certaines situations.'
  },
  {
    icon: '📧',
    title: 'Droit de désinscription',
    description: 'Vous pouvez vous désinscrire des communications commerciales à tout moment.'
  }
]

const starStyle = (n) => ({
  left: `${Math.random() * 100}%`,
  top: `${Math.random() * 100}%`,
  animationDelay: `${Math.random() * 5}s`,
  animationDuration: `${15 + Math.random() * 20}s`
})

const crescentStyle = (n) => ({
  left: `${Math.random() * 100}%`,
  top: `${Math.random() * 100}%`,
  animationDelay: `${Math.random() * 5}s`,
  animationDuration: `${15 + Math.random() * 20}s`
})

const geometricStyle = (n) => ({
  left: `${Math.random() * 100}%`,
  top: `${Math.random() * 100}%`,
  animationDelay: `${Math.random() * 5}s`,
  animationDuration: `${15 + Math.random() * 20}s`,
  transform: `rotate(${Math.random() * 360}deg)`
})

const scrollToSection = (index) => {
  const element = document.getElementById(`section-${index}`)
  if (element) {
    element.scrollIntoView({ behavior: 'smooth', block: 'start' })
  }
}

const downloadPDF = () => {
  // Logique pour télécharger le PDF
  alert('Téléchargement du PDF en cours...')
}
</script>

<script>
export default { name: 'PrivacyPolicyPage' }
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Zain:wght@300;400;600;700;900&display=swap');

* { margin: 0; padding: 0; box-sizing: border-box; }

.privacy-page {
  font-family: var(--display);
  background: linear-gradient(135deg, rgba(1,132,4,0.03), rgba(251,146,60,0.03));
}

/* Hero Banner */
.hero-banner {
  position: relative;
  background: linear-gradient(135deg, var(--green), var(--green));
  padding: 5rem 2rem 4rem;
  overflow: hidden;
}

.hero-background {
  position: absolute;
  inset: 0;
  overflow: hidden;
  pointer-events: none;
}

.pattern {
  position: absolute;
  border-radius: 50%;
  opacity: 0.1;
  animation: float 20s ease-in-out infinite;
}

.pattern-1 {
  width: 400px;
  height: 400px;
  background: radial-gradient(circle, var(--gold), transparent);
  top: -200px;
  left: -150px;
}

.pattern-2 {
  width: 500px;
  height: 500px;
  background: radial-gradient(circle, var(--gold), transparent);
  top: -100px;
  right: -200px;
  animation-delay: -5s;
}

.pattern-3 {
  width: 300px;
  height: 300px;
  background: radial-gradient(circle, var(--gold), transparent);
  bottom: -100px;
  left: 50%;
  animation-delay: -10s;
}

@keyframes float {
  0%, 100% { transform: translate(0, 0) scale(1); }
  33% { transform: translate(30px, -30px) scale(1.1); }
  66% { transform: translate(-20px, 20px) scale(0.9); }
}

/* Islamic Patterns */
.islamic-patterns {
  position: absolute;
  inset: 0;
  pointer-events: none;
  overflow: hidden;
  z-index: 1;
}

.pattern-star, .pattern-crescent, .pattern-geometric {
  position: absolute;
  opacity: 0.15;
  animation: float-pattern 25s infinite linear;
}

.pattern-star {
  width: 30px;
  height: 30px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z'/%3E%3C/svg%3E");
  background-size: contain;
}

.pattern-crescent {
  width: 25px;
  height: 25px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white'%3E%3Cpath d='M12 2a9 9 0 100 18 9 9 0 000-18zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7c-3.9 0-7 3.1-7 7s3.1 7 7 7z'/%3E%3C/svg%3E");
  background-size: contain;
}

.pattern-geometric {
  width: 35px;
  height: 35px;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100' fill='white'%3E%3Cpath d='M50 0L100 50L50 100L0 50Z'/%3E%3C/svg%3E");
  background-size: contain;
}

@keyframes float-pattern {
  0% { transform: translate(0, -100px) rotate(0deg); }
  50% { transform: translate(0, calc(100vh + 100px)) rotate(180deg); }
  100% { transform: translate(0, -100px) rotate(360deg); }
}

.hero-container {
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
  z-index: 2;
}

.hero-content {
  text-align: center;
  color: white;
}

.hero-content h1 {
  font-size: 3.5rem;
  font-weight: 900;
  margin-bottom: 1rem;
  line-height: 1.1;
}

.gradient-text {
  background: linear-gradient(135deg, var(--gold), var(--gold));
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.hero-subtitle {
  font-size: 1.3rem;
  opacity: 0.95;
  margin-bottom: 1.5rem;
}

.breadcrumbs {
  font-size: 0.95rem;
  opacity: 0.9;
}

.breadcrumbs a {
  color: white;
  text-decoration: none;
  transition: opacity 0.3s;
}

.breadcrumbs a:hover { opacity: 1; }

.separator {
  margin: 0 0.75rem;
  opacity: 0.7;
}

.current {
  font-weight: 700;
}

/* Main Content */
.main-content {
  padding: 4rem 0;
}

.content-container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 1.5rem;
}

/* Intro Section */
.intro-section {
  margin-bottom: 4rem;
}

.intro-card {
  background: white;
  border-radius: 0;
  padding: 3rem;
  text-align: center;
  box-shadow: var(--shadow);
}

.intro-icon {
  font-size: 4rem;
  margin-bottom: 1.5rem;
}

.intro-card h2 {
  font-size: 2rem;
  font-weight: 900;
  color: var(--green);
  margin-bottom: 1.5rem;
}

.intro-text {
  font-size: 1.15rem;
  line-height: 1.8;
  color: var(--ink-soft);
  max-width: 800px;
  margin: 0 auto 2rem;
}

.update-info {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  background: linear-gradient(135deg, rgba(1,132,4,0.1), rgba(251,146,60,0.1));
  border-radius: 0;
  color: var(--green);
  font-weight: 600;
  font-size: 0.95rem;
}

/* Quick Navigation */
.quick-nav {
  margin-bottom: 4rem;
}

.quick-nav h3 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--ink);
  margin-bottom: 1.5rem;
  text-align: center;
}

.nav-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.nav-card {
  background: white;
  padding: 1.5rem;
  border-radius: 0;
  text-align: center;
  text-decoration: none;
  color: var(--ink);
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
  cursor: pointer;
}

.nav-card:hover {
  transform: translateY(-5px);
  box-shadow: var(--shadow);
}

.nav-icon {
  font-size: 2.5rem;
  margin-bottom: 0.75rem;
}

.nav-card span {
  font-size: 0.95rem;
  font-weight: 600;
  display: block;
}

/* Policy Sections */
.policy-sections {
  margin-bottom: 5rem;
}

.policy-section {
  background: white;
  border-radius: 0;
  padding: 3rem;
  margin-bottom: 3rem;
  box-shadow: var(--shadow);
  scroll-margin-top: 100px;
}

.section-header {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 1rem;
}

.section-number {
  width: 60px;
  height: 60px;
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 900;
  flex-shrink: 0;
}

.section-header h2 {
  font-size: 2rem;
  font-weight: 900;
  color: var(--ink);
  margin: 0;
}

.first-letter {
  color: var(--gold);
  font-size: 2.5rem;
}

.title-line {
  width: 60px;
  height: 4px;
  background: linear-gradient(90deg, var(--green), var(--gold));
  border-radius: 0;
  margin-bottom: 2rem;
}

.section-content {
  color: var(--ink-soft);
  font-size: 1.05rem;
  line-height: 1.8;
}

.section-content p {
  margin-bottom: 1.5rem;
}

.section-content strong {
  color: var(--green);
  font-weight: 700;
}

.styled-list {
  list-style: none;
  padding-left: 0;
}

.styled-list li {
  padding-left: 2rem;
  position: relative;
  margin-bottom: 1rem;
  line-height: 1.8;
}

.styled-list li::before {
  content: '✓';
  position: absolute;
  left: 0;
  color: var(--green);
  font-weight: 900;
  font-size: 1.2rem;
}

/* Info Boxes */
.info-box, .warning-box {
  padding: 1.5rem;
  border-radius: 0;
  margin: 2rem 0;
  border-left: 4px solid;
}

.info-box {
  background: rgba(59,130,246,0.1);
  border-color: #3b82f6;
}

.warning-box {
  background: rgba(239,68,68,0.1);
  border-color: #ef4444;
}

.warning-box ul, .info-box ul {
  margin-top: 1rem;
  padding-left: 1.5rem;
}

.note-text {
  font-style: italic;
  color: var(--muted);
  margin-top: 1rem;
}

.emphasis-text {
  font-weight: 600;
  color: var(--green);
  margin-top: 1.5rem;
}

/* Subsections */
.subsections {
  margin-top: 2rem;
}

.subsection {
  background: linear-gradient(135deg, rgba(1,132,4,0.03), rgba(251,146,60,0.03));
  padding: 2rem;
  border-radius: 0;
  margin-bottom: 1.5rem;
}

.subsection h3 {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--green);
  margin-bottom: 1rem;
}

/* Protection Grid */
.protection-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.protection-item {
  background: linear-gradient(135deg, rgba(1,132,4,0.03), rgba(251,146,60,0.03));
  padding: 2rem;
  border-radius: 0;
  text-align: center;
}

.protection-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.protection-item h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--green);
  margin-bottom: 0.75rem;
}

.protection-item p {
  color: var(--muted);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Force Majeure Grid */
.force-majeure-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin: 2rem 0;
}

.fm-item {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 1.25rem;
  border-radius: 0;
  text-align: center;
  font-weight: 600;
  color: var(--ink-soft);
  transition: all 0.3s ease;
}

.fm-item:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow);
}

/* Limitation Grid */
.limitation-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin: 2rem 0;
}

.limitation-item {
  background: linear-gradient(135deg, rgba(1,132,4,0.03), rgba(251,146,60,0.03));
  padding: 2rem;
  border-radius: 0;
  text-align: center;
}

.limitation-icon {
  font-size: 2.5rem;
  margin-bottom: 1rem;
}

.limitation-item h4 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--green);
  margin-bottom: 0.75rem;
}

.limitation-item p {
  color: var(--muted);
  font-size: 0.95rem;
  line-height: 1.6;
}

/* Rights Section */
.rights-section {
  margin-bottom: 5rem;
  text-align: center;
}

.rights-section h2 {
  margin-bottom: 0.5rem;
}

.rights-section .title-line {
  margin: 0 auto 3rem;
}

.rights-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 2rem;
}

.right-card {
  background: white;
  padding: 2.5rem 2rem;
  border-radius: 0;
  text-align: center;
  box-shadow: var(--shadow);
  transition: all 0.3s ease;
}

.right-card:hover {
  transform: translateY(-8px);
  box-shadow: var(--shadow);
}

.right-icon {
  font-size: 3rem;
  margin-bottom: 1.5rem;
}

.right-card h3 {
  font-size: 1.2rem;
  font-weight: 800;
  color: var(--green);
  margin-bottom: 1rem;
}

.right-card p {
  color: var(--muted);
  font-size: 0.95rem;
  line-height: 1.7;
}

/* Contact CTA */
.contact-cta {
  margin-bottom: 5rem;
}

.cta-card {
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  border-radius: 0;
  padding: 4rem 3rem;
  text-align: center;
}

.cta-icon {
  font-size: 4rem;
  margin-bottom: 1.5rem;
}

.cta-card h2 {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--green);
  margin-bottom: 1rem;
}

.cta-card p {
  font-size: 1.1rem;
  color: var(--muted);
  max-width: 600px;
  margin: 0 auto 2.5rem;
  line-height: 1.7;
}

.cta-actions {
  display: flex;
  gap: 1.5rem;
  justify-content: center;
  flex-wrap: wrap;
}

/* Download Section */
.download-section {
  margin-bottom: 4rem;
}

.download-card {
  background: white;
  border-radius: 0;
  overflow: hidden;
  display: grid;
  grid-template-columns: 1.5fr 1fr;
  box-shadow: var(--shadow);
}

.download-content {
  padding: 3rem;
}

.download-content h3 {
  font-size: 2rem;
  font-weight: 900;
  color: var(--green);
  margin-bottom: 1rem;
}

.download-content p {
  color: var(--muted);
  font-size: 1.05rem;
  line-height: 1.7;
  margin-bottom: 2rem;
}

.download-illustration {
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, rgba(1,132,4,0.05), rgba(251,146,60,0.05));
  padding: 2rem;
}

.download-illustration svg {
  width: 100%;
  max-width: 250px;
  height: auto;
}

/* Buttons */
.btn {
  padding: 1.25rem 2.5rem;
  border-radius: 0;
  font-size: 1.1rem;
  font-weight: 700;
  border: none;
  cursor: pointer;
  transition: all 0.3s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.75rem;
  font-family: var(--display);
  text-decoration: none;
}

.btn-primary {
  background: linear-gradient(135deg, var(--green), var(--green));
  color: white;
  box-shadow: var(--shadow);
}

.btn-primary:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow);
}

.btn-secondary {
  background: linear-gradient(135deg, var(--gold), var(--gold));
  color: white;
  box-shadow: var(--shadow);
}

.btn-secondary:hover {
  transform: translateY(-3px);
  box-shadow: var(--shadow);
}

.btn-outline {
  background: transparent;
  color: var(--green);
  border: 2px solid var(--green);
  padding: 0.875rem 2rem;
}

.btn-outline:hover {
  background: rgba(1,132,4,0.1);
}

/* Responsive */
@media (max-width: 1024px) {
  .download-card {
    grid-template-columns: 1fr;
  }
  
  .rights-grid {
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  }
}

@media (max-width: 768px) {
  .hero-content h1 {
    font-size: 2.5rem;
  }
  
  .policy-section {
    padding: 2rem 1.5rem;
  }
  
  .section-header {
    flex-direction: column;
    align-items: flex-start;
    gap: 1rem;
  }
  
  .nav-grid {
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  }
  
  .cta-actions {
    flex-direction: column;
  }
  
  .cta-actions .btn {
    width: 100%;
    justify-content: center;
  }
}

@media (max-width: 480px) {
  .hero-content h1 {
    font-size: 2rem;
  }
  
  .section-header h2 {
    font-size: 1.5rem;
  }
  
  .first-letter {
    font-size: 2rem;
  }
  
  .limitation-grid,
  .protection-grid,
  .force-majeure-grid {
    grid-template-columns: 1fr;
  }
}

@media (prefers-reduced-motion: reduce) {
  * {
    animation-duration: 0.01ms !important;
    transition-duration: 0.01ms !important;
  }
}
</style>