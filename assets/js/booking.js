/* ==========================================================================
   MARINE + — Page Réservations
   Champs dynamiques par mode de transport, récapitulatif latéral en temps
   réel, validation, panneau de confirmation et référence booking.
   ========================================================================== */
(function () {
  "use strict";

  var form = document.getElementById("bookingForm");
  if (!form) return;

  var success = document.getElementById("bookingSuccess");
  var refEl = document.getElementById("bookingRef");
  var again = document.getElementById("bookingAgain");

  var MODE_LABELS = { LCL: "bk.mode.lcl", FCL: "bk.mode.fcl", AIR: "bk.mode.air", RORO: "bk.mode.roro" };
  var MODE_DE = {
    LCL: "Sammelgut LCL",
    FCL: "Vollcontainer FCL",
    AIR: "Luftfracht",
    RORO: "RoRo"
  };

  /* Groupes de champs affichés selon le mode choisi */
  var DYN_FIELDS = {
    LCL: ["dyn-LCL", "dyn-LCL-pkg"],
    FCL: ["dyn-FCL", "dyn-FCL-qty"],
    AIR: ["dyn-AIR"],
    RORO: ["dyn-RORO"]
  };

  /* ----- Date minimale : aujourd'hui ----- */
  var dateInput = document.getElementById("b-date");
  if (dateInput) {
    var now = new Date();
    var iso = now.getFullYear() + "-" +
      String(now.getMonth() + 1).padStart(2, "0") + "-" +
      String(now.getDate()).padStart(2, "0");
    dateInput.min = iso;
  }

  function getMode() {
    var checked = form.querySelector('input[name="mode"]:checked');
    return checked ? checked.value : "LCL";
  }

  /* ----- Affichage des champs dynamiques ----- */
  function applyMode() {
    var mode = getMode();
    Object.keys(DYN_FIELDS).forEach(function (m) {
      var visible = m === mode;
      DYN_FIELDS[m].forEach(function (id) {
        var wrap = document.getElementById(id);
        if (!wrap) return;
        wrap.hidden = !visible;
        /* Neutralise la validation native des champs masqués */
        wrap.querySelectorAll("input, select").forEach(function (el) {
          if (visible) {
            el.setAttribute("required", "");
          } else {
            el.removeAttribute("required");
            var field = el.closest(".field");
            if (field) field.classList.remove("invalid");
          }
        });
      });
    });
  }

  /* Champs obligatoires "métier" des groupes dynamiques */
  ["b-container", "b-containerQty", "b-airWeight", "b-vehicle"].forEach(function (id) {
    var el = document.getElementById(id);
    if (el) el.setAttribute("required", "");
  });
  applyMode();

  /* ----- Récapitulatif latéral ----- */
  var sumMode = document.getElementById("sum-mode");
  var sumFrom = document.getElementById("sum-from");
  var sumTo = document.getElementById("sum-to");
  var sumDate = document.getElementById("sum-date");
  var sumCargo = document.getElementById("sum-cargo");

  function setText(el, value) {
    if (!el) return;
    if (value) {
      el.textContent = value;
      el.classList.remove("empty");
    } else {
      el.classList.add("empty");
    }
  }

  function cargoSummary() {
    var mode = getMode();
    if (mode === "LCL") {
      var vol = document.getElementById("b-volume");
      var pkg = document.getElementById("b-packages");
      if (!vol || !vol.value) return "";
      var txt = vol.value + " m³";
      if (pkg && pkg.value) txt += " · " + pkg.value + " colis";
      return txt;
    }
    if (mode === "FCL") {
      var cont = document.getElementById("b-container");
      var qty = document.getElementById("b-containerQty");
      if (!cont || !cont.value) return "";
      return (qty && qty.value ? qty.value + " × " : "") +
        cont.options[cont.selectedIndex].text;
    }
    if (mode === "AIR") {
      var w = document.getElementById("b-airWeight");
      return w && w.value ? w.value + " kg" : "";
    }
    if (mode === "RORO") {
      var veh = document.getElementById("b-vehicle");
      return veh && veh.value ? veh.options[veh.selectedIndex].text : "";
    }
    return "";
  }

  function updateSummary() {
    if (sumMode) {
      var label = window.t(MODE_LABELS[getMode()], MODE_DE[getMode()]);
      setText(sumMode, label || "");
      if (!label) sumMode.classList.add("empty");
    }
    var from = document.getElementById("b-from");
    if (sumFrom && from) setText(sumFrom, from.options[from.selectedIndex].text);
    var to = document.getElementById("b-to");
    if (sumTo && to) {
      setText(sumTo, to.value ? to.options[to.selectedIndex].text : "");
      if (!to.value) sumTo.textContent = window.t("bk.sum.empty1", "À sélectionner");
    }
    var dateTxt = "";
    if (dateInput && dateInput.value) {
      var d = new Date(dateInput.value + "T12:00:00");
      dateTxt = d.toLocaleDateString("fr-FR", { day: "numeric", month: "long", year: "numeric" });
    }
    if (sumDate) {
      setText(sumDate, dateTxt);
      if (!dateTxt) sumDate.textContent = window.t("bk.sum.empty2", "À indiquer");
    }
    var cargo = cargoSummary();
    if (sumCargo) {
      setText(sumCargo, cargo);
      if (!cargo) sumCargo.textContent = window.t("bk.sum.empty3", "À détailler");
    }
  }

  form.addEventListener("change", function (e) {
    if (e.target.name === "mode") applyMode();
    updateSummary();
  });
  form.addEventListener("input", function (e) {
    var field = e.target.closest(".field");
    if (field) field.classList.remove("invalid");
    updateSummary();
  });
  updateSummary();

  /* ----- Validation & envoi ----- */
  function isBad(el) {
    if (el.type === "checkbox") return !el.checked;
    if (!el.value || !String(el.value).trim()) return true;
    if (el.type === "email") return !el.checkValidity();
    if (el.type === "date" && dateInput && dateInput.min && el.value < dateInput.min) return true;
    return false;
  }

  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var firstBad = null;
    var ok = true;

    /* Seuls les champs visibles sont contrôlés */
    form.querySelectorAll("input[required], select[required], textarea[required]").forEach(function (el) {
      if (el.hidden || (el.closest("[hidden]"))) return;
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
        firstBad.scrollIntoView({ behavior: "smooth", block: "center" });
        firstBad.focus({ preventScroll: true });
      }
      window.showToast(window.t("form.incompleteT", "Formulaire incomplet"), window.t("form.incompleteP", "Merci de corriger les champs signalés en rouge."));
      return;
    }

    /* Référence réservation type MP-2026-XXXXX */
    var ref = "MP-" + new Date().getFullYear() + "-" +
      Math.random().toString(36).slice(2, 7).toUpperCase();
    if (refEl) refEl.textContent = ref;

    form.hidden = true;
    if (success) {
      success.classList.add("is-visible");
      success.scrollIntoView({ behavior: "smooth", block: "center" });
    }
    window.showToast(window.t("bk.sentT", "Réservation enregistrée"), window.t("bk.sentP", "Réf. %s — confirmation d'espace sous 4 h ouvrées.").replace("%s", ref));
  });

  if (again) {
    again.addEventListener("click", function () {
      if (success) success.classList.remove("is-visible");
      form.reset();
      form.hidden = false;
      applyMode();
      updateSummary();
      form.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  }
})();
