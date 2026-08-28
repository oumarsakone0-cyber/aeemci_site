<template>
  <div class="sv">
    <!-- ══ Connexion par matricule ═══════════════════════════════════ -->
    <div v-if="!moi" class="sv-porte">
      <div class="sv-porte-carte">
        <div class="sv-logo">
          <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.9">
            <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2" />
            <path d="M10 9.5l5 2.5-5 2.5z" fill="currentColor" stroke="none" />
          </svg>
        </div>
        <span class="sv-eyebrow">SENAFOI 2026 · KORHOGO</span>
        <h1>Le fil du séminaire</h1>
        <p>Réservé aux participants. Saisissez le matricule de votre badge.</p>

        <form @submit.prevent="connecter">
          <input
            v-model="matricule" class="sv-matricule" placeholder="Ex : SEM20261408"
            autocapitalize="characters" autocomplete="off" spellcheck="false"
          />
          <button class="sv-btn-plein" :disabled="connexion || !matricule.trim()">
            {{ connexion ? 'Vérification…' : 'Entrer' }}
          </button>
        </form>

        <p v-if="erreur" class="sv-erreur">{{ erreur }}</p>
        <small class="sv-note">
          Séminaristes, SR, CCR, formateurs, commissions, imarat, invités, aînés,
          compétiteurs… tout badge est accepté.
        </small>
      </div>
    </div>

    <!-- ══ Fil ═══════════════════════════════════════════════════════ -->
    <template v-else>
      <div v-show="vue === 'fil'" ref="fil" class="sv-fil" @scroll.passive="surDefilement">
        <section
          v-for="(v, i) in videos" :key="v.id"
          ref="ecrans" class="sv-ecran" :data-index="i"
        >
          <video
            :ref="(el) => (lecteurs[i] = el)"
            class="sv-video"
            :style="{ filter: FILTRES[v.filtre] || FILTRES.eclat }"
            :src="v.video_url"
            :poster="v.poster_url || undefined"
            :preload="i <= indexCourant + 2 && i >= indexCourant - 1 ? 'auto' : 'none'"
            :muted="!son"
            playsinline webkit-playsinline loop
            @click="basculerLecture(i)"
            @dblclick="aimerVite(v)"
          ></video>

          <!-- Le navigateur interdit de démarrer avec le son : on l'annonce -->
          <button v-if="!son && i === indexCourant" class="sv-son-invite" @click.stop="activerSon">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.1">
              <path d="M11 5L6 9H2v6h4l5 4z" /><path d="M22 9l-6 6M16 9l6 6" />
            </svg>
            Toucher pour le son
          </button>

          <!-- Voile pour détacher le texte de l'image -->
          <div class="sv-voile"></div>

          <!-- Cœur au double-tap -->
          <Transition name="sv-coeur">
            <div v-if="coeurSur === v.id" class="sv-coeur">
              <svg viewBox="0 0 24 24" width="120" height="120" fill="currentColor">
                <path d="M12 21s-8-5.2-8-11a4.6 4.6 0 018-3 4.6 4.6 0 018 3c0 5.8-8 11-8 11z" />
              </svg>
            </div>
          </Transition>

          <!-- Indicateur de pause -->
          <div v-if="enPause === i" class="sv-pause">
            <svg viewBox="0 0 24 24" width="60" height="60" fill="currentColor"><path d="M8 5l11 7-11 7z" /></svg>
          </div>

          <!-- Auteur et légende — l'auteur mène à son mur -->
          <div class="sv-bas">
            <button class="sv-auteur sv-auteur--clic" @click="ouvrirProfil(v.auteur.matricule)">
              <img v-if="v.auteur.photo" :src="v.auteur.photo" alt="" />
              <span v-else class="sv-init">{{ initiales(v.auteur.nom) }}</span>
              <div>
                <strong>{{ v.auteur.nom || 'Participant' }}</strong>
                <small>{{ v.auteur.categorie }} · {{ ilYA(v.created_at) }}</small>
              </div>
            </button>
            <p v-if="v.legende" class="sv-legende">{{ v.legende }}</p>
          </div>

          <!-- Actions -->
          <div class="sv-actions">
            <button class="sv-action" :class="{ aime: v.aime_par_moi }" @click="basculerAime(v)">
              <svg viewBox="0 0 24 24" width="30" height="30" :fill="v.aime_par_moi ? 'currentColor' : 'none'"
                   stroke="currentColor" stroke-width="1.9">
                <path d="M12 21s-8-5.2-8-11a4.6 4.6 0 018-3 4.6 4.6 0 018 3c0 5.8-8 11-8 11z" />
              </svg>
              <em>{{ v.aimes }}</em>
            </button>

            <button class="sv-action" @click="ouvrirAimes(v)">
              <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.9">
                <path d="M17 20v-2a4 4 0 00-4-4H6a4 4 0 00-4 4v2" /><circle cx="9.5" cy="7" r="4" />
                <path d="M22 20v-2a4 4 0 00-3-3.9" />
              </svg>
              <em>Qui ?</em>
            </button>

            <button class="sv-action" @click="ouvrirCommentaires(v)">
              <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="1.9">
                <path d="M21 11.5a8.4 8.4 0 01-9 8.4 9 9 0 01-3.9-.9L3 20.5l1.5-4.6A8.4 8.4 0 0112 3.1a8.4 8.4 0 019 8.4z" />
              </svg>
              <em>{{ v.commentaires }}</em>
            </button>

            <button v-if="v.a_moi" class="sv-action sv-action--danger" @click="supprimer(v)">
              <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.9">
                <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14" />
              </svg>
              <em>Retirer</em>
            </button>

            <button class="sv-action" @click="basculerSon">
              <svg v-if="son" viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.9">
                <path d="M11 5L6 9H2v6h4l5 4z" /><path d="M15.5 8.5a5 5 0 010 7M18.5 5.5a9 9 0 010 13" />
              </svg>
              <svg v-else viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.9">
                <path d="M11 5L6 9H2v6h4l5 4z" /><path d="M22 9l-6 6M16 9l6 6" />
              </svg>
              <em>{{ son ? 'Son' : 'Muet' }}</em>
            </button>

            <div class="sv-vues">
              <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7z" /><circle cx="12" cy="12" r="3" />
              </svg>
              {{ v.vues }}
            </div>
          </div>
        </section>

        <!-- Fin du fil -->
        <section v-if="!videos.length && !chargement" class="sv-ecran sv-ecran--vide">
          <p>Aucune vidéo pour le moment.</p>
          <small>Les publications du séminaire apparaîtront ici.</small>
        </section>
        <section v-else-if="fini && videos.length" class="sv-ecran sv-ecran--vide">
          <p>Vous avez tout vu.</p>
          <small>Remontez pour revoir le fil.</small>
        </section>
      </div>

      <!-- Barre du haut -->
      <header class="sv-tete">
        <div class="sv-bascule">
          <button :class="{ on: vue === 'fil' }" @click="allerAuFil">Fil</button>
          <button :class="{ on: vue === 'media' }" @click="allerAuxMedias">Média</button>
        </div>
        <div class="sv-moi">
          <button class="sv-publier" @click="ouvrirPublication">
            <svg viewBox="0 0 24 24" width="17" height="17" fill="none" stroke="currentColor" stroke-width="2.6">
              <path d="M12 5v14M5 12h14" />
            </svg>
            Publier
          </button>
          <!-- Sa propre vignette ouvre « mes publications » -->
          <button class="sv-avatar" title="Mes publications" @click="ouvrirProfil(moi.matricule)">
            <img v-if="moi.photo" :src="moi.photo" alt="" />
            <span v-else class="sv-init sv-init--petit">{{ initiales(moi.nom) }}</span>
          </button>
          <button class="sv-sortir" @click="deconnecter">Quitter</button>
        </div>
      </header>

      <!-- Bouton flottant : reste atteignable au pouce pendant le défilement -->
      <button class="sv-fab" aria-label="Publier une vidéo" @click="ouvrirPublication">
        <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.4">
          <rect x="2" y="6" width="13" height="12" rx="2.5" />
          <path d="M15 10.5l7-3.5v10l-7-3.5z" />
        </svg>
      </button>

      <!-- ══ Galerie média ═════════════════════════════════════════ -->
      <div v-show="vue === 'media'" ref="grille" class="sv-galerie" @scroll.passive="surDefilementGalerie">
        <div class="sv-galerie-tete">
          <h2>Cloud du SENAFOI</h2>
          <p>{{ totaux.image }} photo{{ totaux.image > 1 ? 's' : '' }} · {{ totaux.video }} vidéo{{ totaux.video > 1 ? 's' : '' }}</p>
          <div class="sv-onglets">
            <button :class="{ on: typeMedia === 'tout' }" @click="filtrerMedias('tout')">Tout</button>
            <button :class="{ on: typeMedia === 'image' }" @click="filtrerMedias('image')">Photos</button>
            <button :class="{ on: typeMedia === 'video' }" @click="filtrerMedias('video')">Vidéos</button>
          </div>
        </div>

        <div v-if="!medias.length && !chargementMedias" class="sv-galerie-vide">
          <p>Rien à afficher.</p>
          <small>Ajoutez la première photo ou vidéo du séminaire.</small>
        </div>

        <div class="sv-grille">
          <button
            v-for="(m, i) in medias" :key="m.id" class="sv-tuile"
            @click="ouvrirMedia(i)"
          >
            <img
              v-if="m.type === 'image'" :src="m.video_url" alt="" loading="lazy" decoding="async"
              :style="{ filter: FILTRES[m.filtre] || FILTRES.eclat }"
            />
            <img
              v-else-if="m.poster_url" :src="m.poster_url" alt="" loading="lazy" decoding="async"
              :style="{ filter: FILTRES[m.filtre] || FILTRES.eclat }"
            />
            <span v-else class="sv-tuile-vide">
              <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.9">
                <rect x="2" y="6" width="13" height="12" rx="2.5" /><path d="M15 10.5l7-3.5v10l-7-3.5z" />
              </svg>
            </span>

            <span v-if="m.type === 'video'" class="sv-tuile-type">
              <svg viewBox="0 0 24 24" width="13" height="13" fill="currentColor"><path d="M8 5l11 7-11 7z" /></svg>
              <em v-if="m.duree">{{ minutes(m.duree) }}</em>
            </span>

            <span class="sv-tuile-bas">
              <svg viewBox="0 0 24 24" width="12" height="12" :fill="m.aime_par_moi ? 'currentColor' : 'none'"
                   stroke="currentColor" stroke-width="2.2">
                <path d="M12 21s-8-5.2-8-11a4.6 4.6 0 018-3 4.6 4.6 0 018 3c0 5.8-8 11-8 11z" />
              </svg>{{ m.aimes }}
              <svg viewBox="0 0 24 24" width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2">
                <path d="M21 11.5a8.4 8.4 0 01-9 8.4 9 9 0 01-3.9-.9L3 20.5l1.5-4.6A8.4 8.4 0 0112 3.1a8.4 8.4 0 019 8.4z" />
              </svg>{{ m.commentaires }}
            </span>
          </button>
        </div>

        <p v-if="chargementMedias" class="sv-galerie-charge">Chargement…</p>
        <p v-else-if="finiMedias && medias.length" class="sv-galerie-charge">Fin de la galerie.</p>
      </div>

      <!-- ══ Mur d'un participant ═══════════════════════════════════
           Ouvert en touchant une photo de profil. Quand c'est le sien,
           chaque publication porte son bouton de retrait. -->
      <Transition name="sv-monte">
        <div v-if="profil" class="sv-profil">
          <header class="sv-profil-tete">
            <button class="sv-fermer sv-fermer--statique" aria-label="Fermer" @click="fermerProfil">
              <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4">
                <path d="M18 6L6 18M6 6l12 12" />
              </svg>
            </button>

            <div class="sv-profil-identite">
              <img v-if="profil.photo" :src="profil.photo" alt="" />
              <span v-else class="sv-init sv-init--grand">{{ initiales(profil.nom) }}</span>
              <div>
                <strong>{{ profilAMoi ? 'Mes publications' : (profil.nom || 'Participant') }}</strong>
                <small>{{ profil.categorie || '—' }} · {{ profil.matricule }}</small>
              </div>
            </div>

            <div class="sv-profil-chiffres">
              <div><b>{{ profilStats.total }}</b><span>publication{{ profilStats.total > 1 ? 's' : '' }}</span></div>
              <div><b>{{ profilStats.videos }}</b><span>vidéo{{ profilStats.videos > 1 ? 's' : '' }}</span></div>
              <div><b>{{ profilStats.photos }}</b><span>photo{{ profilStats.photos > 1 ? 's' : '' }}</span></div>
              <div><b>{{ profilStats.aimes_recus }}</b><span>j'aime</span></div>
              <div><b>{{ profilStats.vues }}</b><span>vues</span></div>
            </div>
          </header>

          <div v-if="chargementProfil" class="sv-galerie-charge">Chargement…</div>
          <div v-else-if="!profilMedias.length" class="sv-galerie-vide">
            <p>{{ profilAMoi ? "Vous n'avez rien publié." : 'Aucune publication.' }}</p>
            <small v-if="profilAMoi">Touchez « Publier » pour déposer votre première vidéo.</small>
          </div>

          <div class="sv-grille sv-grille--profil">
            <div v-for="m in profilMedias" :key="m.id" class="sv-tuile-profil">
              <button class="sv-tuile" @click="ouvrirDepuisProfil(m)">
                <img
                  v-if="m.type === 'image'" :src="m.video_url" alt="" loading="lazy" decoding="async"
                  :style="{ filter: FILTRES[m.filtre] || FILTRES.eclat }"
                />
                <video
                  v-else :src="m.video_url" :poster="m.poster_url || undefined"
                  :style="{ filter: FILTRES[m.filtre] || FILTRES.eclat }"
                  muted playsinline preload="metadata"
                ></video>
                <span class="sv-tuile-type">{{ m.type === 'image' ? 'Photo' : 'Vidéo' }}</span>
                <span class="sv-tuile-chiffres">♥ {{ m.aimes }} · {{ m.vues }} vues</span>
              </button>

              <button
                v-if="m.a_moi" class="sv-tuile-suppr" title="Retirer cette publication"
                :disabled="suppressionEnCours === m.id"
                @click.stop="supprimerDepuisProfil(m)"
              >
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.2">
                  <path d="M3 6h18M8 6V4h8v2M6 6l1 14h10l1-14" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </Transition>

      <!-- ══ Visionneuse d'un média de la galerie ═══════════════════ -->
      <Transition name="sv-monte">
        <div v-if="mediaOuvert" class="sv-visionneuse">
          <button class="sv-fermer" aria-label="Fermer" @click="fermerMedia">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4">
              <path d="M18 6L6 18M6 6l12 12" />
            </svg>
          </button>

          <button v-if="indexMedia > 0" class="sv-nav sv-nav--haut" aria-label="Précédent" @click="naviguerMedia(-1)">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M18 15l-6-6-6 6" /></svg>
          </button>
          <button v-if="indexMedia < medias.length - 1" class="sv-nav sv-nav--bas" aria-label="Suivant" @click="naviguerMedia(1)">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M6 9l6 6 6-6" /></svg>
          </button>

          <div class="sv-visionneuse-media" @dblclick="aimerVite(mediaOuvert)">
            <img
              v-if="mediaOuvert.type === 'image'" :src="mediaOuvert.video_url" alt=""
              :style="{ filter: FILTRES[mediaOuvert.filtre] || FILTRES.eclat }"
            />
            <video
              v-else ref="lecteurVisionneuse" :src="mediaOuvert.video_url"
              :poster="mediaOuvert.poster_url || undefined"
              :style="{ filter: FILTRES[mediaOuvert.filtre] || FILTRES.eclat }"
              :muted="!son"
              playsinline autoplay loop controls
            ></video>
          </div>

          <Transition name="sv-coeur">
            <div v-if="coeurSur === mediaOuvert.id" class="sv-coeur">
              <svg viewBox="0 0 24 24" width="110" height="110" fill="currentColor">
                <path d="M12 21s-8-5.2-8-11a4.6 4.6 0 018-3 4.6 4.6 0 018 3c0 5.8-8 11-8 11z" />
              </svg>
            </div>
          </Transition>

          <div class="sv-visionneuse-pied">
            <button class="sv-auteur sv-auteur--clic" @click="ouvrirProfil(mediaOuvert.auteur.matricule)">
              <img v-if="mediaOuvert.auteur.photo" :src="mediaOuvert.auteur.photo" alt="" />
              <span v-else class="sv-init">{{ initiales(mediaOuvert.auteur.nom) }}</span>
              <div>
                <strong>{{ mediaOuvert.auteur.nom || 'Participant' }}</strong>
                <small>{{ mediaOuvert.auteur.categorie }} · {{ ilYA(mediaOuvert.created_at) }}</small>
              </div>
            </button>
            <p v-if="mediaOuvert.legende" class="sv-legende">{{ mediaOuvert.legende }}</p>

            <div class="sv-visionneuse-actions">
              <button :class="{ aime: mediaOuvert.aime_par_moi }" @click="basculerAime(mediaOuvert)">
                <svg viewBox="0 0 24 24" width="21" height="21" :fill="mediaOuvert.aime_par_moi ? 'currentColor' : 'none'"
                     stroke="currentColor" stroke-width="2"><path d="M12 21s-8-5.2-8-11a4.6 4.6 0 018-3 4.6 4.6 0 018 3c0 5.8-8 11-8 11z" /></svg>
                {{ mediaOuvert.aimes }}
              </button>
              <button @click="ouvrirAimes(mediaOuvert)">Qui a aimé</button>
              <button @click="ouvrirCommentaires(mediaOuvert)">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 11.5a8.4 8.4 0 01-9 8.4 9 9 0 01-3.9-.9L3 20.5l1.5-4.6A8.4 8.4 0 0112 3.1a8.4 8.4 0 019 8.4z" />
                </svg>
                {{ mediaOuvert.commentaires }}
              </button>
              <button v-if="mediaOuvert.a_moi" class="danger" @click="supprimer(mediaOuvert)">Retirer</button>
            </div>
          </div>
        </div>
      </Transition>

      <!-- Choix du filtre pour la vidéo affichée -->
      <div v-if="vue === 'fil' && videos.length" class="sv-filtres">
        <button
          v-for="(css, cle) in FILTRES" :key="cle"
          :class="{ on: (videos[indexCourant]?.filtre || 'eclat') === cle }"
          @click="changerFiltre(cle)"
        >{{ NOMS_FILTRES[cle] }}</button>
      </div>

      <!-- ══ Panneau : commentaires ═════════════════════════════════ -->
      <Transition name="sv-monte">
        <div v-if="panneau === 'commentaires'" class="sv-panneau" @click.self="fermerPanneau">
          <div class="sv-feuille">
            <header>
              <strong>{{ commentaires.length }} commentaire{{ commentaires.length > 1 ? 's' : '' }}</strong>
              <button class="sv-x" @click="fermerPanneau">×</button>
            </header>

            <div class="sv-liste">
              <div v-if="!commentaires.length" class="sv-liste-vide">
                Personne n'a encore réagi. Lancez-vous.
              </div>
              <article v-for="c in commentaires" :key="c.id">
                <img v-if="c.photo" :src="c.photo" alt="" />
                <span v-else class="sv-init sv-init--petit">{{ initiales(c.nom) }}</span>
                <div>
                  <strong>{{ c.nom }}<small>{{ c.categorie }}</small></strong>
                  <p>{{ c.texte }}</p>
                  <time>{{ ilYA(c.created_at) }}</time>
                </div>
              </article>
            </div>

            <form class="sv-saisie" @submit.prevent="envoyerCommentaire">
              <img v-if="moi.photo" :src="moi.photo" alt="" />
              <span v-else class="sv-init sv-init--petit">{{ initiales(moi.nom) }}</span>
              <input v-model="nouveauCommentaire" placeholder="Votre commentaire…" maxlength="600" />
              <button :disabled="envoiCommentaire || !nouveauCommentaire.trim()">Envoyer</button>
            </form>
          </div>
        </div>
      </Transition>

      <!-- ══ Panneau : qui a aimé ═══════════════════════════════════ -->
      <Transition name="sv-monte">
        <div v-if="panneau === 'aimes'" class="sv-panneau" @click.self="fermerPanneau">
          <div class="sv-feuille">
            <header>
              <strong>{{ aimes.length }} j'aime</strong>
              <button class="sv-x" @click="fermerPanneau">×</button>
            </header>
            <div class="sv-liste">
              <div v-if="!aimes.length" class="sv-liste-vide">Aucun j'aime pour l'instant.</div>
              <article v-for="p in aimes" :key="p.matricule">
                <img v-if="p.photo" :src="p.photo" alt="" />
                <span v-else class="sv-init sv-init--petit">{{ initiales(p.nom) }}</span>
                <div>
                  <strong>{{ p.nom }}<small>{{ p.categorie }}</small></strong>
                  <time>{{ ilYA(p.created_at) }}</time>
                </div>
              </article>
            </div>
          </div>
        </div>
      </Transition>

      <!-- ══ Panneau : publier ══════════════════════════════════════ -->
      <Transition name="sv-monte">
        <div v-if="panneau === 'publier'" class="sv-panneau" @click.self="fermerPublication">
          <div class="sv-feuille sv-feuille--haute">
            <header>
              <strong>Publier une vidéo</strong>
              <button class="sv-x" @click="fermerPublication">×</button>
            </header>

            <div class="sv-pub">
              <!-- Prise de vue directe -->
              <template v-if="!apercu && camera">
                <div class="sv-camera">
                  <video ref="camVideo" class="sv-cam-video" playsinline muted autoplay></video>
                  <div v-if="enregistre" class="sv-rec">
                    <span class="sv-rec-point"></span>{{ chrono }}
                    <i>/ {{ MAX_SECONDES }} s</i>
                  </div>
                  <!-- Jauge du temps restant, sous le chronomètre -->
                  <div v-if="enregistre" class="sv-rec-jauge">
                    <div :style="{ width: (secondes / MAX_SECONDES * 100) + '%' }"></div>
                  </div>
                  <button class="sv-cam-basculer" :disabled="enregistre" @click="changerCamera">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2">
                      <path d="M3 12a9 9 0 0114-7.3M21 12a9 9 0 01-14 7.3" />
                      <path d="M17 4.7V9h-4M7 19.3V15h4" />
                    </svg>
                  </button>
                </div>

                <div class="sv-cam-barre">
                  <button class="sv-cam-annuler" @click="arreterCamera">Annuler</button>
                  <button
                    class="sv-declencheur" :class="{ rec: enregistre }"
                    :aria-label="enregistre ? 'Arrêter' : 'Enregistrer'"
                    @click="basculerEnregistrement"
                  ><span></span></button>
                  <span class="sv-cam-aide">{{ enregistre ? 'Appuyez pour arrêter' : `${MAX_SECONDES} s max` }}</span>
                </div>
              </template>

              <!-- Choix du mode -->
              <template v-else-if="!apercu">
                <button class="sv-depot sv-depot--action" @click="demarrerCamera">
                  <span class="sv-depot-icone">
                    <svg viewBox="0 0 24 24" width="30" height="30" fill="none" stroke="currentColor" stroke-width="2">
                      <rect x="2" y="6" width="13" height="12" rx="2.5" />
                      <path d="M15 10.5l7-3.5v10l-7-3.5z" />
                    </svg>
                  </span>
                  <strong>Filmer maintenant</strong>
                  <small>La caméra s'ouvre dans la page · {{ MAX_SECONDES }} s maximum</small>
                </button>

                <!-- Sans « capture », le téléphone propose au choix la
                     galerie ou l'appareil photo. -->
                <label class="sv-depot sv-depot--secondaire">
                  <input type="file" accept="image/*" @change="choisirImage" />
                  <span class="sv-depot-icone sv-depot-icone--photo">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M3 8.5A2.5 2.5 0 015.5 6h1.8l1.2-2h6l1.2 2h1.8A2.5 2.5 0 0121 8.5v9A2.5 2.5 0 0118.5 20h-13A2.5 2.5 0 013 17.5z" />
                      <circle cx="12" cy="12.5" r="3.4" />
                    </svg>
                  </span>
                  <strong>Ajouter une photo</strong>
                  <small>Depuis votre galerie ou l'appareil photo · 10 Mo maximum</small>
                </label>

                <label class="sv-depot sv-depot--secondaire">
                  <input type="file" accept="video/*" @change="choisirVideo" />
                  <span class="sv-depot-icone sv-depot-icone--gris">
                    <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2">
                      <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M12 3v13M7 8l5-5 5 5" />
                    </svg>
                  </span>
                  <strong>Choisir une vidéo existante</strong>
                  <small>Format vertical conseillé · 100 Mo maximum</small>
                </label>
              </template>

              <!-- Aperçu, retouche et légende -->
              <template v-else>
                <div class="sv-apercu">
                  <img
                    v-if="typePublication === 'image'" :src="apercu" class="sv-apercu-video" alt=""
                    :style="{ filter: FILTRES[filtreChoisi] }"
                  />
                  <video
                    v-else
                    ref="apercuVideo" :src="apercu" class="sv-apercu-video"
                    :style="{ filter: FILTRES[filtreChoisi] }"
                    playsinline muted loop autoplay
                    @loadedmetadata="surMetadonnees"
                  ></video>
                  <button class="sv-changer" @click="reinitialiserPublication">Changer</button>
                </div>

                <div class="sv-pub-filtres">
                  <button
                    v-for="(css, cle) in FILTRES" :key="cle"
                    :class="{ on: filtreChoisi === cle }"
                    @click="filtreChoisi = cle"
                  >{{ NOMS_FILTRES[cle] }}</button>
                </div>

                <input v-model="legende" class="sv-legende-saisie" maxlength="500"
                       placeholder="Une légende ? (facultatif)" />

                <div v-if="envoiVideo" class="sv-progression">
                  <div class="sv-progression-fill" :style="{ width: progression + '%' }"></div>
                  <span>{{ etapeEnvoi }} {{ progression }}%</span>
                </div>

                <button class="sv-btn-plein" :disabled="envoiVideo" @click="publier">
                  {{ envoiVideo ? 'Envoi…' : 'Publier dans le fil' }}
                </button>
                <small class="sv-note">
                  Le filtre choisi est appliqué à l'affichage. Votre vidéo d'origine
                  n'est pas modifiée.
                </small>
              </template>
            </div>
          </div>
        </div>
      </Transition>

      <div v-if="erreur" class="sv-toast">{{ erreur }}</div>
    </template>
  </div>
</template>

<script setup>
/**
 * Fil vidéo du SENAFOI 2026.
 *
 * Défilement vertical plein écran, une vidéo par écran, dans les deux sens.
 * L'accès passe par le matricule du badge. Pas de partage : aucune adresse
 * n'est exposée hors du fil.
 */
import { ref, reactive, computed, onMounted, onBeforeUnmount, nextTick } from 'vue'

const API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_videos_api.php'
// Le service de fichiers commun n'accepte que des images : les vidéos
// passent par un point d'entrée dédié, les vignettes restent sur le cloud.
const UPLOAD_VIDEO_URL = 'https://api.aeemci-ce.ci/senafoi/senafoi26_video_upload.php'
const UPLOAD_URL = 'https://api.aeemci-ce.ci/cloud/upload.php'
const CLOUD_API_KEY = 'SAKONE25_04_2026_medias'
const CLE_JETON = 'senafoi_video_jeton'

/** Retouches appliquées à la vidéo. « Éclat » est le rendu par défaut. */
const FILTRES = {
  eclat: 'saturate(1.22) contrast(1.08) brightness(1.04)',
  chaud: 'saturate(1.3) sepia(.18) contrast(1.06) brightness(1.05)',
  froid: 'saturate(1.1) hue-rotate(-10deg) contrast(1.1) brightness(1.02)',
  argent: 'grayscale(1) contrast(1.15) brightness(1.06)',
  vif: 'saturate(1.6) contrast(1.18)',
  brut: 'none',
}
const NOMS_FILTRES = {
  eclat: 'Éclat', chaud: 'Chaud', froid: 'Froid',
  argent: 'Argent', vif: 'Vif', brut: 'Brut',
}

const moi = ref(null)
const matricule = ref('')
const connexion = ref(false)
const erreur = ref('')

const vue = ref('fil')

const videos = ref([])
const chargement = ref(false)
const fini = ref(false)

// Galerie
const grille = ref(null)
const medias = ref([])
const totaux = ref({ image: 0, video: 0 })
const typeMedia = ref('tout')
const chargementMedias = ref(false)
const finiMedias = ref(false)
const indexMedia = ref(-1)
const mediaOuvert = ref(null)

// Mur d'un participant : « mes publications » ou celui de quelqu'un d'autre
const profil = ref(null)
const profilAMoi = ref(false)
const profilMedias = ref([])
const profilStats = ref({ total: 0, videos: 0, photos: 0, vues: 0, aimes_recus: 0 })
const chargementProfil = ref(false)
const suppressionEnCours = ref(null)

const lecteurVisionneuse = ref(null)
const indexCourant = ref(0)
const enPause = ref(null)
const coeurSur = ref(null)
// Les navigateurs refusent de lancer une vidéo sonore sans geste de
// l'utilisateur : on démarre en sourdine, le premier appui rétablit le son.
const son = ref(false)

const fil = ref(null)
const ecrans = ref([])
const lecteurs = reactive({})

const panneau = ref('')
const videoActive = ref(null)
const commentaires = ref([])
const aimes = ref([])
const nouveauCommentaire = ref('')
const envoiCommentaire = ref(false)

// Publication et prise de vue
/** Durée maximale d'un enregistrement fait dans la page. */
const MAX_SECONDES = 60
const camVideo = ref(null)
const camera = ref(false)
const enregistre = ref(false)
const secondes = ref(0)
const faceCamera = ref('environment')
let fluxCamera = null
let enregistreur = null
let morceaux = []
let minuteurChrono = null

const apercuVideo = ref(null)
const apercu = ref('')
const typePublication = ref('video')
const fichierVideo = ref(null)
const legende = ref('')
const filtreChoisi = ref('eclat')
const duree = ref(null)
const envoiVideo = ref(false)
const progression = ref(0)
const etapeEnvoi = ref('')

let observateur = null
let jeton = ''
let minuteurErreur = null
const vuesComptees = new Set()

/* ─── Utilitaires ─────────────────────────────────────────────────────── */

function initiales(nom) {
  const parts = String(nom || '').trim().split(/\s+/)
  return ((parts[0]?.[0] || '') + (parts[1]?.[0] || '')).toUpperCase() || '?'
}

function ilYA(valeur) {
  if (!valeur) return ''
  const d = new Date(String(valeur).replace(' ', 'T'))
  if (Number.isNaN(d.getTime())) return ''
  const s = Math.max(0, Math.round((Date.now() - d.getTime()) / 1000))
  if (s < 60) return "à l'instant"
  if (s < 3600) return `il y a ${Math.floor(s / 60)} min`
  if (s < 86400) return `il y a ${Math.floor(s / 3600)} h`
  return `il y a ${Math.floor(s / 86400)} j`
}

function signaler(message) {
  erreur.value = message
  clearTimeout(minuteurErreur)
  minuteurErreur = setTimeout(() => { erreur.value = '' }, 4000)
}

async function appeler(action, options = {}) {
  const res = await fetch(`${API}?action=${action}${options.query || ''}`, {
    method: options.corps ? 'POST' : 'GET',
    headers: {
      Authorization: `Bearer ${jeton}`,
      ...(options.corps ? { 'Content-Type': 'application/json' } : {}),
    },
    body: options.corps ? JSON.stringify(options.corps) : undefined,
  })
  const data = await res.json().catch(() => ({}))
  if (!data.success) {
    if (data.reconnexion) deconnecter()
    throw new Error(data.message || `HTTP ${res.status}`)
  }
  return data
}

/* ─── Connexion ───────────────────────────────────────────────────────── */

async function connecter() {
  connexion.value = true
  erreur.value = ''
  try {
    const res = await fetch(`${API}?action=connexion`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ matricule: matricule.value.trim().toUpperCase() }),
    })
    const data = await res.json().catch(() => ({}))
    if (!data.success) throw new Error(data.message || 'Connexion impossible')

    jeton = data.jeton
    moi.value = data.moi
    try { localStorage.setItem(CLE_JETON, jeton) } catch { /* navigation privée */ }
    await chargerFeed(true)
  } catch (e) {
    erreur.value = e.message
  } finally {
    connexion.value = false
  }
}

function deconnecter() {
  jeton = ''
  moi.value = null
  videos.value = []
  try { localStorage.removeItem(CLE_JETON) } catch { /* ignore */ }
}

/* ─── Fil ─────────────────────────────────────────────────────────────── */

async function chargerFeed(remiseAZero = false) {
  if (chargement.value) return
  chargement.value = true
  try {
    const dernier = remiseAZero ? 0 : (videos.value[videos.value.length - 1]?.id || 0)
    const data = await appeler('feed', { query: `&limite=12${dernier ? `&avant=${dernier}` : ''}` })
    moi.value = data.moi || moi.value
    const lot = data.videos || []
    videos.value = remiseAZero ? lot : [...videos.value, ...lot]
    if (lot.length < 12) fini.value = true
    await nextTick()
    observer()
    if (remiseAZero) jouer(0)
  } catch (e) {
    signaler(e.message)
  } finally {
    chargement.value = false
  }
}

/** Charge la suite avant d'arriver au bout, pour que le fil reste continu. */
function surDefilement() {
  if (fini.value || chargement.value) return
  const el = fil.value
  if (!el) return
  if (el.scrollTop + el.clientHeight * 2.5 >= el.scrollHeight) chargerFeed(false)
}

function observer() {
  observateur?.disconnect()
  observateur = new IntersectionObserver((entrees) => {
    for (const e of entrees) {
      if (e.intersectionRatio < 0.6) continue
      const i = Number(e.target.dataset.index)
      if (Number.isNaN(i)) continue
      indexCourant.value = i
      jouer(i)
    }
  }, { root: fil.value, threshold: [0.6] })

  for (const el of ecrans.value || []) {
    if (el?.dataset?.index !== undefined) observateur.observe(el)
  }
}

function jouer(i) {
  enPause.value = null
  for (const [cle, el] of Object.entries(lecteurs)) {
    if (!el) continue
    if (Number(cle) === i) {
      el.muted = !son.value
      el.play?.().catch(() => {
        // Refusée avec le son : on retombe en sourdine pour ne pas
        // laisser une image figée.
        el.muted = true
        el.play?.().catch(() => { /* l'écran n'a pas encore été touché */ })
      })
    } else {
      el.muted = true
      el.pause?.()
      // On ne rembobine que les vidéos éloignées : revenir en arrière
      // d'un cran doit reprendre là où on en était.
      if (Math.abs(Number(cle) - i) > 1) el.currentTime = 0
    }
  }
  compterVue(i)
}

/** Le premier appui sur une vidéo muette rétablit le son plutôt que de mettre en pause. */
function basculerLecture(i) {
  const el = lecteurs[i]
  if (!el) return
  if (!son.value) { activerSon(); return }
  if (el.paused) { el.play?.(); enPause.value = null }
  else { el.pause(); enPause.value = i }
}

function appliquerSon() {
  for (const [cle, el] of Object.entries(lecteurs)) {
    if (!el) continue
    // Seule la vidéo à l'écran porte le son : les voisines restent muettes
    // même si elles ont été préchargées.
    el.muted = !son.value || Number(cle) !== indexCourant.value
  }
  if (lecteurVisionneuse.value) lecteurVisionneuse.value.muted = !son.value
}

function activerSon() {
  son.value = true
  appliquerSon()
  // Le geste de l'utilisateur autorise la lecture sonore : on relance.
  lecteurs[indexCourant.value]?.play?.().catch(() => { /* refusé, on reste muet */ })
  try { localStorage.setItem('senafoi_video_son', '1') } catch { /* ignore */ }
}

function basculerSon() {
  son.value = !son.value
  appliquerSon()
  if (son.value) lecteurs[indexCourant.value]?.play?.().catch(() => {})
  try { localStorage.setItem('senafoi_video_son', son.value ? '1' : '0') } catch { /* ignore */ }
}

function compterVue(i) {
  const v = videos.value[i]
  if (!v || vuesComptees.has(v.id)) return
  vuesComptees.add(v.id)
  v.vues += 1
  appeler('vue', { corps: { video_id: v.id } }).catch(() => { /* sans conséquence */ })
}

/* ─── Galerie ─────────────────────────────────────────────────────────── */

function minutes(secondesTotal) {
  const s = Number(secondesTotal) || 0
  return `${Math.floor(s / 60)}:${String(s % 60).padStart(2, '0')}`
}

function allerAuFil() {
  vue.value = 'fil'
  fermerMedia()
  nextTick(() => jouer(indexCourant.value))
}

function allerAuxMedias() {
  vue.value = 'media'
  // Le fil se tait pendant qu'on parcourt la galerie
  for (const el of Object.values(lecteurs)) el?.pause?.()
  if (!medias.value.length) chargerMedias(true)
}

async function chargerMedias(remiseAZero = false) {
  if (chargementMedias.value) return
  chargementMedias.value = true
  try {
    const dernier = remiseAZero ? 0 : (medias.value[medias.value.length - 1]?.id || 0)
    const data = await appeler('galerie', {
      query: `&type=${typeMedia.value}&limite=30${dernier ? `&avant=${dernier}` : ''}`,
    })
    const lot = data.medias || []
    medias.value = remiseAZero ? lot : [...medias.value, ...lot]
    totaux.value = data.totaux || totaux.value
    if (lot.length < 30) finiMedias.value = true
  } catch (e) {
    signaler(e.message)
  } finally {
    chargementMedias.value = false
  }
}

function surDefilementGalerie() {
  if (finiMedias.value || chargementMedias.value) return
  const el = grille.value
  if (!el) return
  if (el.scrollTop + el.clientHeight * 1.8 >= el.scrollHeight) chargerMedias(false)
}

function filtrerMedias(type) {
  if (typeMedia.value === type) return
  typeMedia.value = type
  finiMedias.value = false
  medias.value = []
  chargerMedias(true)
  grille.value?.scrollTo({ top: 0 })
}

function ouvrirMedia(i) {
  indexMedia.value = i
  mediaOuvert.value = medias.value[i] || null
  compterVueMedia(mediaOuvert.value)
}

function fermerMedia() {
  mediaOuvert.value = null
  indexMedia.value = -1
}

function naviguerMedia(pas) {
  const i = indexMedia.value + pas
  if (i < 0 || i >= medias.value.length) return
  ouvrirMedia(i)
  // On charge la suite avant d'atteindre le dernier élément
  if (i >= medias.value.length - 3) chargerMedias(false)
}

function compterVueMedia(m) {
  if (!m || vuesComptees.has(m.id)) return
  vuesComptees.add(m.id)
  m.vues += 1
  appeler('vue', { corps: { video_id: m.id } }).catch(() => { /* sans conséquence */ })
}

/* ─── J'aime ──────────────────────────────────────────────────────────── */

/**
 * Le fil et la galerie détiennent deux objets distincts pour un même média :
 * on répercute le compteur sur les deux, sinon l'un afficherait une valeur
 * périmée au retour.
 */
function synchroniser(id, champs) {
  for (const liste of [videos.value, medias.value]) {
    const cible = liste.find((x) => x.id === id)
    if (cible) Object.assign(cible, champs)
  }
  if (mediaOuvert.value?.id === id) Object.assign(mediaOuvert.value, champs)
}

async function basculerAime(v) {
  // Réponse immédiate à l'écran, correction ensuite si le serveur refuse.
  const avant = { aime_par_moi: v.aime_par_moi, aimes: v.aimes }
  synchroniser(v.id, {
    aime_par_moi: !avant.aime_par_moi,
    aimes: avant.aimes + (avant.aime_par_moi ? -1 : 1),
  })
  try {
    const data = await appeler('aimer', { corps: { video_id: v.id } })
    synchroniser(v.id, { aime_par_moi: data.aime, aimes: data.aimes })
  } catch (e) {
    synchroniser(v.id, avant)
    signaler(e.message)
  }
}

function aimerVite(v) {
  if (!v.aime_par_moi) basculerAime(v)
  coeurSur.value = v.id
  setTimeout(() => { if (coeurSur.value === v.id) coeurSur.value = null }, 750)
}

/* ─── Panneaux ────────────────────────────────────────────────────────── */

async function ouvrirCommentaires(v) {
  videoActive.value = v
  commentaires.value = []
  panneau.value = 'commentaires'
  try {
    const data = await appeler('commentaires', { query: `&video_id=${v.id}` })
    commentaires.value = data.commentaires || []
  } catch (e) { signaler(e.message) }
}

async function ouvrirAimes(v) {
  videoActive.value = v
  aimes.value = []
  panneau.value = 'aimes'
  try {
    const data = await appeler('aimes', { query: `&video_id=${v.id}` })
    aimes.value = data.personnes || []
  } catch (e) { signaler(e.message) }
}

function fermerPanneau() {
  panneau.value = ''
  videoActive.value = null
}

async function envoyerCommentaire() {
  const v = videoActive.value
  if (!v || !nouveauCommentaire.value.trim()) return
  envoiCommentaire.value = true
  try {
    const data = await appeler('commenter', {
      corps: { video_id: v.id, texte: nouveauCommentaire.value.trim() },
    })
    commentaires.value.push(data.commentaire)
    synchroniser(v.id, { commentaires: data.commentaires })
    nouveauCommentaire.value = ''
  } catch (e) {
    signaler(e.message)
  } finally {
    envoiCommentaire.value = false
  }
}

/* ─── Publication ─────────────────────────────────────────────────────── */

function ouvrirPublication() {
  panneau.value = 'publier'
  // On met le fil en sourdine pendant la préparation
  for (const el of Object.values(lecteurs)) el?.pause?.()
}

function fermerPublication() {
  panneau.value = ''
  arreterCamera()
  reinitialiserPublication()
  jouer(indexCourant.value)
}

function reinitialiserPublication() {
  if (apercu.value) URL.revokeObjectURL(apercu.value)
  apercu.value = ''
  typePublication.value = 'video'
  fichierVideo.value = null
  legende.value = ''
  duree.value = null
  progression.value = 0
}

/* ─── Prise de vue dans la page ───────────────────────────────────────── */

const chrono = computed(() => {
  const m = Math.floor(secondes.value / 60)
  const s = secondes.value % 60
  return `${m}:${String(s).padStart(2, '0')}`
})

/** Premier format d'enregistrement accepté par le navigateur. */
function formatEnregistrement() {
  const candidats = [
    'video/mp4;codecs=h264,aac',
    'video/mp4',
    'video/webm;codecs=vp9,opus',
    'video/webm;codecs=vp8,opus',
    'video/webm',
  ]
  for (const type of candidats) {
    if (window.MediaRecorder?.isTypeSupported?.(type)) return type
  }
  return ''
}

async function demarrerCamera() {
  if (!navigator.mediaDevices?.getUserMedia || !window.MediaRecorder) {
    signaler("Ce navigateur ne sait pas filmer dans la page. Utilisez « Choisir une vidéo existante ».")
    return
  }
  try {
    fluxCamera = await navigator.mediaDevices.getUserMedia({
      video: { facingMode: faceCamera.value, width: { ideal: 1080 }, height: { ideal: 1920 } },
      audio: true,
    })
    camera.value = true
    await nextTick()
    if (camVideo.value) {
      camVideo.value.srcObject = fluxCamera
      camVideo.value.play?.().catch(() => { /* sans conséquence */ })
    }
  } catch (e) {
    signaler(
      e.name === 'NotAllowedError'
        ? "Accès à la caméra refusé. Autorisez-le dans les réglages du navigateur."
        : `Caméra indisponible : ${e.message}`
    )
  }
}

function arreterFlux() {
  fluxCamera?.getTracks?.().forEach((t) => t.stop())
  fluxCamera = null
  if (camVideo.value) camVideo.value.srcObject = null
}

function arreterCamera() {
  if (enregistre.value) arreterEnregistrement()
  arreterFlux()
  camera.value = false
  secondes.value = 0
}

async function changerCamera() {
  faceCamera.value = faceCamera.value === 'environment' ? 'user' : 'environment'
  arreterFlux()
  await demarrerCamera()
}

function basculerEnregistrement() {
  if (enregistre.value) arreterEnregistrement()
  else demarrerEnregistrement()
}

function demarrerEnregistrement() {
  if (!fluxCamera) return
  const type = formatEnregistrement()
  try {
    enregistreur = new MediaRecorder(fluxCamera, type ? { mimeType: type } : undefined)
  } catch {
    signaler("Ce navigateur refuse l'enregistrement vidéo.")
    return
  }

  morceaux = []
  enregistreur.ondataavailable = (e) => { if (e.data?.size) morceaux.push(e.data) }
  enregistreur.onstop = () => {
    clearInterval(minuteurChrono)
    enregistre.value = false
    if (!morceaux.length) { signaler('Aucune image enregistrée.'); return }

    const mime = enregistreur.mimeType || type || 'video/webm'
    const extension = mime.includes('mp4') ? 'mp4' : 'webm'
    const blob = new Blob(morceaux, { type: mime })
    const fichier = new File([blob], `senafoi_${Date.now()}.${extension}`, { type: mime })

    arreterFlux()
    camera.value = false

    reinitialiserPublication()
    fichierVideo.value = fichier
    apercu.value = URL.createObjectURL(fichier)
    duree.value = secondes.value
    secondes.value = 0
  }

  // Des tranches d'une seconde : si l'onglet est interrompu, ce qui est
  // déjà filmé reste récupérable.
  enregistreur.start(1000)
  enregistre.value = true
  secondes.value = 0
  minuteurChrono = setInterval(() => {
    secondes.value += 1
    if (secondes.value >= MAX_SECONDES) arreterEnregistrement()
  }, 1000)
}

function arreterEnregistrement() {
  clearInterval(minuteurChrono)
  try { enregistreur?.state !== 'inactive' && enregistreur?.stop() } catch { /* déjà arrêté */ }
}

function choisirVideo(evenement) {
  const fichier = evenement.target.files?.[0]
  evenement.target.value = ''
  if (!fichier) return
  if (!fichier.type.startsWith('video/')) { signaler('Choisissez une vidéo.'); return }
  if (fichier.size > 100 * 1024 * 1024) { signaler('Vidéo trop lourde (100 Mo maximum).'); return }

  reinitialiserPublication()
  typePublication.value = 'video'
  fichierVideo.value = fichier
  apercu.value = URL.createObjectURL(fichier)
}

function choisirImage(evenement) {
  const fichier = evenement.target.files?.[0]
  evenement.target.value = ''
  if (!fichier) return
  if (!fichier.type.startsWith('image/')) { signaler('Choisissez une image.'); return }
  if (fichier.size > 10 * 1024 * 1024) { signaler('Photo trop lourde (10 Mo maximum).'); return }

  reinitialiserPublication()
  typePublication.value = 'image'
  fichierVideo.value = fichier
  apercu.value = URL.createObjectURL(fichier)
}

function surMetadonnees() {
  const el = apercuVideo.value
  if (el && Number.isFinite(el.duration)) duree.value = Math.round(el.duration)
}

/**
 * Envoi d'un fichier, avec la progression.
 * @param {string} sousDossier  'videos' passe par le point d'entrée vidéo,
 *                              tout le reste va sur le cloud des images.
 */
function televerser(fichier, sousDossier) {
  return new Promise((resolve, reject) => {
    const estVideo = sousDossier === 'videos'
    const corps = new FormData()
    corps.append('file', fichier)
    if (!estVideo) {
      corps.append('folder', 'senafoi')
      corps.append('subfolder', sousDossier)
    }

    const xhr = new XMLHttpRequest()
    xhr.upload.onprogress = (e) => {
      if (e.lengthComputable) progression.value = Math.round((e.loaded * 100) / e.total)
    }
    xhr.onload = () => {
      const poids = (fichier.size / 1024 / 1024).toFixed(1)
      // 413 : le serveur refuse la taille avant même de lire le fichier.
      if (xhr.status === 413) {
        reject(new Error(
          `Le serveur a refusé les ${poids} Mo de ce fichier. `
          + 'La limite de téléversement doit être relevée côté hébergement.'
        ))
        return
      }
      try {
        const json = JSON.parse(xhr.responseText || '{}')
        if (xhr.status >= 200 && xhr.status < 300 && json.success !== false) {
          // Le serveur de fichiers ne nomme pas toujours l'adresse de la
          // même façon : on accepte les formes courantes plutôt que de
          // repartir les mains vides avec un « succès » inexploitable.
          const url = json.url || json.secure_url || json.file_url || json.link
            || json.data?.url || json.data?.secure_url || json.path || ''
          if (url) {
            resolve({ ...json, url, public_id: json.public_id || json.filename || json.data?.public_id || '' })
            return
          }
          reject(new Error(
            "Le fichier est parti mais le serveur n'a renvoyé aucune adresse. "
            + `Réponse reçue : ${xhr.responseText.slice(0, 160)}`
          ))
          return
        }
        const message = json.error || json.message || `Envoi impossible (HTTP ${xhr.status})`
        // Un refus lié au poids mérite d'être nommé : sinon on cherche
        // longtemps du côté du réseau ou du format.
        reject(new Error(
          /taille|size|large|limit|volumineu/i.test(message)
            ? `${message} — fichier de ${poids} Mo.`
            : message
        ))
      } catch {
        reject(new Error(
          xhr.status === 0
            ? 'Envoi interrompu. Vérifiez votre connexion.'
            : `Réponse invalide du serveur de fichiers (HTTP ${xhr.status}, fichier de ${poids} Mo).`
        ))
      }
    }
    xhr.onerror = () => reject(new Error('Connexion interrompue pendant l’envoi'))
    xhr.open('POST', estVideo ? UPLOAD_VIDEO_URL : UPLOAD_URL)
    if (estVideo) xhr.setRequestHeader('Authorization', `Bearer ${jeton}`)
    else xhr.setRequestHeader('X-API-Key', CLOUD_API_KEY)
    xhr.send(corps)
  })
}

/**
 * Extrait la première image nette de la vidéo : elle sert de vignette
 * pendant le chargement, le fil ne reste jamais noir.
 */
function capturerVignette() {
  return new Promise((resolve) => {
    const el = apercuVideo.value
    if (!el || !el.videoWidth) { resolve(null); return }
    try {
      const cv = document.createElement('canvas')
      const largeur = 720
      cv.width = largeur
      cv.height = Math.round((el.videoHeight / el.videoWidth) * largeur)
      cv.getContext('2d').drawImage(el, 0, 0, cv.width, cv.height)
      cv.toBlob((blob) => {
        resolve(blob ? new File([blob], 'vignette.jpg', { type: 'image/jpeg' }) : null)
      }, 'image/jpeg', 0.82)
    } catch { resolve(null) }
  })
}

async function publier() {
  if (!fichierVideo.value) return
  const estImage = typePublication.value === 'image'
  envoiVideo.value = true
  erreur.value = ''
  try {
    etapeEnvoi.value = estImage ? 'Envoi de la photo' : 'Envoi de la vidéo'
    progression.value = 0
    // Les images passent par le cloud commun, les vidéos par leur point dédié.
    const televerse = await televerser(fichierVideo.value, estImage ? 'photos' : 'videos')

    // La vignette est un confort : son échec ne doit pas bloquer la publication.
    let poster = ''
    if (!estImage) {
      try {
        etapeEnvoi.value = 'Vignette'
        const vignette = await capturerVignette()
        if (vignette) poster = (await televerser(vignette, 'photos')).url || ''
      } catch { poster = '' }
    }

    etapeEnvoi.value = 'Publication'
    await appeler('publier', {
      corps: {
        type: estImage ? 'image' : 'video',
        video_url: televerse.url,
        video_id: televerse.public_id || televerse.filename || '',
        poster_url: poster,
        legende: legende.value.trim(),
        filtre: filtreChoisi.value,
        duree: estImage ? null : duree.value,
      },
    })

    fermerPublication()

    // Une photo n'apparaît pas dans le fil vertical : on emmène l'auteur
    // là où sa publication se trouve.
    if (estImage) {
      finiMedias.value = false
      typeMedia.value = 'tout'
      await chargerMedias(true)
      vue.value = 'media'
      grille.value?.scrollTo({ top: 0, behavior: 'smooth' })
    } else {
      vue.value = 'fil'
      await chargerFeed(true)
      medias.value = []
      finiMedias.value = false
      fil.value?.scrollTo({ top: 0, behavior: 'smooth' })
    }
  } catch (e) {
    signaler(e.message)
  } finally {
    envoiVideo.value = false
    etapeEnvoi.value = ''
  }
}

/* ─── Filtre et suppression ───────────────────────────────────────────── */

/** Le filtre reste local : il change le rendu à l'écran, pas le fichier. */
function changerFiltre(cle) {
  const v = videos.value[indexCourant.value]
  if (v) v.filtre = cle
}

async function supprimer(v) {
  if (!confirm('Retirer ce média définitivement ?')) return
  try {
    await appeler('supprimer', { corps: { video_id: v.id } })
    retirerPartout(v)
    await nextTick()
    observer()
  } catch (e) { signaler(e.message) }
}

/** Un média retiré doit disparaître des trois listes qui le détiennent. */
function retirerPartout(v) {
  videos.value = videos.value.filter((x) => x.id !== v.id)
  medias.value = medias.value.filter((x) => x.id !== v.id)
  profilMedias.value = profilMedias.value.filter((x) => x.id !== v.id)
  if (mediaOuvert.value?.id === v.id) fermerMedia()
  totaux.value = {
    ...totaux.value,
    [v.type || 'video']: Math.max(0, (totaux.value[v.type || 'video'] || 0) - 1),
  }
}

/* ─── Mur d'un participant ────────────────────────────────────────────── */

async function ouvrirProfil(matricule) {
  if (!matricule) return
  fermerMedia()
  chargementProfil.value = true
  profil.value = { matricule, nom: '', photo: null, categorie: '' }
  try {
    const data = await appeler('profil', { query: `&matricule=${encodeURIComponent(matricule)}` })
    profil.value = data.profil
    profilAMoi.value = Boolean(data.a_moi)
    profilMedias.value = data.medias || []
    profilStats.value = data.stats || profilStats.value
  } catch (e) {
    signaler(e.message)
    profil.value = null
  } finally {
    chargementProfil.value = false
  }
}

function fermerProfil() {
  profil.value = null
  profilMedias.value = []
}

/**
 * Ouvre un média du mur dans la visionneuse existante.
 * La liste de navigation devient celle du mur : on reste chez la personne.
 */
function ouvrirDepuisProfil(m) {
  medias.value = [...profilMedias.value]
  indexMedia.value = profilMedias.value.findIndex((x) => x.id === m.id)
  mediaOuvert.value = medias.value[indexMedia.value] || null
}

async function supprimerDepuisProfil(m) {
  if (!confirm('Retirer cette publication définitivement ?')) return
  suppressionEnCours.value = m.id
  try {
    await appeler('supprimer', { corps: { video_id: m.id } })
    retirerPartout(m)
    profilStats.value = {
      ...profilStats.value,
      total: Math.max(0, profilStats.value.total - 1),
      videos: Math.max(0, profilStats.value.videos - (m.type === 'image' ? 0 : 1)),
      photos: Math.max(0, profilStats.value.photos - (m.type === 'image' ? 1 : 0)),
    }
  } catch (e) {
    signaler(e.message)
  } finally {
    suppressionEnCours.value = null
  }
}

/* ─── Cycle de vie ────────────────────────────────────────────────────── */

onMounted(async () => {
  try {
    jeton = localStorage.getItem(CLE_JETON) || ''
    // Le son reste coupé au premier chargement : un geste sera demandé.
    // On ne restitue le choix que s'il a déjà été fait une fois.
    son.value = localStorage.getItem('senafoi_video_son') === '1'
  } catch { jeton = '' }
  if (!jeton) return
  try {
    await chargerFeed(true)
    if (!moi.value) deconnecter()
  } catch { deconnecter() }
})

onBeforeUnmount(() => {
  observateur?.disconnect()
  clearTimeout(minuteurErreur)
  clearInterval(minuteurChrono)
  arreterFlux()
  for (const el of Object.values(lecteurs)) el?.pause?.()
})
</script>

<style scoped>
/* Plein écran, fond noir : la page occupe tout, sans cadre du site. */
.sv { position: fixed; inset: 0; background: #000; color: #fff; overflow: hidden;
      font-family: inherit; -webkit-tap-highlight-color: transparent; }

/* ── Porte d'entrée ───────────────────────────────────────────────── */
.sv-porte { position: absolute; inset: 0; display: grid; place-items: center; padding: 22px;
            background: radial-gradient(120% 90% at 50% 0%, var(--green) 0%, #05140c 55%, #000 100%); }
.sv-porte-carte { width: 100%; max-width: 380px; text-align: center; }
.sv-logo { width: 62px; height: 62px; margin: 0 auto 16px; border-radius: 0;
           background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.18);
           display: grid; place-items: center; color: var(--green-2); }
.sv-eyebrow { font-size: 10.5px; font-weight: 800; letter-spacing: .12em; color: var(--green-mist); }
.sv-porte h1 { margin: 8px 0 6px; font-size: 30px; font-weight: 800; letter-spacing: -.02em; }
.sv-porte p { margin: 0 0 20px; color: rgba(255,255,255,.65); font-size: 14px; line-height: 1.5; }
.sv-matricule { width: 100%; height: 54px; border-radius: 0; border: 1px solid rgba(255,255,255,.2);
                background: rgba(255,255,255,.07); color: #fff; text-align: center;
                font-size: 17px; font-weight: 700; letter-spacing: .06em; font-family: inherit;
                text-transform: uppercase; }
.sv-matricule::placeholder { color: rgba(255,255,255,.32); letter-spacing: normal;
                             font-weight: 500; text-transform: none; }
.sv-matricule:focus { outline: none; border-color: var(--green-2); background: rgba(255,255,255,.11); }
.sv-btn-plein { width: 100%; height: 54px; margin-top: 11px; border: 0; border-radius: 0;
                background: var(--green); color: #fff; font-size: 16px; font-weight: 800;
                cursor: pointer; font-family: inherit; }
.sv-btn-plein:disabled { opacity: .45; }
.sv-erreur { margin: 14px 0 0; color: #fca5a5; font-size: 13.5px; line-height: 1.5; }
.sv-note { display: block; margin-top: 22px; color: rgba(255,255,255,.4);
           font-size: 11.5px; line-height: 1.6; }

/* ── Défilement vertical ──────────────────────────────────────────── */
.sv-fil { height: 100%; overflow-y: auto; scroll-snap-type: y mandatory;
          -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.sv-fil::-webkit-scrollbar { display: none; }
.sv-ecran { position: relative; height: 100dvh; scroll-snap-align: start;
            scroll-snap-stop: always; overflow: hidden; background: #000; }
.sv-ecran--vide { display: grid; place-content: center; text-align: center; gap: 6px; }
.sv-ecran--vide p { margin: 0; font-size: 17px; font-weight: 700; }
.sv-ecran--vide small { color: rgba(255,255,255,.5); font-size: 13px; }

.sv-video { width: 100%; height: 100%; object-fit: cover; display: block; }
.sv-voile { position: absolute; inset: 0; pointer-events: none;
            background: linear-gradient(to bottom, rgba(0,0,0,.45) 0%, transparent 22%,
                        transparent 52%, rgba(0,0,0,.75) 100%); }

/* ── Effets ───────────────────────────────────────────────────────── */
.sv-coeur { position: absolute; inset: 0; display: grid; place-items: center;
            color: #fff; pointer-events: none; filter: drop-shadow(0 8px 30px rgba(0,0,0,.5)); }
.sv-coeur-enter-active { animation: coeur .75s ease-out; }
.sv-coeur-leave-active { transition: opacity .2s; }
.sv-coeur-leave-to { opacity: 0; }
@keyframes coeur {
  0%   { transform: scale(.3); opacity: 0; }
  35%  { transform: scale(1.15); opacity: 1; }
  60%  { transform: scale(.95); }
  100% { transform: scale(1.05); opacity: .92; }
}
.sv-pause { position: absolute; inset: 0; display: grid; place-items: center;
            color: rgba(255,255,255,.85); pointer-events: none;
            filter: drop-shadow(0 4px 18px rgba(0,0,0,.6)); animation: apparait .18s ease-out; }
@keyframes apparait { from { transform: scale(.7); opacity: 0; } to { transform: scale(1); opacity: 1; } }

/* ── Barre du haut ────────────────────────────────────────────────── */
.sv-tete { position: absolute; top: 0; left: 0; right: 0; z-index: 5;
           display: flex; align-items: center; justify-content: space-between;
           padding: 14px 16px; padding-top: calc(14px + env(safe-area-inset-top, 0px));
           background: linear-gradient(to bottom, rgba(0,0,0,.5), transparent); }
.sv-titre { font-size: 15px; font-weight: 900; letter-spacing: .12em; }
.sv-moi { display: flex; align-items: center; gap: 9px; }
.sv-moi img { width: 32px; height: 32px; border-radius: 50%; object-fit: cover;
              border: 1.5px solid rgba(255,255,255,.7); }
.sv-sortir { border: 1px solid rgba(255,255,255,.3); background: rgba(0,0,0,.3); color: #fff;
             border-radius: 999px; padding: 6px 13px; font-size: 11.5px; font-weight: 700;
             cursor: pointer; font-family: inherit; }

.sv-init { width: 32px; height: 32px; border-radius: 50%; background: var(--green); color: #fff;
           display: grid; place-items: center; font-size: 12px; font-weight: 800; flex-shrink: 0; }
.sv-init--petit { width: 34px; height: 34px; font-size: 12.5px; }

/* ── Bas de l'écran ───────────────────────────────────────────────── */
.sv-bas { position: absolute; left: 0; right: 76px; bottom: 0; z-index: 4;
          padding: 16px; padding-bottom: calc(84px + env(safe-area-inset-bottom, 0px));
          display: grid; gap: 9px; }
.sv-auteur { display: flex; align-items: center; gap: 10px; }
.sv-auteur img { width: 42px; height: 42px; border-radius: 50%; object-fit: cover;
                 border: 2px solid rgba(255,255,255,.85); }
.sv-auteur .sv-init { width: 42px; height: 42px; font-size: 15px; }
.sv-auteur strong { display: block; font-size: 15px; }
.sv-auteur small { color: rgba(255,255,255,.7); font-size: 11.5px; }
.sv-legende { margin: 0; font-size: 14px; line-height: 1.5;
              text-shadow: 0 1px 10px rgba(0,0,0,.6); }

/* ── Colonne d'actions ────────────────────────────────────────────── */
.sv-actions { position: absolute; right: 10px; z-index: 4;
              bottom: calc(84px + env(safe-area-inset-bottom, 0px));
              display: grid; gap: 18px; justify-items: center; }
.sv-action { border: 0; background: none; color: #fff; display: grid; justify-items: center;
             gap: 4px; cursor: pointer; font-family: inherit; padding: 0;
             filter: drop-shadow(0 2px 8px rgba(0,0,0,.55)); }
.sv-action em { font-style: normal; font-size: 11.5px; font-weight: 700; }
.sv-action.aime { color: #f43f5e; animation: pulse .3s ease-out; }
.sv-action--danger { color: #fca5a5; }
@keyframes pulse { 0% { transform: scale(1); } 45% { transform: scale(1.28); } 100% { transform: scale(1); } }
.sv-vues { display: flex; align-items: center; gap: 5px; color: rgba(255,255,255,.75);
           font-size: 11.5px; font-weight: 700; }

/* Invitation à rétablir le son, tant qu'aucun geste n'a été fait */
.sv-son-invite { position: absolute; left: 50%; transform: translateX(-50%); z-index: 4;
                 top: calc(74px + env(safe-area-inset-top, 0px));
                 display: inline-flex; align-items: center; gap: 7px;
                 border: 1px solid rgba(255,255,255,.22); background: rgba(0,0,0,.5);
                 backdrop-filter: blur(8px); color: #fff; border-radius: 999px;
                 padding: 9px 16px; font-size: 12.5px; font-weight: 700;
                 cursor: pointer; font-family: inherit; white-space: nowrap;
                 animation: respire 2.4s ease-in-out infinite; }
@keyframes respire { 0%, 100% { opacity: .92; } 50% { opacity: .55; } }

/* ── Filtres ──────────────────────────────────────────────────────── */
.sv-filtres { position: absolute; left: 0; right: 0; z-index: 5;
              bottom: calc(16px + env(safe-area-inset-bottom, 0px));
              display: flex; gap: 7px; overflow-x: auto; padding: 0 14px;
              scrollbar-width: none; }
.sv-filtres::-webkit-scrollbar { display: none; }
.sv-filtres button { flex-shrink: 0; border: 1px solid rgba(255,255,255,.24);
                     background: rgba(0,0,0,.4); backdrop-filter: blur(6px); color: #fff;
                     border-radius: 999px; padding: 8px 15px; font-size: 12px; font-weight: 700;
                     cursor: pointer; font-family: inherit; }
.sv-filtres button.on { background: #fff; color: #000; border-color: #fff; }

/* ── Panneaux du bas ──────────────────────────────────────────────── */
.sv-panneau { position: absolute; inset: 0; z-index: 10; background: rgba(0,0,0,.55);
              display: flex; align-items: flex-end; }
.sv-feuille { width: 100%; max-height: 76dvh; background: #14161a;
              border-radius: 0; display: flex; flex-direction: column;
              padding-bottom: env(safe-area-inset-bottom, 0px); }
.sv-feuille > header { display: flex; align-items: center; justify-content: space-between;
                       padding: 15px 18px; border-bottom: 1px solid rgba(255,255,255,.08); }
.sv-feuille > header strong { font-size: 14.5px; }
.sv-x { border: 0; background: none; color: rgba(255,255,255,.6); font-size: 26px;
        line-height: 1; cursor: pointer; }

.sv-liste { flex: 1; overflow-y: auto; padding: 14px 18px; display: grid; gap: 15px;
            align-content: start; }
.sv-liste-vide { padding: 34px 10px; text-align: center; color: rgba(255,255,255,.45);
                 font-size: 13.5px; }
.sv-liste article { display: flex; gap: 11px; align-items: flex-start; }
.sv-liste img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.sv-liste strong { display: block; font-size: 13px; }
.sv-liste strong small { margin-left: 7px; color: rgba(255,255,255,.45);
                         font-size: 10.5px; font-weight: 600; }
.sv-liste p { margin: 3px 0 0; font-size: 14px; line-height: 1.5;
              color: rgba(255,255,255,.9); overflow-wrap: anywhere; }
.sv-liste time { display: block; margin-top: 3px; color: rgba(255,255,255,.35); font-size: 11px; }

.sv-saisie { display: flex; align-items: center; gap: 9px; padding: 12px 16px;
             border-top: 1px solid rgba(255,255,255,.08); }
.sv-saisie img { width: 34px; height: 34px; border-radius: 50%; object-fit: cover; flex-shrink: 0; }
.sv-saisie input { flex: 1; min-width: 0; height: 44px; border-radius: 0;
                   border: 1px solid rgba(255,255,255,.14); background: rgba(255,255,255,.07);
                   color: #fff; padding: 0 16px; font-size: 16px; font-family: inherit; }
.sv-saisie input:focus { outline: none; border-color: var(--green-2); }
.sv-saisie button { flex-shrink: 0; border: 0; background: none; color: var(--green-2);
                    font-size: 13.5px; font-weight: 800; cursor: pointer; font-family: inherit;
                    padding: 8px 4px; }
.sv-saisie button:disabled { color: rgba(255,255,255,.28); }

/* ── Bascule Fil / Média ──────────────────────────────────────────── */
.sv-bascule { display: flex; gap: 3px; background: rgba(0,0,0,.4); backdrop-filter: blur(8px);
              border: 1px solid rgba(255,255,255,.14); border-radius: 999px; padding: 3px; }
.sv-bascule button { border: 0; background: none; color: rgba(255,255,255,.65);
                     border-radius: 999px; padding: 7px 17px; font-size: 12.5px;
                     font-weight: 800; cursor: pointer; font-family: inherit; }
.sv-bascule button.on { background: #fff; color: #000; }

/* ── Galerie ──────────────────────────────────────────────────────── */
.sv-galerie { position: absolute; inset: 0; overflow-y: auto; background: #0a0c0e;
              padding-top: calc(66px + env(safe-area-inset-top, 0px));
              padding-bottom: calc(96px + env(safe-area-inset-bottom, 0px));
              -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.sv-galerie::-webkit-scrollbar { display: none; }
.sv-galerie-tete { padding: 8px 14px 14px; }
.sv-galerie-tete h2 { margin: 0; font-size: 21px; font-weight: 800; letter-spacing: -.02em; }
.sv-galerie-tete p { margin: 3px 0 12px; color: rgba(255,255,255,.5); font-size: 12.5px; }
.sv-onglets { display: flex; gap: 7px; }
.sv-onglets button { border: 1px solid rgba(255,255,255,.16); background: rgba(255,255,255,.05);
                     color: rgba(255,255,255,.75); border-radius: 999px; padding: 8px 16px;
                     font-size: 12.5px; font-weight: 700; cursor: pointer; font-family: inherit; }
.sv-onglets button.on { background: var(--green); border-color: var(--green); color: #fff; }

.sv-grille { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2px; padding: 0 2px; }
.sv-tuile { position: relative; aspect-ratio: 9/13; border: 0; padding: 0; background: #16181c;
            overflow: hidden; cursor: pointer; }
.sv-tuile img { width: 100%; height: 100%; object-fit: cover; display: block; }
.sv-tuile-vide { position: absolute; inset: 0; display: grid; place-items: center;
                 color: rgba(255,255,255,.25); }
.sv-tuile-type { position: absolute; top: 6px; right: 6px; display: flex; align-items: center;
                 gap: 4px; background: rgba(0,0,0,.55); border-radius: 999px; padding: 3px 8px;
                 color: #fff; font-size: 10px; font-weight: 800; }
.sv-tuile-type em { font-style: normal; font-variant-numeric: tabular-nums; }
.sv-tuile-bas { position: absolute; left: 0; right: 0; bottom: 0; display: flex; align-items: center;
                gap: 4px; padding: 14px 7px 6px; color: #fff; font-size: 10.5px; font-weight: 800;
                background: linear-gradient(to top, rgba(0,0,0,.75), transparent); }
.sv-tuile-bas svg { margin-left: 3px; }

.sv-galerie-vide { padding: 60px 20px; text-align: center; }
.sv-galerie-vide p { margin: 0; font-size: 16px; font-weight: 700; }
.sv-galerie-vide small { color: rgba(255,255,255,.45); font-size: 13px; }
.sv-galerie-charge { padding: 20px; text-align: center; color: rgba(255,255,255,.4); font-size: 12.5px; }

/* ── Mur d'un participant ─────────────────────────────────────────── */
.sv-profil { position: absolute; inset: 0; z-index: 16; overflow-y: auto;
             background: #0a0c0e; -ms-overflow-style: none; scrollbar-width: none; }
.sv-profil::-webkit-scrollbar { display: none; }

.sv-profil-tete { position: relative; padding: calc(14px + env(safe-area-inset-top, 0px)) 16px 16px;
                  background: linear-gradient(160deg, var(--green) 0%, #0a0c0e 100%); }
.sv-fermer--statique { position: absolute; }
.sv-profil-identite { display: flex; align-items: center; gap: 13px; padding-right: 46px; }
.sv-profil-identite img { width: 66px; height: 66px; border-radius: 50%; object-fit: cover;
                          border: 2px solid rgba(255,255,255,.35); }
.sv-init--grand { width: 66px; height: 66px; font-size: 23px; }
.sv-profil-identite strong { display: block; font-size: 19px; font-weight: 800; letter-spacing: -.01em; }
.sv-profil-identite small { color: rgba(255,255,255,.6); font-size: 12px; }

.sv-profil-chiffres { display: flex; gap: 6px; margin-top: 16px; }
.sv-profil-chiffres div { flex: 1; text-align: center; padding: 9px 4px; border-radius: 0;
                          background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1); }
.sv-profil-chiffres b { display: block; font-size: 16px; font-weight: 900;
                        font-variant-numeric: tabular-nums; }
.sv-profil-chiffres span { font-size: 9.5px; color: rgba(255,255,255,.55); font-weight: 700; }

.sv-grille--profil { padding-top: 2px; }
.sv-tuile-profil { position: relative; }
.sv-tuile-profil .sv-tuile { width: 100%; }
.sv-tuile-chiffres { position: absolute; left: 0; right: 0; bottom: 0; padding: 16px 7px 6px;
                     color: #fff; font-size: 10px; font-weight: 800; text-align: left;
                     background: linear-gradient(to top, rgba(0,0,0,.8), transparent); }
.sv-tuile-suppr { position: absolute; left: 6px; top: 6px; z-index: 2;
                  width: 30px; height: 30px; border-radius: 0; border: 0; cursor: pointer;
                  background: rgba(220,38,38,.92); color: #fff;
                  display: grid; place-items: center; }
.sv-tuile-suppr:disabled { opacity: .5; cursor: default; }

/* Une photo de profil mène au mur de son auteur */
.sv-auteur--clic { border: 0; background: none; padding: 0; color: inherit;
                   font-family: inherit; text-align: left; cursor: pointer; }
.sv-auteur--clic:active { opacity: .7; }
.sv-avatar { border: 0; background: none; padding: 0; cursor: pointer; line-height: 0; }

/* ── Visionneuse ──────────────────────────────────────────────────── */
.sv-visionneuse { position: absolute; inset: 0; z-index: 15; background: #000;
                  display: flex; flex-direction: column; }
.sv-visionneuse-media { flex: 1; min-height: 0; display: grid; place-items: center;
                        overflow: hidden; }
.sv-visionneuse-media img, .sv-visionneuse-media video { max-width: 100%; max-height: 100%;
                                                         object-fit: contain; display: block; }
.sv-fermer { position: absolute; z-index: 3; right: 12px;
             top: calc(12px + env(safe-area-inset-top, 0px));
             width: 38px; height: 38px; border-radius: 50%; border: 0;
             background: rgba(0,0,0,.5); backdrop-filter: blur(6px); color: #fff;
             display: grid; place-items: center; cursor: pointer; }
.sv-nav { position: absolute; z-index: 3; left: 50%; transform: translateX(-50%);
          width: 40px; height: 40px; border-radius: 50%; border: 0;
          background: rgba(0,0,0,.45); backdrop-filter: blur(6px); color: #fff;
          display: grid; place-items: center; cursor: pointer; }
.sv-nav--haut { top: calc(14px + env(safe-area-inset-top, 0px)); }
.sv-nav--bas { bottom: 186px; }

.sv-visionneuse-pied { flex-shrink: 0; padding: 14px 16px;
                       padding-bottom: calc(16px + env(safe-area-inset-bottom, 0px));
                       background: #0a0c0e; border-top: 1px solid rgba(255,255,255,.07);
                       display: grid; gap: 10px; }
.sv-visionneuse-actions { display: flex; gap: 8px; flex-wrap: wrap; }
.sv-visionneuse-actions button { display: inline-flex; align-items: center; gap: 6px;
                                 border: 1px solid rgba(255,255,255,.15);
                                 background: rgba(255,255,255,.06); color: #fff;
                                 border-radius: 999px; padding: 9px 15px; font-size: 12.5px;
                                 font-weight: 700; cursor: pointer; font-family: inherit; }
.sv-visionneuse-actions button.aime { color: #f43f5e; border-color: rgba(244,63,94,.4);
                                      background: rgba(244,63,94,.12); }
.sv-visionneuse-actions button.danger { color: #fca5a5; }

/* ── Publication ──────────────────────────────────────────────────── */
.sv-publier { display: inline-flex; align-items: center; gap: 5px; border: 0;
              background: var(--green); color: #fff; border-radius: 999px; padding: 8px 14px;
              font-size: 12.5px; font-weight: 800; cursor: pointer; font-family: inherit; }
.sv-fab { position: absolute; right: 14px; z-index: 6;
          bottom: calc(72px + env(safe-area-inset-bottom, 0px));
          width: 54px; height: 54px; border-radius: 50%; border: 0;
          background: var(--green); color: #fff; display: grid; place-items: center;
          cursor: pointer; box-shadow: var(--shadow); }

.sv-feuille--haute { max-height: 88dvh; }
.sv-pub { flex: 1; overflow-y: auto; padding: 16px 18px 20px; display: grid; gap: 13px;
          align-content: start; }

.sv-depot { display: grid; justify-items: center; gap: 7px; padding: 34px 18px;
            border: 1.5px dashed rgba(255,255,255,.22); border-radius: 0;
            background: rgba(255,255,255,.04); cursor: pointer; text-align: center; }
.sv-depot input { display: none; }
.sv-depot-icone { width: 62px; height: 62px; border-radius: 0; background: rgba(22,163,74,.18);
                  color: var(--green-2); display: grid; place-items: center; }
.sv-depot strong { font-size: 15px; }
.sv-depot small { color: rgba(255,255,255,.45); font-size: 11.5px; }

.sv-depot--action { border-style: solid; border-color: rgba(22,163,74,.45);
                    background: rgba(22,163,74,.1); color: #fff; font-family: inherit;
                    cursor: pointer; }
.sv-depot--secondaire { padding: 20px 18px; }
.sv-depot-icone--gris { background: rgba(255,255,255,.08); color: rgba(255,255,255,.7);
                        width: 52px; height: 52px; border-radius: 0; }
.sv-depot-icone--photo { background: rgba(56,189,248,.16); color: #7dd3fc;
                         width: 52px; height: 52px; border-radius: 0; }

/* ── Caméra ───────────────────────────────────────────────────────── */
.sv-camera { position: relative; border-radius: 0; overflow: hidden; background: #000;
             aspect-ratio: 9/16; max-height: 52dvh; margin: 0 auto; }
.sv-cam-video { width: 100%; height: 100%; object-fit: cover; display: block; }
.sv-rec { position: absolute; top: 12px; left: 12px; display: flex; align-items: center; gap: 7px;
          background: rgba(0,0,0,.55); backdrop-filter: blur(6px); border-radius: 999px;
          padding: 6px 13px; font-size: 12.5px; font-weight: 800;
          font-variant-numeric: tabular-nums; }
.sv-rec i { font-style: normal; font-weight: 600; color: rgba(255,255,255,.55); font-size: 11px; }
.sv-rec-point { width: 9px; height: 9px; border-radius: 50%; background: #ef4444;
                animation: clignote 1s infinite; }
.sv-rec-jauge { position: absolute; left: 12px; right: 12px; top: 46px; height: 3px;
                border-radius: 0; background: rgba(255,255,255,.22); overflow: hidden; }
.sv-rec-jauge div { height: 100%; background: #ef4444; transition: width 1s linear; }
@keyframes clignote { 0%, 100% { opacity: 1; } 50% { opacity: .25; } }
.sv-cam-basculer { position: absolute; top: 12px; right: 12px; width: 38px; height: 38px;
                   border-radius: 50%; border: 1px solid rgba(255,255,255,.28);
                   background: rgba(0,0,0,.45); backdrop-filter: blur(6px); color: #fff;
                   display: grid; place-items: center; cursor: pointer; }
.sv-cam-basculer:disabled { opacity: .35; }

.sv-cam-barre { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 10px; }
.sv-cam-annuler { justify-self: start; border: 0; background: none; color: rgba(255,255,255,.65);
                  font-size: 13.5px; font-weight: 700; cursor: pointer; font-family: inherit; }
.sv-cam-aide { justify-self: end; color: rgba(255,255,255,.5); font-size: 11.5px; text-align: right; }
.sv-declencheur { width: 68px; height: 68px; border-radius: 50%; border: 4px solid #fff;
                  background: none; display: grid; place-items: center; cursor: pointer; padding: 0; }
.sv-declencheur span { width: 52px; height: 52px; border-radius: 50%; background: #ef4444;
                       transition: border-radius .2s, width .2s, height .2s; }
.sv-declencheur.rec span { width: 26px; height: 26px; border-radius: 0; }

.sv-apercu { position: relative; border-radius: 0; overflow: hidden; background: #000; }
.sv-apercu-video { width: 100%; max-height: 46dvh; object-fit: contain; display: block; }
.sv-changer { position: absolute; top: 10px; right: 10px; border: 1px solid rgba(255,255,255,.3);
              background: rgba(0,0,0,.55); backdrop-filter: blur(6px); color: #fff;
              border-radius: 999px; padding: 7px 14px; font-size: 12px; font-weight: 700;
              cursor: pointer; font-family: inherit; }

.sv-pub-filtres { display: flex; gap: 7px; overflow-x: auto; scrollbar-width: none; }
.sv-pub-filtres::-webkit-scrollbar { display: none; }
.sv-pub-filtres button { flex-shrink: 0; border: 1px solid rgba(255,255,255,.2);
                         background: rgba(255,255,255,.06); color: #fff; border-radius: 999px;
                         padding: 9px 15px; font-size: 12.5px; font-weight: 700;
                         cursor: pointer; font-family: inherit; }
.sv-pub-filtres button.on { background: #fff; color: #000; border-color: #fff; }

.sv-legende-saisie { width: 100%; height: 48px; border-radius: 0;
                     border: 1px solid rgba(255,255,255,.16); background: rgba(255,255,255,.06);
                     color: #fff; padding: 0 15px; font-size: 16px; font-family: inherit; }
.sv-legende-saisie:focus { outline: none; border-color: var(--green-2); }

.sv-progression { position: relative; height: 34px; border-radius: 0; overflow: hidden;
                  background: rgba(255,255,255,.09); display: grid; place-items: center; }
.sv-progression-fill { position: absolute; left: 0; top: 0; bottom: 0; background: var(--green);
                       transition: width .2s ease; }
.sv-progression span { position: relative; font-size: 12px; font-weight: 800; }

.sv-monte-enter-active, .sv-monte-leave-active { transition: opacity .22s; }
.sv-monte-enter-active .sv-feuille, .sv-monte-leave-active .sv-feuille { transition: transform .26s ease; }
.sv-monte-enter-from, .sv-monte-leave-to { opacity: 0; }
.sv-monte-enter-from .sv-feuille, .sv-monte-leave-to .sv-feuille { transform: translateY(100%); }

.sv-toast { position: absolute; left: 50%; transform: translateX(-50%); z-index: 20;
            bottom: calc(100px + env(safe-area-inset-bottom, 0px));
            background: rgba(220,38,38,.95); color: #fff; border-radius: 0;
            padding: 11px 16px; font-size: 13px; font-weight: 600; max-width: 88%;
            text-align: center; }

/* ── Écran large : on borne la largeur, façon téléphone ───────────── */
@media (min-width: 720px) {
  .sv-ecran { max-width: 480px; margin: 0 auto; }
  .sv-tete, .sv-filtres { max-width: 480px; left: 50%; transform: translateX(-50%); }
  .sv-feuille { max-width: 480px; margin: 0 auto; border-radius: 0; }
}
</style>
