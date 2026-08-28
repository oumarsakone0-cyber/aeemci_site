/**
 * AEEMCI — moteur d'animation partagé.
 *
 * `installMotion()` s'exécute une fois au démarrage de l'application :
 *   - génère les motifs (trame khatam, grain) en variables CSS ;
 *   - observe toute la page et révèle les éléments .rv / .mask / .rule / .wipe
 *     au fil du défilement, y compris ceux ajoutés après coup (routes, appels API).
 *
 * `useCountUp(el)` anime un nombre jusqu'à sa valeur cible.
 */

export const prefersReducedMotion = () =>
  typeof window !== 'undefined' &&
  window.matchMedia &&
  window.matchMedia('(prefers-reduced-motion: reduce)').matches

const SELECTOR = '.rv, .mask, .rule, .wipe'
let observer = null
let installed = false

/* ---------- Motifs ---------- */
function starPolygon(cx, cy, r) {
  const pts = []
  for (let i = 0; i < 16; i++) {
    const a = (Math.PI / 8) * i - Math.PI / 2
    const rad = i % 2 === 0 ? r : r * 0.541
    pts.push(`${(cx + rad * Math.cos(a)).toFixed(1)},${(cy + rad * Math.sin(a)).toFixed(1)}`)
  }
  return `<polygon points="${pts.join(' ')}"/>`
}

function toUri(svg) {
  return `url("data:image/svg+xml,${svg
    .replace(/#/g, '%23')
    .replace(/"/g, "'")
    .replace(/</g, '%3C')
    .replace(/>/g, '%3E')}")`
}

function installPatterns() {
  const T = 126
  const R = 29
  const khatam =
    `<svg xmlns="http://www.w3.org/2000/svg" width="${T}" height="${T}" viewBox="0 0 ${T} ${T}">` +
    `<g fill="none" stroke="#164E36" stroke-width="0.9" opacity="0.085">` +
    starPolygon(0, 0, R) + starPolygon(T, 0, R) + starPolygon(0, T, R) +
    starPolygon(T, T, R) + starPolygon(T / 2, T / 2, R) +
    `</g></svg>`

  const grain =
    `<svg xmlns="http://www.w3.org/2000/svg" width="240" height="240">` +
    `<filter id="n"><feTurbulence type="fractalNoise" baseFrequency="0.85" numOctaves="3" stitchTiles="stitch"/>` +
    `<feColorMatrix type="saturate" values="0"/></filter>` +
    `<rect width="240" height="240" filter="url(#n)" opacity="0.5"/></svg>`

  const root = document.documentElement.style
  root.setProperty('--khatam', toUri(khatam))
  root.setProperty('--grain', toUri(grain))
}

/* ---------- Révélations ---------- */
function reveal(el) {
  const parent = el.parentElement
  const sibs = parent
    ? Array.prototype.filter.call(
        parent.children,
        (c) => c.classList && (c.classList.contains('rv') || c.classList.contains('mask'))
      )
    : []
  const i = sibs.indexOf(el)
  el.style.transitionDelay = `${i > 0 ? Math.min(i, 6) * 90 : 0}ms`
  el.classList.add('in')
}

/** Observe les éléments animables présents dans `root` (document par défaut). */
export function scanReveal(root = document) {
  if (!observer) return
  root.querySelectorAll(SELECTOR).forEach((el) => {
    if (!el.classList.contains('in')) observer.observe(el)
  })
}

/** À appeler une seule fois, depuis main.js. */
export function installMotion() {
  if (installed || typeof window === 'undefined') return
  installed = true
  installPatterns()

  const reduce = prefersReducedMotion()

  if (reduce || !('IntersectionObserver' in window)) {
    const showAll = () => document.querySelectorAll(SELECTOR).forEach((el) => el.classList.add('in'))
    showAll()
    // Les vues chargées plus tard doivent l'être aussi.
    new MutationObserver(showAll).observe(document.body, { childList: true, subtree: true })
    return
  }

  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (!e.isIntersecting) return
        reveal(e.target)
        observer.unobserve(e.target)
      })
    },
    { threshold: 0.1, rootMargin: '0px 0px -5% 0px' }
  )

  scanReveal()

  // Vue remplace le DOM à chaque changement de route ou de données : on re-scanne.
  let pending = null
  new MutationObserver(() => {
    if (pending) return
    pending = requestAnimationFrame(() => {
      pending = null
      scanReveal()
    })
  }).observe(document.body, { childList: true, subtree: true })
}

/* ---------- Compteurs ---------- */
function format(n, sep) {
  const s = Math.round(n).toString()
  return sep ? s.replace(/\B(?=(\d{3})+(?!\d))/g, ' ') : s
}

/** Anime un élément portant data-to / data-suf / data-sep. */
export function countUp(el) {
  const to = parseInt(el.getAttribute('data-to'), 10)
  const suf = el.getAttribute('data-suf') || ''
  const sep = el.getAttribute('data-sep')
  const paint = (v) => {
    el.innerHTML = format(v, sep) + (suf ? `<span class="suf">${suf}</span>` : '')
  }
  if (prefersReducedMotion() || Number.isNaN(to)) return paint(to || 0)

  let start = null
  const dur = 1900
  const step = (ts) => {
    if (start === null) start = ts
    const p = Math.min((ts - start) / dur, 1)
    paint(to * (1 - Math.pow(1 - p, 4)))
    if (p < 1) requestAnimationFrame(step)
  }
  requestAnimationFrame(step)
}

/** Déclenche les compteurs d'un conteneur dès qu'ils entrent dans l'écran. */
export function observeCounters(container) {
  const nums = container.querySelectorAll('[data-to]')
  if (!('IntersectionObserver' in window)) {
    nums.forEach(countUp)
    return () => {}
  }
  const io = new IntersectionObserver(
    (entries) => {
      entries.forEach((e) => {
        if (e.isIntersecting) {
          countUp(e.target)
          io.unobserve(e.target)
        }
      })
    },
    { threshold: 0.5 }
  )
  nums.forEach((n) => io.observe(n))
  return () => io.disconnect()
}
