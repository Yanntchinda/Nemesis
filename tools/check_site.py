#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Contrôle qualité du site Travex Global Forwarding.

Vérifie, pour chaque page HTML du dépôt :
  1. l'équilibre des balises (HTML5, éléments auto-fermants ignorés) ;
  2. que toute clé `data-i18n*` existe bien dans le dictionnaire anglais
     (window.I18N.en) de assets/js/translations.js — une clé manquante fait
     retomber la page en français lors du basculement EN ;
  3. que les liens internes (`*.html`, `*.html#ancre`) pointent vers une page
     existante et une ancre réellement présente ;
  4. l'absence d'`id` dupliqués dans une même page.

Usage : python3 tools/check_site.py [fichier.html ...]
Sortie : rapport sur stdout, code retour 1 si au moins une anomalie.
"""
import re
import sys
from pathlib import Path
from html.parser import HTMLParser

ROOT = Path(__file__).resolve().parent.parent

VOID = {"area", "base", "br", "col", "embed", "hr", "img", "input", "link",
        "meta", "param", "source", "track", "wbr"}


class BalanceChecker(HTMLParser):
    def __init__(self):
        super().__init__(convert_charrefs=True)
        self.stack = []
        self.errors = []

    def handle_starttag(self, tag, attrs):
        if tag in VOID:
            return
        self.stack.append((tag, self.getpos()[0]))

    def handle_startendtag(self, tag, attrs):
        pass

    def handle_endtag(self, tag):
        if tag in VOID:
            return
        if not self.stack:
            self.errors.append("ligne %d : </%s> sans ouverture" % (self.getpos()[0], tag))
            return
        open_tag, line = self.stack.pop()
        if open_tag != tag:
            self.errors.append("ligne %d : </%s> ferme <%s> ouvert ligne %d"
                              % (self.getpos()[0], tag, open_tag, line))

    def close(self):
        super().close()
        for tag, line in self.stack:
            self.errors.append("ligne %d : <%s> jamais refermé" % (line, tag))


def load_en_keys():
    txt = (ROOT / "assets/js/translations.js").read_text(encoding="utf-8")
    m = re.search(r"\ben:\s*\{", txt)
    if not m:
        return set()
    start, depth, i = m.end(), 1, m.end()
    while i < len(txt) and depth:
        if txt[i] == "{":
            depth += 1
        elif txt[i] == "}":
            depth -= 1
        i += 1
    return set(re.findall(r'"([^"]+)"\s*:', txt[start:i]))


def pages(argv):
    if argv:
        return [Path(a) if Path(a).is_absolute() else ROOT / a for a in argv]
    skip = {"travex-global-forwarding.html"}  # build autonome, généré
    return [p for p in sorted(ROOT.glob("*.html")) if p.name not in skip]


def anchors_of(html):
    return set(re.findall(r'\bid="([^"]+)"', html)) | set(
        re.findall(r'<a[^>]*\bname="([^"]+)"', html))


problems = 0
en_keys = load_en_keys()

for page in pages(sys.argv[1:]):
    html = page.read_text(encoding="utf-8")

    # 1. équilibre des balises (on ignore <head>, les meta/link étant auto-fermants)
    checker = BalanceChecker()
    checker.feed(html)
    checker.close()
    for e in checker.errors[:6]:
        print("%-46s BALISES  %s" % (page.name, e))
        problems += 1

    # 2. couverture des clés de traduction
    used = set()
    for attr in ("data-i18n", "data-i18n-placeholder", "data-i18n-aria", "data-i18n-content"):
        used |= set(re.findall(attr + r'="([^"]+)"', html))
    missing = sorted(k for k in used if k not in en_keys)
    if missing:
        print("%-46s TRAD     %d clé(s) sans anglais : %s"
              % (page.name, len(missing), ", ".join(missing[:8])))
        problems += 1

    # 3. liens internes + ancres
    pages_here = {p.stem for p in ROOT.glob("*.html")}
    bad = []
    for target in re.findall(r'href="([^"#]+\.html)(#[^"]*)?"', html):
        fname, frag = target
        if Path(fname).stem not in pages_here:
            bad.append(fname)
        elif frag:
            other = (ROOT / fname)
            if other.exists() and frag[1:] not in anchors_of(other.read_text(encoding="utf-8")):
                bad.append(fname + frag)
    for frag in re.findall(r'href="#([^"./]+)"', html):
        if frag not in anchors_of(html):
            bad.append("#" + frag)
    if bad:
        print("%-46s LIENS    %s" % (page.name, ", ".join(sorted(set(bad))[:8])))
        problems += 1

    # 4. ids dupliqués
    ids = re.findall(r'\bid="([^"]+)"', html)
    dupes = sorted({i for i in ids if ids.count(i) > 1})
    if dupes:
        print("%-46s IDS      dupliqués : %s" % (page.name, ", ".join(dupes[:8])))
        problems += 1

print("-" * 60)
print("Anomalies : %d" % problems)
sys.exit(1 if problems else 0)
