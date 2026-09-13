/* ==========================================================================
   TRAVEX GLOBAL FORWARDING — Moteur i18n
   Le français (fr) est la langue principale : son contenu vit dans le HTML et
   est capturé au chargement. L'anglais (en) provient de translations.js
   (window.I18N). Choix persisté en localStorage.
   ========================================================================== */
(function () {
  "use strict";

  var STORE_KEY = "travex-lang";
  var deText = {};   /* clé -> innerHTML français capturé */
  var deAttr = {};   /* "p:clé" / "a:clé" / "c:clé" -> valeur française */

  function each(sel, fn) {
    document.querySelectorAll(sel).forEach(fn);
  }

  function capture() {
    each("[data-i18n]", function (el) {
      deText[el.getAttribute("data-i18n")] = el.innerHTML;
    });
    each("[data-i18n-placeholder]", function (el) {
      deAttr["p:" + el.getAttribute("data-i18n-placeholder")] = el.getAttribute("placeholder") || "";
    });
    each("[data-i18n-aria]", function (el) {
      deAttr["a:" + el.getAttribute("data-i18n-aria")] = el.getAttribute("aria-label") || "";
    });
    each("[data-i18n-content]", function (el) {
      deAttr["c:" + el.getAttribute("data-i18n-content")] = el.getAttribute("content") || "";
    });
  }

  function currentLang() {
    try { return localStorage.getItem(STORE_KEY) || "fr"; } catch (e) { return "fr"; }
  }
  window.travexLang = currentLang;

  /* Traduction utilisable par les scripts de page (toasts, messages…) */
  window.t = function (key, fallback) {
    var lang = currentLang();
    var dict = (window.I18N && window.I18N[lang]) || {};
    if (lang !== "fr" && dict[key] !== undefined) return dict[key];
    if (deText[key] !== undefined) return deText[key];
    return fallback !== undefined ? fallback : key;
  };

  function apply(lang) {
    var dict = (window.I18N && window.I18N[lang]) || {};
    function pick(map, k) {
      if (lang === "fr") return map[k];
      return dict[k] !== undefined ? dict[k] : map[k];
    }
    each("[data-i18n]", function (el) {
      var v = pick(deText, el.getAttribute("data-i18n"));
      if (v !== undefined) el.innerHTML = v;
    });
    each("[data-i18n-placeholder]", function (el) {
      var v = pick(deAttr, "p:" + el.getAttribute("data-i18n-placeholder"));
      if (v !== undefined) el.setAttribute("placeholder", v);
    });
    each("[data-i18n-aria]", function (el) {
      var v = pick(deAttr, "a:" + el.getAttribute("data-i18n-aria"));
      if (v !== undefined) el.setAttribute("aria-label", v);
    });
    each("[data-i18n-content]", function (el) {
      var v = pick(deAttr, "c:" + el.getAttribute("data-i18n-content"));
      if (v !== undefined) el.setAttribute("content", v);
    });
    document.documentElement.setAttribute("lang", lang);
    each(".lang-switch button", function (b) {
      var on = b.getAttribute("data-lang") === lang;
      b.classList.toggle("is-active", on);
      b.setAttribute("aria-pressed", String(on));
    });
    try { localStorage.setItem(STORE_KEY, lang); } catch (e) { /* stockage indisponible */ }
    window.dispatchEvent(new CustomEvent("travex:lang", { detail: { lang: lang } }));
  }
  window.travexApplyLang = apply;

  document.addEventListener("DOMContentLoaded", function () {
    capture();
    each(".lang-switch button", function (b) {
      b.addEventListener("click", function () { apply(b.getAttribute("data-lang")); });
    });
    apply(currentLang());
  });
})();
