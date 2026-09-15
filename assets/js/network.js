/* ==========================================================================
   TRAVEX GLOBAL FORWARDING — Annuaire du réseau (page Réseau & implantations)
   Filtre par zone + recherche plein texte sur les fiches (bureaux et agents).
   Aucun appel réseau : tout est traité dans le DOM.
   ========================================================================== */
(function () {
  "use strict";

  var tools = document.querySelector(".net-tools");
  var items = Array.prototype.slice.call(document.querySelectorAll("[data-net-item]"));
  if (!tools || !items.length) return;

  var search = tools.querySelector("[data-net-search]");
  var buttons = Array.prototype.slice.call(tools.querySelectorAll("[data-net-region]"));
  var countEl = tools.querySelector("[data-net-count]");
  var emptyEl = document.querySelector("[data-net-empty]");
  var region = "all";

  function norm(s) {
    return (s || "")
      .toLowerCase()
      .normalize("NFD")
      .replace(/[\u0300-\u036f]/g, "");
  }

  /* Index texte calculé une fois par élément */
  items.forEach(function (el) {
    el._netIndex = norm(el.textContent + " " + el.getAttribute("data-net-tags"));
  });

  function apply() {
    var q = norm(search ? search.value : "");
    var terms = q.split(/\s+/).filter(Boolean);
    var shown = 0;

    items.forEach(function (el) {
      var okRegion = region === "all" || el.getAttribute("data-net-region") === region;
      var okText = terms.every(function (t) { return el._netIndex.indexOf(t) > -1; });
      var show = okRegion && okText;
      el.classList.toggle("is-hidden", !show);
      if (show) shown++;
    });

    if (countEl) countEl.textContent = netLabel(shown);
    if (emptyEl) emptyEl.hidden = shown !== 0;
  }

  function netLabel(n) {
    var lang = window.travexLang ? window.travexLang() : "fr";
    var one = lang === "en" ? "location shown" : "implantation affichée";
    var many = lang === "en" ? "locations shown" : "implantations affichées";
    return n + " " + (n === 1 ? one : many);
  }

  buttons.forEach(function (btn) {
    btn.addEventListener("click", function () {
      region = btn.getAttribute("data-net-region") || "all";
      buttons.forEach(function (b) {
        var on = b === btn;
        b.classList.toggle("is-active", on);
        b.setAttribute("aria-pressed", String(on));
      });
      apply();
    });
  });

  if (search) {
    search.addEventListener("input", apply);
    search.addEventListener("search", apply);
  }

  window.addEventListener("travex:lang", apply);
  apply();
})();
