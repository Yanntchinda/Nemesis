/* ==========================================================================
   MARINE + — Assistant de demande de cotation
   Parcours en 4 étapes, champs dynamiques selon le mode de transport,
   récapitulatif en direct, brouillon local, pré-remplissage par URL,
   envoi simulé + passerelle mailto.
   ========================================================================== */
(function () {
  "use strict";

  var form = document.getElementById("quoteForm");
  if (!form) return;

  var DRAFT_KEY = "mp-quote-draft";
  var panels = Array.prototype.slice.call(form.querySelectorAll(".wpanel"));
  var steps = Array.prototype.slice.call(document.querySelectorAll(".wstep"));
  var current = 0;

  var MODE_LABELS = {
    LCL: "Groupage maritime (LCL)",
    FCL: "Conteneur complet (FCL)",
    AIR: "Fret aérien",
    RORO: "Roulier (RO/RO)",
    PROJECT: "Project cargo / colis lourd"
  };

  /* ---------- Helpers ---------- */
  function val(name) {
    var el = form.querySelector('[name="' + name + '"]');
    return el ? el.value.trim() : "";
  }
  function selectedText(name) {
    var el = form.querySelector('[name="' + name + '"]');
    if (!el || el.tagName !== "SELECT") return "";
    return el.selectedIndex >= 0 ? el.options[el.selectedIndex].text : "";
  }
  function modeValue() {
    var checked = form.querySelector('input[name="mode"]:checked');
    return checked ? checked.value : "";
  }
  function isVisible(el) {
    return !!(el.offsetWidth || el.offsetHeight || el.getClientRects().length);
  }

  /* ---------- Champs dynamiques selon le mode ---------- */
  var DYN_GROUPS = ["FCL", "LCL", "AIR", "RORO", "PROJECT"];
  function syncDynamic() {
    var mode = modeValue();
    DYN_GROUPS.forEach(function (g) {
      /* Tous les sous-groupes du mode (ex : dyn-FCL et dyn-FCL-qty) */
      document.querySelectorAll('[id^="dyn-' + g + '"]').forEach(function (grp) {
        var show = g === mode;
        grp.hidden = !show;
        grp.querySelectorAll("input, select, textarea").forEach(function (inp) {
          if (show) {
            if (inp.hasAttribute("data-req")) inp.setAttribute("required", "");
          } else {
            inp.removeAttribute("required");
          }
        });
      });
    });
  }

  /* ---------- Récapitulatif en direct ---------- */
  function setSum(id, text) {
    var el = document.getElementById(id);
    if (!el) return;
    el.textContent = text || "—";
    el.classList.toggle("empty", !text);
  }
  function cargoSummary() {
    var parts = [];
    var nature = selectedText("goods");
    if (nature) parts.push(nature);
    var mode = modeValue();
    if (mode === "FCL") {
      if (val("container")) parts.push(val("container") + " × " + (val("containerQty") || "1"));
    } else if (mode === "LCL") {
      if (val("volume")) parts.push(val("volume") + " m³");
      if (val("packages")) parts.push(val("packages") + " colis");
    } else if (mode === "AIR") {
      if (val("airWeight")) parts.push(val("airWeight") + " kg");
    } else if (mode === "RORO") {
      if (selectedText("vehicle")) parts.push(selectedText("vehicle"));
    } else if (mode === "PROJECT") {
      if (val("projectDims")) parts.push(val("projectDims"));
      if (val("projectWeight")) parts.push(val("projectWeight") + " kg");
    }
    if (form.querySelector('[name="insurance"]') && form.querySelector('[name="insurance"]').checked) {
      parts.push("Assurance ad valorem");
    }
    return parts.join(" · ");
  }
  function updateSummary() {
    var from = selectedText("from");
    var to = selectedText("to");
    setSum("sum-route", (from || to) ? (from || "—") + "  →  " + (to || "—") : "");
    setSum("sum-mode", MODE_LABELS[modeValue()] || "");
    setSum("sum-incoterm", val("incoterm"));
    setSum("sum-cargo", cargoSummary());
    var d = val("date");
    setSum("sum-date", d ? new Date(d + "T12:00:00").toLocaleDateString("fr-FR", { day: "numeric", month: "long", year: "numeric" }) : "");
    var who = [val("firstname"), val("lastname")].filter(Boolean).join(" ");
    var co = val("company");
    setSum("sum-contact", [who, co].filter(Boolean).join(" — "));
  }

  /* ---------- Navigation entre étapes ---------- */
  function goTo(index) {
    current = Math.max(0, Math.min(panels.length - 1, index));
    panels.forEach(function (p, i) { p.classList.toggle("is-active", i === current); });
    steps.forEach(function (s, i) {
      s.classList.toggle("is-active", i === current);
      s.classList.toggle("is-done", i < current);
      var num = s.querySelector("i");
      if (num) num.textContent = i < current ? "✓" : String(i + 1);
    });
    var anchor = document.getElementById("wizardTop");
    if (anchor && window.scrollY > 200) {
      anchor.scrollIntoView({ behavior: "smooth", block: "start" });
    }
  }

  function validatePanel(panel) {
    var ok = true;
    var fields = panel.querySelectorAll("input[required], select[required], textarea[required]");
    Array.prototype.forEach.call(fields, function (inp) {
      if (!isVisible(inp)) return;
      var wrap = inp.closest(".field") || inp.parentElement;
      var bad = !inp.value.trim();
      if (!bad && inp.type === "email") {
        bad = !/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(inp.value.trim());
      }
      if (!bad && inp.getAttribute("data-type") === "phone") {
        bad = !/^[0-9 +().-]{6,}$/.test(inp.value.trim());
      }
      wrap.classList.toggle("invalid", bad);
      if (bad) ok = false;
    });
    if (!ok) {
      window.showToast("Champs incomplets", "Merci de compléter les champs signalés en rouge avant de continuer.");
    }
    return ok;
  }

  form.addEventListener("click", function (e) {
    var next = e.target.closest("[data-next]");
    var prev = e.target.closest("[data-prev]");
    if (next) {
      if (validatePanel(panels[current])) goTo(current + 1);
    }
    if (prev) goTo(current - 1);
  });

  /* Efface l'état d'erreur dès que l'utilisateur corrige */
  form.addEventListener("input", function (e) {
    var wrap = e.target.closest(".field");
    if (wrap) wrap.classList.remove("invalid");
  });

  /* ---------- Brouillon + pré-remplissage URL ---------- */
  function serialize() {
    var data = {};
    Array.prototype.forEach.call(form.elements, function (el) {
      if (!el.name) return;
      if (el.type === "radio") { if (el.checked) data[el.name] = el.value; }
      else if (el.type === "checkbox") { data[el.name] = el.checked; }
      else data[el.name] = el.value;
    });
    return data;
  }
  function restore(data) {
    Object.keys(data).forEach(function (name) {
      var els = form.querySelectorAll('[name="' + name + '"]');
      Array.prototype.forEach.call(els, function (el) {
        if (el.type === "radio") el.checked = el.value === data[name];
        else if (el.type === "checkbox") el.checked = !!data[name];
        else el.value = data[name];
      });
    });
  }
  (function init() {
    var draft = null;
    try { draft = JSON.parse(window.localStorage.getItem(DRAFT_KEY) || "null"); } catch (e) { draft = null; }
    if (draft) restore(draft);
    var params = new URLSearchParams(window.location.search);
    if (params.get("from")) { var f = form.querySelector('[name="from"]'); if (f) f.value = params.get("from"); }
    if (params.get("to")) { var t = form.querySelector('[name="to"]'); if (t) t.value = params.get("to"); }
    if (params.get("mode")) {
      var m = form.querySelector('input[name="mode"][value="' + params.get("mode") + '"]');
      if (m) m.checked = true;
    }
    syncDynamic();
    updateSummary();
    updateCounter();
    goTo(0);
  })();

  var saveTimer = null;
  form.addEventListener("input", function () {
    updateSummary();
    updateCounter();
    if (saveTimer) window.clearTimeout(saveTimer);
    saveTimer = window.setTimeout(function () {
      try { window.localStorage.setItem(DRAFT_KEY, JSON.stringify(serialize())); } catch (e) { /* ignore */ }
    }, 350);
  });
  form.addEventListener("change", function (e) {
    if (e.target.name === "mode") syncDynamic();
    updateSummary();
  });

  /* ---------- Compteur de caractères (remarques) ---------- */
  function updateCounter() {
    var ta = form.querySelector('[name="remarks"]');
    var counter = document.getElementById("remarksCounter");
    if (ta && counter) counter.textContent = ta.value.length + " / 300";
  }

  /* ---------- Envoi ---------- */
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    var consent = form.querySelector('[name="consent"]');
    if (!consent || !consent.checked) {
      var wrap = consent ? consent.closest(".field") : null;
      if (wrap) wrap.classList.add("invalid");
      window.showToast("Consentement requis", "Merci d'accepter le traitement de vos données pour recevoir votre cotation.");
      return;
    }
    for (var i = 0; i < panels.length - 1; i++) {
      if (!validatePanel(panels[i])) { goTo(i); return; }
    }
    var submitBtn = document.getElementById("quoteSubmit");
    submitBtn.disabled = true;
    submitBtn.textContent = "Envoi en cours…";

    window.setTimeout(function () {
      var ref = "MP-" + new Date().getFullYear() + "-" + String(Math.floor(1000 + Math.random() * 9000));
      var successRef = document.getElementById("successRef");
      if (successRef) successRef.textContent = ref;

      /* Passerelle mailto : le récapitulatif part dans le corps du message */
      var mailto = document.getElementById("mailtoLink");
      if (mailto) {
        var lines = [
          "Demande de cotation " + ref,
          "Trajet : " + (selectedText("from") || "—") + " -> " + (selectedText("to") || "—"),
          "Mode : " + (MODE_LABELS[modeValue()] || "—"),
          "Incoterm : " + (val("incoterm") || "—"),
          "Marchandise : " + (cargoSummary() || "—"),
          "Date souhaitée : " + (val("date") || "à convenir"),
          "Contact : " + [val("firstname"), val("lastname")].filter(Boolean).join(" ") + " — " + val("company"),
          "Email : " + val("email") + " | Tél : " + (val("phonePrefix") + " " + val("phone")),
          "Remarques : " + (val("remarks") || "—")
        ];
        mailto.href = "mailto:contact@marineplus.net?subject=" +
          encodeURIComponent("Demande de cotation " + ref) +
          "&body=" + encodeURIComponent(lines.join("\n"));
      }

      try { window.localStorage.removeItem(DRAFT_KEY); } catch (err) { /* ignore */ }
      form.hidden = true;
      var asideSummary = document.getElementById("quoteSummary");
      if (asideSummary) asideSummary.hidden = true;
      var success = document.getElementById("successPanel");
      if (success) success.classList.add("is-visible");
      window.showToast("Demande transmise", "Référence " + ref + " — notre pôle cotations vous répond sous 24 h ouvrées.");
    }, 900);
  });

  /* ---------- Nouvelle demande ---------- */
  var resetBtn = document.getElementById("newQuote");
  if (resetBtn) {
    resetBtn.addEventListener("click", function () {
      form.reset();
      form.hidden = false;
      var asideSummary = document.getElementById("quoteSummary");
      if (asideSummary) asideSummary.hidden = false;
      var success = document.getElementById("successPanel");
      if (success) success.classList.remove("is-visible");
      var submitBtn = document.getElementById("quoteSubmit");
      submitBtn.disabled = false;
      submitBtn.textContent = "Envoyer ma demande";
      syncDynamic();
      updateSummary();
      goTo(0);
    });
  }
})();
