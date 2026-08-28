/**
 * Générateur d'affiches personnalisées SENAFOI 2026.
 *
 * L'affiche n'est plus dessinée de zéro : on part des deux gabarits fournis
 * par la commission Communication et on n'ajoute que les informations de la
 * personne.
 *
 *   • gabarit_seminariste.jpg — bandeau vert simple
 *     Utilisé pour séminaristes, SR, CCR, formateurs, imams, stands, Imarat.
 *   • gabarit_co.jpg — porte la mention « MEMBRE DU » au-dessus du bandeau
 *     Réservé aux membres de commission, dont le bandeau lit
 *     « MEMBRE DU · COMITÉ D'ORGANISATION ».
 *
 * Ce qui est ajouté par-dessus :
 *   1. la photo, recadrée dans le cadre à bordure dégradée orange → or ;
 *   2. la catégorie, centrée dans le bandeau vert ;
 *   3. le nom et le matricule, sous le cadre photo ;
 *   4. une accroche courte, sous la pastille « Pour l'aventure » ;
 *   5. le « e » de « Prête » pour les sœurs.
 *
 * Toutes les coordonnées sont relevées sur les gabarits d'origine, en
 * 1800 × 1800. Ne pas les modifier sans re-mesurer les images.
 */

import gabaritSeminariste from '../../assets/affiches/gabarit_seminariste.jpg'
import gabaritCo from '../../assets/affiches/gabarit_co.jpg'

// ── Géométrie relevée sur les gabarits (1800 × 1800) ───────────────────────
const T = 1800

const ZONES = {
  // Cadre photo. Le gabarit n'a pas de bord bas : la photo d'origine se perd
  // dans le bord déchiré. On descend donc jusqu'à 1288 pour recouvrir
  // entièrement le portrait du gabarit, avec un fondu de 70 px qui reproduit
  // ce dégradé et laisse réapparaître la déchirure.
  photo: { x: 1078, y: 410, w: 645, h: 878, r: 52, fondu: 70 },
  // Bandeau vert, une variante par gabarit
  bandeauSeminariste: { x: 100, y: 895, w: 530, h: 118 },
  bandeauCo: { x: 102, y: 892, w: 694, h: 122 },
  // Ligne du nom : aligné sur la petite barre orange verticale, qui occupe
  // x 96→106 et y 690→782. La ligne de base est calée pour que le nom soit
  // optiquement centré sur la barre.
  nom: { x: 131, baseline: 758, largeur: 860, taille: 50 },
  // Sur le gabarit commission, « MEMBRE DU » occupe le début de la ligne
  // du dessous : le nom reste aligné sur la barre.
  nomCo: { x: 131, baseline: 758, largeur: 860, taille: 50 },
  // Mot « Prêt » : le « t » se termine à x = 404, ligne de base à y = 1149
  pret: { finX: 404, baseline: 1149, taille: 120 },
  // Espace libre sous la pastille « Pour l'aventure », au-dessus du bandeau dates
  accroche: { x: 108, y: 1360, largeur: 700, interligne: 44 },
}

const COULEURS = {
  blanc: '#ffffff',
  vertFonce: '#0f5132',
  vert: '#15803d',
  orange: '#ea580c',
}

const F = '"DM Sans", "Poppins", "Segoe UI", Arial, Helvetica, sans-serif'

/**
 * Catégories.
 *   gabarit   : 'co' pour les membres de commission, 'seminariste' sinon
 *   bandeau   : texte affiché dans le bandeau vert
 *   accroche  : petit texte affiché sous « Pour l'aventure »
 * `detail` (commission, secrétariat, niveau, nom du stand) est ajouté à
 * l'accroche quand il est renseigné.
 */
export const CATEGORIES = {
  seminariste: {
    label: 'SÉMINARISTE',
    gabarit: 'seminariste',
    bandeau: 'SÉMINARISTE',
    accroche: 'Rejoins-moi à Korhogo pour huit jours de formation et de fraternité.',
  },
  sr: {
    label: 'SÉMINARISTE SR',
    gabarit: 'seminariste',
    bandeau: 'SÉMINARISTE SR',
    accroche: 'Rejoins-moi à Korhogo pour huit jours de formation et de fraternité.',
  },
  ccr: {
    label: 'SÉMINARISTE CCR',
    gabarit: 'seminariste',
    bandeau: 'SÉMINARISTE CCR',
    accroche: 'Rejoins-moi à Korhogo pour huit jours de formation et de fraternité.',
  },
  /*
   * Le gabarit « MEMBRE DU » n'est pas utilisé par défaut : sa mention occupe
   * exactement la ligne où doit venir le nom, juste sous la barre orange.
   * Les membres de commission prennent donc le gabarit simple, avec
   * « COMITÉ D'ORGANISATION » dans le bandeau et le nom de leur commission
   * dans l'accroche. Basculer `gabarit` sur 'co' réactive l'autre fond.
   */
  commission: {
    label: "COMITÉ D'ORGANISATION",
    gabarit: 'seminariste',
    bandeau: "COMITÉ D'ORGANISATION",
    accroche: 'Je prépare le séminaire pour toi. Rendez-vous à Korhogo !',
  },
  stand: {
    label: 'EXPOSANT · STAND',
    gabarit: 'seminariste',
    bandeau: 'EXPOSANT',
    accroche: 'Retrouve-moi sur mon stand au SENAFOI 2026.',
  },
  formateur: {
    label: 'FORMATEUR',
    gabarit: 'seminariste',
    bandeau: 'FORMATEUR',
    accroche: "J'anime les enseignements du SENAFOI 2026. Rejoins-nous !",
  },
  imam: {
    label: 'IMAM',
    gabarit: 'seminariste',
    bandeau: 'IMAM',
    accroche: "J'assure l'encadrement religieux du SENAFOI 2026.",
  },
  imarat: {
    label: 'IMARAT',
    gabarit: 'seminariste',
    bandeau: 'IMARAT',
    accroche: "Je conduis l'Imarat du SENAFOI 2026. Rejoins-nous à Korhogo !",
  },
}

// ═══════════════════════ Utilitaires ══════════════════════════════════════
function rectArrondi(ctx, x, y, w, h, r) {
  const rr = Math.min(r, w / 2, h / 2)
  ctx.beginPath()
  if (ctx.roundRect) { ctx.roundRect(x, y, w, h, rr); return }
  ctx.moveTo(x + rr, y)
  ctx.arcTo(x + w, y, x + w, y + h, rr)
  ctx.arcTo(x + w, y + h, x, y + h, rr)
  ctx.arcTo(x, y + h, x, y, rr)
  ctx.arcTo(x, y, x + w, y, rr)
  ctx.closePath()
}

/** Réduit la police jusqu'à ce que le texte tienne sur une ligne. */
function ajusterPolice(ctx, texte, largeurMax, tailleMax, tailleMin, poids = '800') {
  let taille = tailleMax
  while (taille > tailleMin) {
    ctx.font = `${poids} ${taille}px ${F}`
    if (ctx.measureText(String(texte || '')).width <= largeurMax) break
    taille -= 1
  }
  ctx.font = `${poids} ${taille}px ${F}`
  return taille
}

function lignesDeTexte(ctx, texte, largeurMax) {
  const mots = String(texte || '').split(/\s+/).filter(Boolean)
  const lignes = []
  let ligne = ''
  for (const mot of mots) {
    const essai = ligne ? `${ligne} ${mot}` : mot
    if (ctx.measureText(essai).width > largeurMax && ligne) {
      lignes.push(ligne)
      ligne = mot
    } else {
      ligne = essai
    }
  }
  if (ligne) lignes.push(ligne)
  return lignes
}

function chargerImageLocale(src) {
  return new Promise((res, rej) => {
    const i = new Image()
    i.onload = () => res(i)
    i.onerror = () => rej(new Error('Gabarit introuvable'))
    i.src = src
  })
}

/** Charge la photo via blob : le canvas reste exportable malgré le CORS. */
async function chargerPhoto(url) {
  if (!url) return null
  try {
    const reponse = await fetch(url)
    if (!reponse.ok) return null
    const blob = await reponse.blob()
    const dataUrl = await new Promise((res) => {
      const r = new FileReader()
      r.onloadend = () => res(r.result)
      r.readAsDataURL(blob)
    })
    return await new Promise((res, rej) => {
      const i = new Image()
      i.onload = () => res(i)
      i.onerror = rej
      i.src = dataUrl
    })
  } catch {
    return null
  }
}

/** Dessine une image en « cover » : jamais de déformation du visage. */
function dessinerCover(ctx, img, x, y, w, h) {
  const ratioCible = w / h
  const ratioImg = img.width / img.height
  let sw = img.width
  let sh = img.height
  let sx = 0
  let sy = 0
  if (ratioImg > ratioCible) {
    sw = img.height * ratioCible
    sx = (img.width - sw) / 2
  } else {
    sh = img.width / ratioCible
    sy = (img.height - sh) / 2
  }
  ctx.drawImage(img, sx, sy, sw, sh, x, y, w, h)
}

/** Vrai si la personne est une sœur : sexe explicite ou drapeau feminin. */
export function estFeminin(p) {
  if (p?.feminin === true) return true
  const s = String(p?.sexe || '').trim().toUpperCase()
  return s === 'F' || s.startsWith('FEM') || s === 'SOEUR' || s === 'SŒUR'
}

// ═══════════════════════ Affiche ══════════════════════════════════════════

/**
 * Génère l'affiche et renvoie le canvas 1800 × 1800.
 *
 * @param {Object} p
 * @param {string} p.nom        Prénom + nom
 * @param {string} p.matricule
 * @param {string} p.photo      URL (facultative)
 * @param {string} p.categorie  clé de CATEGORIES
 * @param {string} p.detail     commission / secrétariat / niveau / nom du stand
 * @param {string} p.sexe       'M' ou 'F' quand la donnée existe
 * @param {boolean} p.feminin   forçage explicite du féminin
 */
export async function genererAffiche(p) {
  const cat = CATEGORIES[p.categorie] || CATEGORIES.seminariste
  const feminin = estFeminin(p)

  const cv = document.createElement('canvas')
  cv.width = T
  cv.height = T
  const ctx = cv.getContext('2d')

  // ── 1. Le gabarit ──
  const gabarit = await chargerImageLocale(cat.gabarit === 'co' ? gabaritCo : gabaritSeminariste)
  ctx.drawImage(gabarit, 0, 0, T, T)

  // ── 2. La photo, dans le cadre ──
  // Elle est composée à part pour pouvoir lui appliquer un fondu bas sans
  // effacer le gabarit déjà dessiné en dessous.
  const z = ZONES.photo
  const photo = await chargerPhoto(p.photo)

  const calque = document.createElement('canvas')
  calque.width = z.w
  calque.height = z.h
  const cctx = calque.getContext('2d')

  if (photo) {
    dessinerCover(cctx, photo, 0, 0, z.w, z.h)
  } else {
    // Repli : initiales sur un dégradé vert, plutôt qu'un cadre vide
    const g = cctx.createLinearGradient(0, 0, z.w, z.h)
    g.addColorStop(0, '#16a34a')
    g.addColorStop(1, '#0f5132')
    cctx.fillStyle = g
    cctx.fillRect(0, 0, z.w, z.h)
    const initiales = String(p.nom || '')
      .split(/\s+/).filter(Boolean).slice(0, 2)
      .map((m) => m.charAt(0)).join('').toUpperCase()
    cctx.fillStyle = 'rgba(255,255,255,.95)'
    cctx.font = `800 240px ${F}`
    cctx.textAlign = 'center'
    cctx.fillText(initiales || 'AE', z.w / 2, z.h / 2 + 60)
  }

  // Fondu du bas : opaque jusqu'à la zone de déchirure, puis transparent.
  //
  // Un seul passage en 'destination-in' avec un dégradé couvrant TOUTE la
  // hauteur. Deux fillRect successifs ne marchent pas : chaque opération
  // 'destination-in' efface aussi tout ce qui se trouve hors du rectangle
  // dessiné, si bien que le second effacerait le premier et le calque
  // ressortirait vide.
  const masque = cctx.createLinearGradient(0, 0, 0, z.h)
  masque.addColorStop(0, 'rgba(0,0,0,1)')
  masque.addColorStop(Math.max(0, (z.h - z.fondu) / z.h), 'rgba(0,0,0,1)')
  masque.addColorStop(1, 'rgba(0,0,0,0)')
  cctx.globalCompositeOperation = 'destination-in'
  cctx.fillStyle = masque
  cctx.fillRect(0, 0, z.w, z.h)
  cctx.globalCompositeOperation = 'source-over'

  ctx.save()
  rectArrondi(ctx, z.x, z.y, z.w, z.h, z.r)
  ctx.clip()
  ctx.drawImage(calque, z.x, z.y)
  ctx.restore()

  // ── 3. Le nom, au-dessus du bandeau ──
  const zn = cat.gabarit === 'co' ? ZONES.nomCo : ZONES.nom
  const nom = String(p.nom || '').toUpperCase()
  ctx.textAlign = 'left'
  ctx.fillStyle = COULEURS.blanc
  ajusterPolice(ctx, nom, zn.largeur, zn.taille, 22, '700')
  ctx.fillText(nom, zn.x, zn.baseline)

  // ── 4. La catégorie, dans le bandeau vert ──
  const b = cat.gabarit === 'co' ? ZONES.bandeauCo : ZONES.bandeauSeminariste
  const texteBandeau = String(p.bandeau || cat.bandeau).toUpperCase()
  ctx.textAlign = 'center'
  ctx.fillStyle = COULEURS.blanc
  ajusterPolice(ctx, texteBandeau, b.w - 56, 58, 24)
  ctx.fillText(texteBandeau, b.x + b.w / 2, b.y + b.h / 2 + 20)

  // ── 5. Le « e » de « Prête » ──
  if (feminin) {
    const pr = ZONES.pret
    ctx.textAlign = 'left'
    ctx.fillStyle = COULEURS.blanc
    ctx.font = `800 ${pr.taille}px ${F}`
    ctx.fillText('e', pr.finX + 2, pr.baseline)
  }

  // ── 6. L'accroche, sous « Pour l'aventure » ──
  // `detail` sert au rattachement (commission, secrétariat, stand). Le niveau
  // et le matricule ne figurent volontairement pas sur l'affiche.
  const a = ZONES.accroche
  const detail = String(p.detail || '').trim()
  ctx.textAlign = 'left'

  let yTexte = a.y
  if (detail) {
    ctx.fillStyle = COULEURS.vert
    ajusterPolice(ctx, detail.toUpperCase(), a.largeur, 34, 18)
    ctx.fillText(detail.toUpperCase(), a.x, yTexte)
    yTexte += a.interligne
  }

  ctx.fillStyle = COULEURS.vertFonce
  ctx.font = `600 32px ${F}`
  for (const ligne of lignesDeTexte(ctx, cat.accroche, a.largeur).slice(0, detail ? 2 : 3)) {
    ctx.fillText(ligne, a.x, yTexte)
    yTexte += a.interligne
  }

  return cv
}

/** Convertit le canvas en Blob, avec repli pour les navigateurs anciens. */
function canvasVersBlob(cv) {
  return new Promise((res) => {
    if (cv.toBlob) {
      cv.toBlob((b) => res(b), 'image/jpeg', 0.92)
      return
    }
    const dataUrl = cv.toDataURL('image/jpeg', 0.92)
    const binaire = atob(dataUrl.split(',')[1])
    const octets = new Uint8Array(binaire.length)
    for (let i = 0; i < binaire.length; i++) octets[i] = binaire.charCodeAt(i)
    res(new Blob([octets], { type: 'image/jpeg' }))
  })
}

export function estIOS() {
  if (typeof navigator === 'undefined') return false
  return /iP(hone|ad|od)/.test(navigator.userAgent)
    || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1)
}

/**
 * Génère puis enregistre l'affiche.
 *
 * Safari sur iPhone ignore l'attribut `download` d'un lien : le fichier
 * s'ouvre au lieu d'être enregistré, voire rien ne se passe avec une data URL
 * de plusieurs mégaoctets. On passe donc d'abord par le partage natif, qui
 * propose « Enregistrer l'image » dans la pellicule, et on ne retombe sur le
 * lien de téléchargement que si ce partage n'est pas disponible.
 *
 * @returns {Promise<{fichier: string, methode: 'partage'|'telechargement'|'onglet'}>}
 */
export async function telechargerAffiche(p) {
  const cv = await genererAffiche(p)
  const fichier = `affiche_senafoi26_${String(p.matricule || 'participant')
    .replace(/[^a-z0-9]+/gi, '_')
    .toLowerCase()}.jpg`

  const blob = await canvasVersBlob(cv)
  if (!blob) throw new Error("Impossible de préparer l'image.")

  // 1. Partage natif : la seule voie fiable sur iPhone
  if (typeof File !== 'undefined' && navigator.share && navigator.canShare) {
    try {
      const image = new File([blob], fichier, { type: 'image/jpeg' })
      if (navigator.canShare({ files: [image] })) {
        await navigator.share({ files: [image], title: 'SENAFOI 2026' })
        return { fichier, methode: 'partage' }
      }
    } catch (e) {
      // Annulation volontaire : on ne relance pas un téléchargement derrière
      if (e && e.name === 'AbortError') return { fichier, methode: 'partage' }
    }
  }

  // 2. Téléchargement classique, via un blob plutôt qu'une data URL
  const url = URL.createObjectURL(blob)
  const lien = document.createElement('a')
  const supporteDownload = 'download' in lien

  if (supporteDownload) {
    lien.href = url
    lien.download = fichier
    lien.rel = 'noopener'
    document.body.appendChild(lien)
    lien.click()
    lien.remove()
    setTimeout(() => URL.revokeObjectURL(url), 60000)
    return { fichier, methode: 'telechargement' }
  }

  // 3. Dernier recours : on ouvre l'image, à enregistrer par appui long
  window.open(url, '_blank')
  setTimeout(() => URL.revokeObjectURL(url), 60000)
  return { fichier, methode: 'onglet' }
}
