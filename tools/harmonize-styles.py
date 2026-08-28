#!/usr/bin/env python3
"""
Harmonise les feuilles de style des composants existants avec le système
de design « Édition Blanche ».

N'intervient QUE dans les blocs <style> : les templates, le JavaScript et
les chaînes de caractères ne sont jamais touchés.

Trois passes :
  1. couleurs   — les littéraux hexadécimaux hérités deviennent des jetons CSS ;
  2. géométrie  — les grands rayons de bordure passent à zéro (angles vifs) ;
  3. ombres     — les ombres portées deviennent une ombre unique et discrète.
"""

import re
import sys
from pathlib import Path

# ---------------------------------------------------------------- couleurs
GREEN = {
    '#018404', '#016a03', '#017a3e', '#15803d', '#16a34a', '#14532d', '#166534',
    '#047857', '#065f46', '#064e3b', '#0f766e', '#065F46', '#134e4a', '#22543d',
    '#276749', '#2f855a', '#38a169',
    # palette héritée du gabarit d'origine
    '#16a085', '#138d75', '#0e5a5a', '#27ae60', '#28a745',
    '#006400', '#004d00', '#008000', '#22331d',
}
GREEN_SOFT = {'#10b981', '#22c55e', '#34d399', '#4ade80', '#48bb78'}
GREEN_MIST = {
    '#f0fdf4', '#ecfdf3', '#ecfdf5', '#dcfce7', '#d1fae5', '#bbf7d0', '#a7f3d0',
    '#86efac', '#f0fff4', '#c6f6d5', '#e6fffa',
}
GOLD = {'#fb923c', '#fbbf24', '#f59e0b', '#ea580c', '#d97706', '#f97316', '#fcd34d',
        '#ed8936', '#dd6b20', '#f6ad55', '#ecc94b', '#d69e2e',
        '#f65a11', '#e54a0a', '#ff6a00', '#e67e22', '#d35400', '#f39c12',
        '#f8b400', '#d99b00', '#ffc933', '#facc15', '#ffed4e', '#9a3412'}
GOLD_MIST = {'#fef3c7', '#fffbeb', '#fef9c3', '#fefce8', '#fff7ed', '#ffedd5', '#fffaf0'}

INK = {'#111827', '#0f172a', '#1a1a1a', '#2d3748', '#1f2937', '#1a202c', '#171923', '#213547',
       '#2c3e50', '#34495e', '#333', '#333333', '#222', '#212529'}
INK_SOFT = {'#374151', '#334155', '#4b5563', '#2d3748', '#4a5568', '#475569'}
MUTED = {'#64748b', '#6b7280', '#94a3b8', '#9ca3af', '#718096', '#a0aec0', '#8b95a1',
         '#666666', '#666', '#888', '#888888', '#999', '#999999', '#555', '#616d69', '#7f8c8d'}
LINE = {'#e5e7eb', '#e2e8f0', '#edf2f7', '#eeeeee', '#eee', '#e5e5e5', '#ddd', '#dddddd',
        '#cbd5e0', '#cbd5e1', '#bdc3c7', '#e0e0e0', '#d9d9d9', '#dee2e6'}
SURFACE = {'#f8fafc', '#f9fafb', '#fafafa', '#f7fafc', '#f5f5f5', '#f1f5f9', '#efede7',
           '#f7f7f7', '#fbfdfb', '#f8f9fa', '#e9ecef', '#ecf0f1'}

TOKEN_BY_COLOR = {}
for group, token in (
    (GREEN, 'var(--green)'),
    (GREEN_SOFT, 'var(--green-2)'),
    (GREEN_MIST, 'var(--green-mist)'),
    (GOLD, 'var(--gold)'),
    (GOLD_MIST, 'var(--gold-mist)'),
    (INK, 'var(--ink)'),
    (INK_SOFT, 'var(--ink-soft)'),
    (MUTED, 'var(--muted)'),
    (LINE, 'var(--line)'),
    (SURFACE, 'var(--surface)'),
):
    for c in group:
        TOKEN_BY_COLOR.setdefault(c.lower(), token)

HEX_RE = re.compile(r'#[0-9a-fA-F]{3,8}\b')


def map_colors(css: str) -> tuple[str, int]:
    count = 0

    def sub(m):
        nonlocal count
        raw = m.group(0)
        token = TOKEN_BY_COLOR.get(raw.lower())
        if token is None:
            return raw
        count += 1
        return token

    return HEX_RE.sub(sub, css), count


# --------------------------------------------------------------- géométrie
RADIUS_RE = re.compile(r'border-radius\s*:\s*([^;}]+)', re.IGNORECASE)
KEEP_RADIUS = re.compile(r'(50%|9999px|999px|100%|9999em)', re.IGNORECASE)


def square_corners(css: str) -> tuple[str, int]:
    count = 0

    def sub(m):
        nonlocal count
        value = m.group(1).strip()
        # Pastilles et cercles conservent leur forme.
        if KEEP_RADIUS.search(value) or value.startswith('var('):
            return m.group(0)
        if value in ('0', '0px', '0 0 0 0'):
            return m.group(0)
        count += 1
        return 'border-radius: 0'

    return RADIUS_RE.sub(sub, css), count


# ------------------------------------------------------------------ ombres
SHADOW_RE = re.compile(r'box-shadow\s*:\s*([^;}]+)', re.IGNORECASE)


def soften_shadows(css: str) -> tuple[str, int]:
    count = 0

    def sub(m):
        nonlocal count
        value = m.group(1).strip()
        low = value.lower()
        # Anneaux de focus, ombres internes et valeurs déjà tokenisées : intactes.
        if 'inset' in low or low.startswith('none') or low.startswith('var(') or low.startswith('0 0 0'):
            return m.group(0)
        count += 1
        return 'box-shadow: var(--shadow)'

    return SHADOW_RE.sub(sub, css), count


# ------------------------------------------------------------- typographie
FONT_RE = re.compile(r"font-family\s*:\s*([^;}]+)", re.IGNORECASE)
SERIF_HINT = re.compile(r"(serif|Zain|Georgia|Playfair|Marcellus)", re.IGNORECASE)


def map_fonts(css: str) -> tuple[str, int]:
    count = 0

    def sub(m):
        nonlocal count
        value = m.group(1).strip()
        if value.startswith('var(') or 'monospace' in value.lower() or 'inherit' in value.lower():
            return m.group(0)
        count += 1
        token = 'var(--display)' if SERIF_HINT.search(value) else 'var(--sans)'
        return f'font-family: {token}'

    return FONT_RE.sub(sub, css), count


STYLE_BLOCK = re.compile(r'(<style[^>]*>)(.*?)(</style>)', re.DOTALL | re.IGNORECASE)


def harmonize(source: str) -> tuple[str, dict]:
    totals = {'couleurs': 0, 'rayons': 0, 'ombres': 0, 'polices': 0}

    def sub(m):
        head, css, tail = m.group(1), m.group(2), m.group(3)
        css, n = map_colors(css);      totals['couleurs'] += n
        css, n = square_corners(css);  totals['rayons'] += n
        css, n = soften_shadows(css);  totals['ombres'] += n
        css, n = map_fonts(css);       totals['polices'] += n
        return head + css + tail

    return STYLE_BLOCK.sub(sub, source), totals


def main(paths):
    changed, grand = [], {'couleurs': 0, 'rayons': 0, 'ombres': 0, 'polices': 0}
    for path in paths:
        p = Path(path)
        if not p.exists():
            print('ABSENT', path)
            continue
        src = p.read_text(encoding='utf-8')
        out, totals = harmonize(src)
        if out != src:
            p.write_text(out, encoding='utf-8')
            changed.append(str(p))
            for k in grand:
                grand[k] += totals[k]
    print(f'{len(changed)} fichier(s) modifié(s)')
    print('   ', ', '.join(f'{v} {k}' for k, v in grand.items()))
    return changed


if __name__ == '__main__':
    main(sys.argv[1:])
