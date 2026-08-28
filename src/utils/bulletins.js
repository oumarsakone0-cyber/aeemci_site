// ── Bulletin de note — SENAFOI 2026 (retrait public) ──────────────────────
//
// Portage de senafoi26/src/utils/bulletins.js. La référence reste le fichier
// de SENAFOI26 : le rendu doit rester identique au bulletin imprimé par
// l'administration, sans quoi deux versions du même document circuleraient.
//
// Seule différence : la signature et les logos sont livrés avec
// l'application plutôt que chargés depuis l'API, la page étant publique.

import { jsPDF } from 'jspdf'
import autoTable from 'jspdf-autotable'
import logoAeemci from '../assets/logos/aeemci.jpg'
import logoSenafoi from '../assets/logos/senafoi.jpeg'
// Signature officielle du Président National, la même que celle des diplômes.
// PNG à fond transparent : elle se pose sur le bulletin sans y coller un
// rectangle blanc. Ne pas repasser au JPEG, qui n'a pas de couche alpha.
import signatureOfficielle from '../assets/logos/signature.png'

// Proportions réelles du fichier : la signature garde sa forme quelle que
// soit la largeur d'impression.
const RAPPORT_SIGNATURE = 720 / 307

const VERT = [15, 81, 50]
const VERT_CLAIR = [220, 252, 231]
const ROUGE = [185, 28, 28]
const ROUGE_CLAIR = [254, 226, 226]
const GRIS = [107, 114, 128]
const OR = [180, 140, 30]

function moisCapitale(date) {
  const mois = date.toLocaleDateString('fr-FR', { month: 'long' })
  return mois.charAt(0).toLocaleUpperCase('fr-FR') + mois.slice(1)
}

function dateLisible(date = new Date()) {
  return `${date.getDate()} ${moisCapitale(date)} ${date.getFullYear()}`
}

function mentionDe(moyenne) {
  if (moyenne >= 16) return 'Excellent'
  if (moyenne >= 14) return 'Très Bien'
  if (moyenne >= 12) return 'Bien'
  if (moyenne >= 10) return 'Assez Bien'
  return 'Insuffisant'
}

/**
 * Photo recadrée en carré, prête pour le PDF. Passe par un blob : le cloud
 * est sur un autre domaine et un chargement direct serait bloqué.
 * Retourne null si l'image est absente ou illisible — le bulletin reste
 * imprimable, avec les initiales à la place.
 */
async function photoCarree(url, cote = 200) {
  if (!url) return null
  try {
    const blob = await (await fetch(url)).blob()
    const dataUrl = await new Promise((res) => {
      const fr = new FileReader()
      fr.onloadend = () => res(fr.result)
      fr.readAsDataURL(blob)
    })
    const img = await new Promise((res, rej) => {
      const i = new Image()
      i.onload = () => res(i)
      i.onerror = rej
      i.src = dataUrl
    })
    const cv = document.createElement('canvas')
    cv.width = cote
    cv.height = cote
    const ctx = cv.getContext('2d')
    const min = Math.min(img.width, img.height)
    ctx.drawImage(img, (img.width - min) / 2, (img.height - min) / 2, min, min, 0, 0, cote, cote)
    return cv.toDataURL('image/jpeg', 0.85)
  } catch {
    return null
  }
}

/** Charge une image et la convertit en data URL base64. Retourne null en cas
 *  d'échec : la signature devient alors simplement absente. */
async function chargerImage(url) {
  try {
    const reponse = await fetch(url)
    if (!reponse.ok) return null
    const blob = await reponse.blob()
    return await new Promise((resolve) => {
      const fr = new FileReader()
      fr.onloadend = () => resolve(fr.result)
      fr.readAsDataURL(blob)
    })
  } catch {
    return null
  }
}

/** Dessine un bulletin complet dans la page courante du document. */
function dessinerBulletin(doc, b, { titreTest, niveau, total, image, signature }) {
  const pW = 210, pH = 297

  // Épreuve éliminatoire : en dessous de 10 au test de sortie, le candidat est
  // refusé quelle que soit sa moyenne générale.
  const echecSortie = b.echec_sortie ?? (b.note_sortie < 10)

  // ── Fond et cadre général ────────────────────────────────────────────
  doc.setFillColor(250, 251, 250)
  doc.rect(0, 0, pW, pH, 'F')
  doc.setDrawColor(...VERT)
  doc.setLineWidth(1.1)
  doc.rect(8, 8, pW - 16, pH - 16)
  doc.setLineWidth(0.3)
  doc.rect(11, 11, pW - 22, pH - 22)

  // ── En-tête ───────────────────────────────────────────────────────────
  doc.setFillColor(...VERT)
  doc.rect(11, 11, pW - 22, 32, 'F')

  doc.setFillColor(255, 255, 255)
  doc.roundedRect(14, 14, 20, 20, 3, 3, 'F')
  doc.addImage(logoAeemci, 'JPEG', 15, 15, 18, 18)

  const largeurSenafoi = 32, hauteurSenafoi = 8.94
  const xSenafoi = pW - 15 - largeurSenafoi
  const ySenafoi = 27 - hauteurSenafoi / 2
  doc.roundedRect(xSenafoi - 2, ySenafoi - 2, largeurSenafoi + 4, hauteurSenafoi + 4, 3, 3, 'F')
  doc.addImage(logoSenafoi, 'JPEG', xSenafoi, ySenafoi, largeurSenafoi, hauteurSenafoi)

  doc.setTextColor(255, 255, 255)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(7.5)
  doc.text('AEEMCI — SÉMINAIRE NATIONAL DE FORMATION ISLAMIQUE ET MANAGÉRIALE 2026',
           pW / 2, 20, { align: 'center' })
  doc.setFontSize(19)
  doc.text('BULLETIN DE NOTE', pW / 2, 30, { align: 'center' })
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(9.5)
  doc.text(`Niveau ${niveau} · ${titreTest || `Test de sortie — Niveau ${niveau}`}`,
           pW / 2, 38, { align: 'center' })
  doc.setTextColor(17, 24, 39)

  // ── Identité du séminariste ──────────────────────────────────────────
  const yId = 52
  if (image) {
    doc.setDrawColor(...VERT)
    doc.setLineWidth(0.6)
    doc.rect(15, yId, 28, 28)
    doc.addImage(image, 'JPEG', 15, yId, 28, 28)
  } else {
    doc.setFillColor(...VERT_CLAIR)
    doc.rect(15, yId, 28, 28, 'F')
    doc.setTextColor(...VERT)
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(16)
    const ini = `${(b.prenom || '')[0] || ''}${(b.nom || '')[0] || ''}`.toUpperCase() || '?'
    doc.text(ini, 29, yId + 17, { align: 'center' })
    doc.setTextColor(17, 24, 39)
  }

  doc.setFont('helvetica', 'bold')
  doc.setFontSize(15)
  doc.text(`${(b.prenom || '').toUpperCase()} ${(b.nom || '').toUpperCase()}`.trim(), 48, yId + 8)

  doc.setFont('helvetica', 'normal')
  doc.setFontSize(10)
  doc.setTextColor(...GRIS)
  doc.text(`Matricule : ${b.matricule || '—'}`, 48, yId + 15)
  doc.text(`Niveau : ${b.niveau_seminaire || `Niveau ${niveau}`}`, 48, yId + 21)
  doc.text(`Rang : ${b.rang} / ${total}`, 48, yId + 27)
  doc.setTextColor(17, 24, 39)

  // ── Tableau des notes ─────────────────────────────────────────────────
  autoTable(doc, {
    startY: yId + 36,
    margin: { left: 15, right: 15 },
    head: [['Épreuve', 'Note / 20', 'Coefficient', 'Note pondérée']],
    body: [
      ['Test de sortie', b.note_sortie.toFixed(2), '2', (b.note_sortie * 2).toFixed(2)],
      ['Conduite', b.note_conduite.toFixed(2), '1', (b.note_conduite * 1).toFixed(2)],
    ],
    foot: [['Total', '', '3', (b.note_sortie * 2 + b.note_conduite).toFixed(2)]],
    theme: 'grid',
    styles: { fontSize: 11, cellPadding: 5, halign: 'center', lineColor: [220, 226, 220], lineWidth: 0.25 },
    headStyles: { fillColor: VERT, textColor: 255, fontStyle: 'bold' },
    footStyles: { fillColor: [240, 245, 241], textColor: [17, 24, 39], fontStyle: 'bold' },
    columnStyles: { 0: { halign: 'left', fontStyle: 'bold' } },
    // La note éliminatoire est signalée en rouge dès le tableau.
    didParseCell: (d) => {
      if (echecSortie && d.section === 'body' && d.row.index === 0) {
        d.cell.styles.fillColor = ROUGE_CLAIR
        d.cell.styles.textColor = ROUGE
        d.cell.styles.fontStyle = 'bold'
      }
    },
  })

  const yApres = doc.lastAutoTable.finalY + 14

  // ── Moyenne générale ─────────────────────────────────────────────────
  doc.setFillColor(...VERT_CLAIR)
  doc.roundedRect(15, yApres, pW - 30, 34, 4, 4, 'F')
  doc.setTextColor(...VERT)
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(11)
  doc.text('MOYENNE GÉNÉRALE', pW / 2, yApres + 11, { align: 'center' })
  doc.setFontSize(30)
  doc.text(`${b.moyenne.toFixed(2)} / 20`, pW / 2, yApres + 26, { align: 'center' })
  doc.setTextColor(17, 24, 39)

  // ── Mention ──────────────────────────────────────────────────────────
  const yMention = yApres + 44
  doc.setFont('helvetica', 'italic')
  doc.setFontSize(11)
  doc.setTextColor(...GRIS)
  doc.text(`Mention : ${mentionDe(b.moyenne)}`, pW / 2, yMention, { align: 'center' })
  doc.setTextColor(17, 24, 39)

  if (echecSortie) {
    doc.setFont('helvetica', 'bold')
    doc.setFontSize(9.5)
    doc.setTextColor(...ROUGE)
    doc.text('Moyenne au test de sortie inférieure à 10 — épreuve éliminatoire',
             pW / 2, yMention + 7, { align: 'center' })
    doc.setTextColor(17, 24, 39)
  }

  // ── Tampon ADMIS / REFUSÉ ────────────────────────────────────────────
  const admis = b.admis ?? (b.moyenne >= 10 && !echecSortie)
  doc.saveGraphicsState()
  doc.setGState(new doc.GState({ opacity: 0.92 }))
  const cx = pW / 2, cy = yMention + 34
  doc.setDrawColor(...(admis ? VERT : ROUGE))
  doc.setFillColor(...(admis ? VERT_CLAIR : ROUGE_CLAIR))
  doc.setLineWidth(1.4)
  const largeurTampon = 96, hauteurTampon = 24
  doc.roundedRect(cx - largeurTampon / 2, cy - hauteurTampon / 2, largeurTampon, hauteurTampon, 3, 3, 'FD')
  doc.setTextColor(...(admis ? VERT : ROUGE))
  doc.setFont('helvetica', 'bold')
  doc.setFontSize(26)
  doc.text(admis ? 'ADMIS' : 'REFUSÉ', cx, cy + 3, { align: 'center', angle: 0 })
  doc.restoreGraphicsState()
  doc.setTextColor(17, 24, 39)

  // ── Pied de page : date et signature ─────────────────────────────────
  const ySign = pH - 40
  doc.setFont('helvetica', 'normal')
  doc.setFontSize(9)
  doc.setTextColor(...GRIS)
  doc.text(`Fait à Abidjan, le ${dateLisible()}`, 15, ySign)

  doc.setDrawColor(...GRIS)
  doc.setLineWidth(0.25)
  doc.line(pW - 75, ySign + 12, pW - 15, ySign + 12)

  if (signature) {
    const largeurSignature = 36
    const hauteurSignature = largeurSignature / RAPPORT_SIGNATURE
    doc.addImage(signature, 'PNG', pW - 45 - largeurSignature / 2,
                 ySign + 12 - hauteurSignature - 1, largeurSignature, hauteurSignature)
  }

  doc.text("L'Amir Youssouf BAMBA", pW - 45, ySign + 17, { align: 'center' })

  doc.setFontSize(7.5)
  doc.setTextColor(...OR)
  doc.text('AEEMCI · Pour une identité islamique', pW / 2, pH - 14, { align: 'center' })
  doc.setTextColor(17, 24, 39)
}

/**
 * Génère et télécharge le bulletin d'un seul séminariste.
 * @param {Object} b  bulletin renvoyé par senafoi26_documents_api.php
 */
export async function telechargerBulletin(b, nomFichier) {
  const [image, signature] = await Promise.all([
    photoCarree(b.photo, 200),
    chargerImage(signatureOfficielle),
  ])
  const doc = new jsPDF('p', 'mm', 'a4')
  dessinerBulletin(doc, b, {
    titreTest: b.titre_test,
    niveau: b.niveau,
    total: b.total_niveau,
    image,
    signature,
  })
  doc.save(nomFichier || `bulletin_${b.matricule}.pdf`)
  return true
}
