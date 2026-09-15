# Travex Global Forwarding — site vitrine

Site statique (HTML/CSS/JS, sans framework ni build) pour une entreprise de
commissionnement de transport international : fret maritime, aérien, routier,
groupage LCL, logistique, project cargo, douane et représentation fiscale.

## Contenu du dépôt

| Fichier | Rôle |
| --- | --- |
| `index.html` | Accueil : en-tête slider, énumération des neuf services, lignes régulières, appel à la cotation |
| `services.html` | Une fiche détaillée par métier + FAQ + grille de transits |
| `a-propos.html` | Histoire, valeurs, timeline, carte du réseau |
| `reseau.html` | Annuaire filtrable : 6 bureaux en Europe, 18 escales africaines, horaires et permanences |
| `reservations.html` | Formulaire multi-étapes de demande d'espace |
| `cotation.html` | Assistant de cotation (4 étapes, récapitulatif, e-mail de synthèse) |
| `contact.html` | Formulaire de contact et coordonnées des bureaux |
| `mentions-legales.html` | Mentions légales, confidentialité, cookies, CGV (ADSp) |
| `travex_global_forwarding_spedition_international.html` | Page d'atterrissage (campagne) |
| `travex-global-forwarding.html` | **Livrable autonome** : les 9 pages, le CSS, le JS et les images dans un seul fichier |

## Assets partagés

- `assets/css/main.css` — design system (palette marine/slate, Sora + Manrope, composants)
- `assets/js/main.js` — en-tête, menu mobile, révélations, accordéons, scrollspy, cookies, toast, slider
- `assets/js/network.js` — recherche + filtres par zone de l'annuaire du réseau
- `assets/js/quote.js`, `booking.js`, `contact.js` — logique des formulaires
- `assets/js/translations.js` + `i18n.js` — bascule FR ⇄ EN (le français vit dans le HTML, l'anglais dans le dictionnaire)

## Avant publication

`CONTENU-A-REMPLACER.md` liste, catégorie par catégorie, les textes, coordonnées et
chiffres fictifs à remplacer. Chaque page porte le même rappel en commentaire,
juste après son `<!DOCTYPE html>`.

## Commandes

```bash
python3 tools/preview_server.py   # sert le site sur http://0.0.0.0:8080, sans cache
python3 tools/build_standalone.py # régénère travex-global-forwarding.html
python3 tools/check_site.py       # balises, clés i18n, liens internes, ids dupliqués
```

`tools/check_site.py` sort avec le code 1 dès qu'une anomalie est détectée : à
lancer après toute modification de page ou de clé de traduction.

## Choix éditoriaux

Le contenu (textes, coordonnées, chiffres, visuels) est fictif et propre au
projet : il sert de gabarit éditable. Les affirmations commerciales restent
vérifiables et sourcables — pas de promesse chiffrée non tenue.
