// ── Diplômes et certificats SENAFOI 2026 (retrait public) ─────────────────
//
// Portage de senafoi26/src/utils/diplomes.js, réduit à ce dont la page de
// retrait a besoin : un document, une personne. La référence reste le fichier
// de SENAFOI26 — toute correction de position doit y être reportée.
//
// Le principe est inchangé : on pose le modèle téléversé depuis « Fonds de
// badges », puis on n'ajoute que le nom, la date et la signature. Tout le
// reste du texte est déjà imprimé sur le modèle.

import { jsPDF } from 'jspdf'
// PNG à fond transparent : la signature se pose sur le modèle sans y coller
// un rectangle blanc. Ne pas repasser au JPEG, qui n'a pas de couche alpha.
import signatureAmir from '../assets/logos/signature.png'

const FONDS_API = 'https://api.aeemci-ce.ci/senafoi/senafoi26_badges_fonds_api.php'

/**
 * Un modèle par population. Les positions sont relevées sur les maquettes,
 * en millimètres sur une page A4 paysage de 297 × 210.
 */
export const MODELES = {
  diplome: {
    cle: 'diplome',
    libelle: 'Diplôme de participation',
    nom: { baseline: 105, largeurMax: 148, taille: 30, couleur: [11, 79, 46] },
    date: { x: 52, baseline: 178, taille: 13 },
  },
  certificat_commission: {
    cle: 'certificat_commission',
    libelle: 'Certificat de reconnaissance',
    nom: { baseline: 99, largeurMax: 148, taille: 30, couleur: [11, 79, 46] },
    date: { x: 52, baseline: 178, taille: 13 },
  },
  diplome_formateur: {
    cle: 'diplome_formateur',
    libelle: 'Diplôme — Formateur',
    nom: { baseline: 105, largeurMax: 148, taille: 30, couleur: [11, 79, 46] },
    date: { x: 52, baseline: 178, taille: 13 },
  },
  diplome_imam: {
    cle: 'diplome_imam',
    libelle: 'Diplôme — Imam',
    nom: { baseline: 105, largeurMax: 148, taille: 30, couleur: [11, 79, 46] },
    date: { x: 52, baseline: 178, taille: 13 },
  },
  diplome_stand: {
    cle: 'diplome_stand',
    libelle: 'Certificat — Stand',
    nom: { baseline: 105, largeurMax: 148, taille: 30, couleur: [11, 79, 46] },
    date: { x: 52, baseline: 178, taille: 13 },
  },
}

// Signature apposée sur tous les documents, quel que soit le modèle.
const SIGNATURE = {
  image: signatureAmir,
  cx: 238,        // centre horizontal, en miroir de la date à gauche
  bas: 176,       // le bas de l'image repose juste au-dessus du trait
  largeur: 46,
  rapport: 447 / 205,
}

// ── Fonds téléversés ──────────────────────────────────────────────────────
let promesseFonds = null
const cacheFonds = new Map()

function chargerFonds() {
  if (!promesseFonds) {
    promesseFonds = fetch(`${FONDS_API}?action=list&rand=${Date.now()}`)
      .then((r) => r.json())
      .then((d) => (d && d.success ? d.data || {} : {}))
      .catch(() => ({}))   // hors ligne : le document sortira sans fond
  }
  return promesseFonds
}

function chargerImage(src) {
  return new Promise((resolve, reject) => {
    const img = new Image()
    img.onload = () => resolve(img)
    img.onerror = reject
    img.src = src
  })
}

/**
 * Le fond passe par un blob : aucun blocage d'origine croisée, contrairement
 * à un chargement direct dans une balise Image.
 *
 * Le résultat est mis en cache sous forme de promesse, et non de valeur : deux
 * appels rapprochés partagent alors le même téléchargement au lieu d'en lancer
 * deux. C'est ce qui rend le préchargement utile.
 */
function obtenirFond(cle) {
  if (!cacheFonds.has(cle)) cacheFonds.set(cle, telechargerFond(cle))
  return cacheFonds.get(cle)
}

/**
 * Lance le téléchargement du modèle sans attendre le résultat. Appelé dès que
 * la personne est identifiée : le fond est prêt avant même qu'elle ne clique,
 * ce qui supprime l'attente au moment du téléchargement.
 */
export function prechargerFond(cle) {
  if (cle) obtenirFond(cle).catch(() => {})
}

async function telechargerFond(cle) {
  const fonds = await chargerFonds()
  const url = fonds?.[cle]?.url
  let resultat = null

  if (url) {
    try {
      const reponse = await fetch(url)
      if (reponse.ok) {
        const blob = await reponse.blob()
        const dataUrl = await new Promise((res) => {
          const fr = new FileReader()
          fr.onloadend = () => res(fr.result)
          fr.readAsDataURL(blob)
        })
        resultat = {
          image: await chargerImage(dataUrl),
          format: dataUrl.includes('image/png') ? 'PNG' : 'JPEG',
        }
      }
    } catch {
      resultat = null   // téléversement cassé : on n'empêche pas l'impression
    }
  }

  // Pas de mise en cache ici : c'est obtenirFond() qui garde la promesse,
  // sinon on écraserait celle-ci par sa propre valeur résolue.
  return resultat
}

// ── Rendu ─────────────────────────────────────────────────────────────────

/**
 * Date lisible, telle qu'elle sera imprimée. Le français écrit les mois en
 * minuscule, mais sur un document officiel la capitale est d'usage.
 */
export function dateLisible(dateISO) {
  const d = new Date(`${dateISO}T12:00:00`)
  if (Number.isNaN(d.getTime())) return String(dateISO || '')
  const mois = d.toLocaleDateString('fr-FR', { month: 'long' })
  return `${d.getDate()} ${mois.charAt(0).toLocaleUpperCase('fr-FR')}${mois.slice(1)} ${d.getFullYear()}`
}

function nomComplet(personne) {
  const nom = personne.nomFamille || personne.nom_famille || personne.nom || ''
  const prenom = personne.prenom || ''
  return `${nom} ${prenom}`.replace(/\s+/g, ' ').trim().toUpperCase()
}

/**
 * Dessine un document dans la page courante.
 * @returns {boolean} false si aucun modèle n'a été déposé
 */
export async function rendreDocument(doc, personne, modele, dateISO) {
  const m = typeof modele === 'string' ? MODELES[modele] : modele
  if (!m) throw new Error('Modèle de document inconnu')

  doc.saveGraphicsState()
  const pW = doc.internal.pageSize.getWidth()
  const pH = doc.internal.pageSize.getHeight()

  let fondPose = false
  try {
    const fond = await obtenirFond(m.cle)
    if (fond) {
      doc.addImage(fond.image, fond.format, 0, 0, pW, pH)
      fondPose = true
    }
  } catch (e) {
    console.warn(`Modèle « ${m.cle} » non chargé`, e)
  }

  // ── Nom, centré sur le trait d'attribution ──────────────────────────
  const texte = nomComplet(personne)
  doc.setFont('times', 'bold')
  doc.setTextColor(...m.nom.couleur)

  let taille = m.nom.taille
  doc.setFontSize(taille)
  while (taille > 14 && doc.getTextWidth(texte) > m.nom.largeurMax) {
    taille -= 1
    doc.setFontSize(taille)
  }
  doc.text(texte, pW / 2, m.nom.baseline, { align: 'center' })

  // ── Date ────────────────────────────────────────────────────────────
  doc.setFont('times', 'italic')
  doc.setFontSize(m.date.taille)
  doc.setTextColor(60, 60, 60)
  doc.text(dateLisible(dateISO), m.date.x, m.date.baseline, { align: 'center' })

  // ── Signature ───────────────────────────────────────────────────────
  const sg = { ...SIGNATURE, ...(m.signature || {}) }
  try {
    const hauteur = sg.largeur / sg.rapport
    doc.addImage(sg.image, 'PNG', sg.cx - sg.largeur / 2, sg.bas - hauteur,
                 sg.largeur, hauteur)
  } catch (e) {
    console.warn('Signature non apposée', e)
  }

  doc.restoreGraphicsState()
  return fondPose
}

/**
 * Génère et télécharge le document d'une seule personne.
 * @returns {boolean} false si le modèle manque — le document sort sans fond
 */
export async function telechargerDocument(personne, modele, dateISO, nomFichier) {
  const doc = new jsPDF({ orientation: 'landscape', unit: 'mm', format: 'a4' })
  const pose = await rendreDocument(doc, personne, modele, dateISO)
  doc.save(nomFichier)
  return pose
}
