#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Build a single self-contained HTML file (travex-global-forwarding.html)
embedding the whole Travex Global Forwarding website:
  - all 8 pages as <section class="spa-page"> blocks + tiny hash router
  - CSS, JS and images inlined (images -> base64 data URIs, icons sprite inlined)
  - DE/EN/FR language switcher fully functional offline (Google Fonts need network)

Usage:  python3 tools/build_standalone.py
"""
import base64
import html
import re
from pathlib import Path

ROOT = Path(__file__).resolve().parent.parent
OUT = ROOT / "travex-global-forwarding.html"

PAGES = [
    ("index",           "index.html"),
    ("services",        "services.html"),
    ("a-propos",        "a-propos.html"),
    ("cotation",        "cotation.html"),
    ("reservations",    "reservations.html"),
    ("contact",         "contact.html"),
    ("mentions-legales","mentions-legales.html"),
    ("landing",         "travex_global_forwarding_spedition_international.html"),
]
FILE2KEY = {Path(fn).stem: key for key, fn in PAGES}

IMG_CACHE = {}


def data_uri(rel_path: str) -> str:
    p = ROOT / rel_path
    if rel_path.endswith(".jpg") or rel_path.endswith(".jpeg"):
        mime = "image/jpeg"
    elif rel_path.endswith(".svg"):
        mime = "image/svg+xml"
    elif rel_path.endswith(".png"):
        mime = "image/png"
    else:
        mime = "application/octet-stream"
    return "data:%s;base64,%s" % (mime, base64.b64encode(p.read_bytes()).decode())


def inline_assets(s: str) -> str:
    def img_repl(m):
        src = m.group(1)
        if src not in IMG_CACHE:
            IMG_CACHE[src] = data_uri(src)
        return 'src="%s"' % IMG_CACHE[src]

    s = re.sub(r'src="(assets/img/[^"]+)"', img_repl, s)
    s = re.sub(r'(<use\s+(?:xlink:)?href=")assets/img/icons\.svg#', r"\1#", s)
    return s


def rewrite_links(s: str, page_key: str) -> str:
    def repl(m):
        target = m.group(1)
        mm = re.match(r"^([A-Za-z0-9_-]+)\.html(?:\?([^#]*))?(?:#(.+))?$", target)
        if mm and mm.group(1) in FILE2KEY:
            out = "#" + FILE2KEY[mm.group(1)]
            if mm.group(2):
                out += "?" + mm.group(2)
            if mm.group(3):
                out += "/" + mm.group(3)
            return 'href="%s"' % out
        if target.startswith("#") and len(target) > 1:
            return 'href="#%s/%s"' % (page_key, target[1:])
        return m.group(0)

    return re.sub(r'href="([^"]+)"', repl, s)


def extract(pattern, source, flags=re.S):
    m = re.search(pattern, source, flags)
    if not m:
        raise SystemExit("pattern not found: %s" % pattern[:60])
    return m.group(0)


# ---------------------------------------------------------------- load pages
sections = []
for key, fname in PAGES:
    raw = (ROOT / fname).read_text(encoding="utf-8")
    main_html = extract(r"<main[^>]*>(.*)</main>", raw)
    main_html = re.sub(r"^<main[^>]*>", "", main_html)
    main_html = re.sub(r"</main>$", "", main_html)

    title_m = re.search(r"<title[^>]*>(.*?)</title>", raw, re.S)
    title_key_m = re.search(r'<title[^>]*data-i18n="([^"]+)"', raw)
    title_text = html.unescape(title_m.group(1)).strip() if title_m else "Travex Global Forwarding"
    title_key = title_key_m.group(1) if title_key_m else ""

    main_html = rewrite_links(main_html, key)
    main_html = inline_assets(main_html)

    if key == "reservations":
        # avoid duplicate ids with cotation.html (dyn-*, sum-*)
        main_html = re.sub(
            r'id="(dyn-(?:LCL|FCL|AIR|RORO)[^"]*)"',
            lambda m: 'id="b%s"' % m.group(1),
            main_html,
        )
        for n in ("mode", "from", "to", "date", "cargo"):
            main_html = main_html.replace('id="sum-%s"' % n, 'id="bsum-%s"' % n)

    sections.append(
        '  <section class="spa-page" data-page="%s" data-title-key="%s" data-title="%s" hidden>\n%s\n  </section>'
        % (key, title_key, html.escape(title_text, quote=True), main_html.strip("\n"))
    )

# ------------------------------------------------------------ shared chrome
index_raw = (ROOT / "index.html").read_text(encoding="utf-8")
header = extract(r'<header class="site-header">.*?</header>', index_raw)
footer = extract(r'<footer class="site-footer">.*?</footer>', index_raw)
cookie = extract(r'<aside class="cookie-banner"[\s\S]*?</aside>', index_raw)
toast = extract(r'(?m)^<div class="toast".*?^</div>$', index_raw)
backtop = extract(r'(?m)^<button class="back-top".*?^</button>$', index_raw)

skip = re.search(r'<a class="skip-link".*?</a>', header, flags=re.S)
if skip:
    header = header.replace(skip.group(0), "")
header = inline_assets(rewrite_links(header, "index"))
cookie = inline_assets(rewrite_links(cookie, "index"))
toast = inline_assets(rewrite_links(toast, "index"))
backtop = inline_assets(rewrite_links(backtop, "index"))
footer = inline_assets(rewrite_links(footer, "index"))
chrome = "\n".join([header, cookie, toast, backtop])

# ------------------------------------------------------------------- assets
css = (ROOT / "assets/css/main.css").read_text(encoding="utf-8")
css += """

/* ===== Standalone (single-file) build ===== */
.spa-page[hidden] { display: none !important; }
.spa-page [id] { scroll-margin-top: 96px; }
"""
icons_svg = (ROOT / "assets/img/icons.svg").read_text(encoding="utf-8")

translations_js = (ROOT / "assets/js/translations.js").read_text(encoding="utf-8")
i18n_js = (ROOT / "assets/js/i18n.js").read_text(encoding="utf-8")
main_js = (ROOT / "assets/js/main.js").read_text(encoding="utf-8")
quote_js = (ROOT / "assets/js/quote.js").read_text(encoding="utf-8")
contact_js = (ROOT / "assets/js/contact.js").read_text(encoding="utf-8")
booking_js = (ROOT / "assets/js/booking.js").read_text(encoding="utf-8")

# patch main.js: express quote widget -> hash navigation
main_js = main_js.replace('"cotation.html"', '"#cotation"')

# patch quote.js: also read the query string that lives after '#' (SPA routing)
old_params = "var params = new URLSearchParams(window.location.search);"
new_params = (
    "var params = new URLSearchParams((function () {"
    "var q = (window.location.search || '').replace(/^\\?/, '');"
    "var hq = (window.location.hash.split('?')[1] || '');"
    "return (q ? q + '&' : '') + hq;"
    "})());"
)
assert old_params in quote_js
quote_js = quote_js.replace(old_params, new_params)

# patch booking.js: namespaced ids of the reservations page (b prefix)
booking_js = booking_js.replace('"dyn-', '"bdyn-')
for n in ("mode", "from", "to", "date", "cargo"):
    booking_js = booking_js.replace('"sum-%s"' % n, '"bsum-%s"' % n)

router_js = r"""
/* ===== SPA router (standalone build) ===== */
(function () {
  "use strict";
  var sections = {};
  document.querySelectorAll(".spa-page").forEach(function (s) {
    sections[s.getAttribute("data-page")] = s;
  });

  function setTitle(sec) {
    var key = sec.getAttribute("data-title-key");
    var fallback = sec.getAttribute("data-title");
    document.title = key && window.t ? window.t(key, fallback) : fallback;
  }

  function show(page, anchor, instant) {
    Object.keys(sections).forEach(function (k) {
      sections[k].hidden = k !== page;
    });
    var sec = sections[page];
    setTitle(sec);
    requestAnimationFrame(function () {
      var target = anchor ? document.getElementById(anchor) : null;
      if (target && sec.contains(target)) {
        target.scrollIntoView({ behavior: instant ? "auto" : "smooth", block: "start" });
      } else {
        window.scrollTo({ top: 0, behavior: instant ? "auto" : "smooth" });
      }
    });
  }

  function route(instant) {
    var h = location.hash.slice(1) || "index";
    var anchor = null;
    var slash = h.indexOf("/");
    if (slash > -1) { anchor = h.slice(slash + 1) || null; h = h.slice(0, slash); }
    var q = h.indexOf("?");
    if (q > -1) h = h.slice(0, q);
    if (!sections[h]) { h = "index"; anchor = null; }
    show(h, anchor, instant);
  }

  window.addEventListener("hashchange", function () { route(false); });
  window.addEventListener("travex:lang", function () {
    var cur = document.querySelector(".spa-page:not([hidden])");
    if (cur) setTitle(cur);
  });
  route(true);
})();
"""

# ------------------------------------------------------------------- output
desc = re.search(r'<meta name="description"[^>]*>', index_raw).group(0)
favicon = re.search(r'<link rel="icon"[^>]*>', index_raw).group(0)

out = []
out.append("<!DOCTYPE html>")
out.append('<html lang="de">')
out.append("<head>")
out.append('  <meta charset="UTF-8">')
out.append('  <meta name="viewport" content="width=device-width, initial-scale=1.0">')
out.append("  <title>Travex Global Forwarding | Commissionnaire de transport international Hambourg – Europe–Afrique</title>")
out.append("  " + desc)
out.append('  <meta property="og:type" content="website">')
out.append("  " + favicon)
out.append('  <link rel="preconnect" href="https://fonts.googleapis.com">')
out.append('  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>')
out.append('  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">')
out.append("  <style>")
out.append(css)
out.append("  </style>")
out.append("</head>")
out.append("<body>")
out.append(chrome)  # header + cookie banner + toast + back-to-top
out.append("")
out.append('<div id="spaRoot">')
out.extend(sections)
out.append("</div>")
out.append("")
out.append(footer)
out.append("")
out.append(icons_svg)
out.append("")
for label, js in [
    ("translations", translations_js),
    ("i18n", i18n_js),
    ("main", main_js),
    ("quote", quote_js),
    ("contact", contact_js),
    ("booking", booking_js),
]:
    out.append("<script>/* ===== %s.js ===== */" % label)
    out.append(js.rstrip())
    out.append("</script>")
out.append("<script>")
out.append(router_js.rstrip())
out.append("</script>")
out.append("</body>")
out.append("</html>")

OUT.write_text("\n".join(out), encoding="utf-8")

# ------------------------------------------------------------------- report
ids = re.findall(r'id="([^"]+)"', OUT.read_text(encoding="utf-8"))
seen, dupes = set(), set()
for i in ids:
    if i in seen:
        dupes.add(i)
    seen.add(i)

size_kb = OUT.stat().st_size // 1024
print("OK  ->", OUT.name, "(%d KB)" % size_kb)
print("sections:", len(sections))
print("images inlined:", len(IMG_CACHE))
if dupes:
    print("DUPLICATE IDS:", sorted(dupes))
else:
    print("ids: all unique")
