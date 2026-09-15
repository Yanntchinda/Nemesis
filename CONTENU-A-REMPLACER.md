# Contenu provisoire — checklist avant mise en ligne

La **structure** du site (pages, blocs, formulaires, navigation, filtre du réseau)
est définitive. Tout ce qui suit est un **gabarit** : textes, coordonnées et
chiffres inventés pour remplir la maquette, à remplacer par vos données réelles.

Repérage rapide dans le code : chaque page porte une ligne
`CONTENU PROVISOIRE` en commentaire, juste après le `<!DOCTYPE html>`.

## 1. Identité et marque

| Élément | Valeur actuelle (fictive) | Où la changer |
| --- | --- | --- |
| Nom commercial | Travex Global Forwarding | mot-clé `Travex` dans le `brand__wordmark` de chaque page (en-tête + pied de page) |
| Logo | `assets/img/logo-travex.svg`, `assets/img/logo-travex-white.svg` | remplacer les fichiers (même nom, format SVG) |
| Favicon | SVG « T » encodé en `data:` dans le `<head>` | 1 ligne par page (attribut `href` de `<link rel="icon">`) |
| Marque typographique | Sora + Manrope (Google Fonts) | `assets/css/main.css`, variables `--font-display` / `--font-body` |
| Palette | Bleu profond `#1A2B4C`, slate `#4A5568`, gris brume `#F0F4F8` | variables `:root` en tête de `assets/css/main.css` |

## 2. Coordonnées

- Téléphone unique affiché partout : **+49 176 50618495** (48 occurrences : en-têtes de contact, boutons d'appel, `tel:` du pied de page, JSON-LD de l'accueil).
- Adresses e-mail : `info@`, `hamburg@`, `bremen@`, `lehavre@`, `antwerp@`, `rotterdam@`, `air@` **@travex-global-forwarding.de**.
- Adresse de destination des formulaires (cotation, réservation, contact) : `assets/js/quote.js`, `booking.js`, `contact.js` — recherchez `mailto:`.
- Adresses postales des six bureaux (page `reseau.html`) : Hambourg, Brême, Le Havre, Anvers, Rotterdam, Francfort.
- Horaires, cut-off documentaires et permanences : section « Horaires, fuseaux et permanences » de `reseau.html`.
- Réseaux sociaux : liens `href="#"` dans le pied de page (LinkedIn, Facebook, X).

## 3. Chiffres et affirmations à vérifier

Aucun de ces éléments n'est vérifié : à remplacer par vos chiffres réels ou à supprimer.

- Entrepôt de **4 500 m²** à Hambourg (accueil, services, à propos).
- **6 bureaux en Europe**, **18 escales africaines**, mention « 3 ans d'audit minimum », « agent remplacé en dessous de 9 appels sur 10 décrochés ».
- Délais maritimes : 12–14 j (Dakar), 14–16 j (Abidjan), 18–21 j (Douala), 4–6 j (Tanger Med), jusqu'à 24–30 j (Matadi/Kinshasa) ; aérien 24–72 h.
- **Cotation sous 24 h ouvrées**, **48–72 h** pour un dossier project cargo, « réponse sous 10 jours » pour l'ouverture d'un agent.
- Dates et faits de l'histoire de l'entreprise (timeline de `a-propos.html`, « fondée à Hambourg », « desk aérien ouvert en … »).
- Agréments affichés en pied de page : **OEA**, **IATA**, **ADSp** — à ne conserver que s'ils sont réellement détenus.
- Conservation des données **24 mois** (texte de consentement) — à aligner sur votre registre de traitement.

## 4. Pages et blocs concernés

| Page | Ce qui est fictif |
| --- | --- |
| `index.html` | accroche, badge « entreprise de logistique », liste des lignes régulières, cotation express (villes proposés) |
| `services.html` | 9 fiches métiers : capacités, puces techniques, FAQ |
| `a-propos.html` | histoire, timeline, valeurs, carte du réseau, engagements |
| `reseau.html` | 6 fiches de bureaux (adresses, téléphones, e-mails), 18 lignes d'agents, tableau des horaires |
| `reservations.html` | grille des prochains départs (navires, dates, échéances) |
| `cotation.html` | listes d'origine/destination, barème indicatif, étapes de l'assistant |
| `contact.html` | coordonnées, horaires, questions fréquentes pré-remplies |
| `mentions-legales.html` | **tout** : éditeur, capital, numéro de registre, TVA intracommunautaire, hébergeur, responsable de publication, extraits de CGV |

## 5. À brancher avant publication

1. **Envoi des formulaires** : aujourd'hui simulé côté navigateur (panneau de confirmation + lien `mailto:` pré-rempli). Prévoir un point d'envoi réel (API, CRM ou service de formulaire) dans `assets/js/quote.js`, `booking.js`, `contact.js`.
2. **Mentions légales** : saisir les informations de votre entité juridique ; le lien CGV/ADSp pointe vers `mentions-legales.html#cgv`.
3. **RGPD / cookies** : le bandeau (`assets/js/main.js`) n'installe aucun traceur tiers ; si vous ajoutez une mesure d'audience, déclarez-la dans `mentions-legales.html#cookies`.
4. **SEO** : `sitemap.xml` et `robots.txt` ne sont pas fournis ; les balises `title`/`description`/`og:*` existent déjà et sont traduites (FR dans le HTML, EN dans `assets/js/translations.js`).
5. **Images** : les visuels de `assets/img/` sont des illustrations de remplacement, sans droit attaché. Pour vos photos : JPEG 1 600 px de large, ~200 Ko, noms inchangés.

## 6. Workflow de modification

```bash
python3 tools/check_site.py        # balises, clés i18n, liens internes, ids
python3 tools/build_standalone.py  # régénère travex-global-forwarding.html
python3 tools/preview_server.py    # http://localhost:8080
```

`check_site.py` signale toute clé `data-i18n*` sans traduction anglaise : si vous
écrivez un nouveau bloc, ajoutez la clé dans le dictionnaire `en` de
`assets/js/translations.js` (le français, lui, vit directement dans le HTML).
