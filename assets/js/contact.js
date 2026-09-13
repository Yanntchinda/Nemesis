/* ==========================================================================
   MARINE + — Page Contact
   Validation du formulaire de contact, panneau de confirmation, référence
   dossier et toast de confirmation.
   ========================================================================== */
(function () {
  "use strict";

  var form = document.getElementById("contactForm");
  if (!form) return;

  var success = document.getElementById("contactSuccess");
  var refEl = document.getElementById("contactRef");
  var again = document.getElementById("contactAgain");

  /* Nettoie l'état d'erreur dès que l'utilisateur corrige un champ */
  form.addEventListener("input", function (e) {
    var field = e.target.closest(".field");
    if (field) field.classList.remove("invalid");
  });

  function isBad(el) {
    if (el.type === "checkbox") return !el.checked;
    if (!el.value || !String(el.value).trim()) return true;
    if (el.type === "email") return !el.checkValidity();
    if (el.id === "c-message") return String(el.value).trim().length < 10;
    return false;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var firstBad = null;
    var ok = true;

    form.querySelectorAll("input[required], select[required], textarea[required]").forEach(function (el) {
      var bad = isBad(el);
      var field = el.closest(".field");
      if (field) field.classList.toggle("invalid", bad);
      if (bad) {
        ok = false;
        if (!firstBad) firstBad = el;
      }
    });

    if (!ok) {
      if (firstBad) {
        firstBad.focus();
        firstBad.scrollIntoView({ behavior: "smooth", block: "center" });
      }
      window.showToast(window.t("form.incompleteT", "Formular unvollständig"), window.t("form.incompleteP", "Bitte die rot markierten Felder korrigieren."));
      return;
    }

    /* Référence dossier type MP-2026-XXXXX */
    var ref = "MP-" + new Date().getFullYear() + "-" +
      Math.random().toString(36).slice(2, 7).toUpperCase();
    if (refEl) refEl.textContent = ref;

    form.hidden = true;
    if (success) {
      success.classList.add("is-visible");
      success.scrollIntoView({ behavior: "smooth", block: "center" });
    }
    window.showToast(window.t("ct.sentT", "Nachricht gesendet"), window.t("ct.sentP", "Zeichen %s — Antwort in 24 Stunden.").replace("%s", ref));
  });

  if (again) {
    again.addEventListener("click", function () {
      if (success) success.classList.remove("is-visible");
      form.reset();
      form.hidden = false;
      form.scrollIntoView({ behavior: "smooth", block: "start" });
      var first = form.querySelector("input, select, textarea");
      if (first) first.focus({ preventScroll: true });
    });
  }
})();
