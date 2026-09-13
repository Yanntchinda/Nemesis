/* ==========================================================================
   MARINE + — Interactions globales
   Header, menu mobile, progression, révélations, compteurs, accordéons,
   scrollspy de sous-navigation, cookies, toast, cotation express.
   ========================================================================== */
(function () {
  "use strict";

  /* ----- Année dynamique ----- */
  document.querySelectorAll("[data-year]").forEach(function (el) {
    el.textContent = new Date().getFullYear();
  });

  /* ----- Header : ombre + barre de progression + back-to-top ----- */
  var header = document.querySelector(".site-header");
  var progress = document.getElementById("scrollProgress");
  var backTop = document.getElementById("backTop");

  function onScroll() {
    var doc = document.documentElement;
    var y = window.scrollY || doc.scrollTop;
    if (header) header.classList.toggle("is-scrolled", y > 8);
    if (progress) {
      var max = doc.scrollHeight - doc.clientHeight;
      progress.style.transform = "scaleX(" + (max > 0 ? y / max : 0) + ")";
    }
    if (backTop) backTop.classList.toggle("show", y > 640);
  }
  document.addEventListener("scroll", onScroll, { passive: true });
  onScroll();

  if (backTop) {
    backTop.addEventListener("click", function () {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });
  }

  /* ----- Menu mobile ----- */
  var navToggle = document.getElementById("navToggle");
  var mainNav = document.getElementById("mainNav");
  if (navToggle && mainNav) {
    var setNav = function (open) {
      mainNav.classList.toggle("is-open", open);
      navToggle.setAttribute("aria-expanded", String(open));
    };
    navToggle.addEventListener("click", function () {
      setNav(!mainNav.classList.contains("is-open"));
    });
    document.addEventListener("click", function (e) {
      if (mainNav.classList.contains("is-open") && !mainNav.contains(e.target) && !navToggle.contains(e.target)) {
        setNav(false);
      }
    });
    document.addEventListener("keydown", function (e) {
      if (e.key === "Escape" && mainNav.classList.contains("is-open")) {
        setNav(false);
        navToggle.focus();
      }
    });
    mainNav.querySelectorAll("a").forEach(function (a) {
      a.addEventListener("click", function () { setNav(false); });
    });
  }

  /* ----- Révélation au scroll ----- */
  var revealEls = document.querySelectorAll("[data-reveal]");
  if ("IntersectionObserver" in window && revealEls.length) {
    var io = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          entry.target.classList.add("is-in");
          io.unobserve(entry.target);
        }
      });
    }, { threshold: 0.12, rootMargin: "0px 0px -40px 0px" });
    revealEls.forEach(function (el) { io.observe(el); });
  } else {
    revealEls.forEach(function (el) { el.classList.add("is-in"); });
  }

  /* ----- Compteurs animés ----- */
  var counters = document.querySelectorAll("[data-count]");
  function animateCounter(el) {
    /* Affichage direct de la valeur finale, sans animation */
    var target = parseFloat(el.getAttribute("data-count")) || 0;
    var suffix = el.getAttribute("data-suffix") || "";
    el.textContent = target.toLocaleString("fr-FR") + suffix;
  }
  if ("IntersectionObserver" in window && counters.length) {
    var cio = new IntersectionObserver(function (entries) {
      entries.forEach(function (entry) {
        if (entry.isIntersecting) {
          cio.unobserve(entry.target);
          animateCounter(entry.target);
        }
      });
    }, { threshold: 0.5 });
    counters.forEach(function (el) { cio.observe(el); });
  } else {
    counters.forEach(function (el) {
      el.textContent = (parseFloat(el.getAttribute("data-count")) || 0).toLocaleString("fr-FR") + (el.getAttribute("data-suffix") || "");
    });
  }

  /* ----- Accordéons ----- */
  document.querySelectorAll(".acc").forEach(function (acc) {
    var btn = acc.querySelector(".acc__btn");
    var panel = acc.querySelector(".acc__panel");
    if (!btn || !panel) return;
    btn.addEventListener("click", function () {
      var open = !acc.classList.contains("is-open");
      /* Ferme les frères (comportement accordéon strict) */
      var list = acc.parentElement;
      if (list) {
        list.querySelectorAll(".acc.is-open").forEach(function (other) {
          if (other !== acc) {
            other.classList.remove("is-open");
            other.querySelector(".acc__btn").setAttribute("aria-expanded", "false");
            other.querySelector(".acc__panel").style.maxHeight = "0px";
          }
        });
      }
      acc.classList.toggle("is-open", open);
      btn.setAttribute("aria-expanded", String(open));
      panel.style.maxHeight = open ? panel.scrollHeight + "px" : "0px";
    });
  });

  /* ----- Scrollspy de la sous-navigation (page Services) ----- */
  var subnav = document.querySelector(".subnav");
  if (subnav) {
    var links = Array.prototype.slice.call(subnav.querySelectorAll("a[href^='#']"));
    var targets = links
      .map(function (a) { return document.getElementById(a.getAttribute("href").slice(1)); })
      .filter(Boolean);
    if (targets.length && "IntersectionObserver" in window) {
      var spy = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            links.forEach(function (a) {
              a.classList.toggle("is-active", a.getAttribute("href") === "#" + entry.target.id);
            });
          }
        });
      }, { rootMargin: "-40% 0px -55% 0px" });
      targets.forEach(function (t) { spy.observe(t); });
    }
  }

  /* ----- Toast ----- */
  var toastTimer = null;
  window.showToast = function (title, message) {
    var toast = document.getElementById("toast");
    if (!toast) return;
    toast.querySelector(".toast__title").textContent = title;
    toast.querySelector(".toast__msg").textContent = message;
    toast.classList.add("is-visible");
    if (toastTimer) window.clearTimeout(toastTimer);
    toastTimer = window.setTimeout(function () {
      toast.classList.remove("is-visible");
    }, 4600);
  };

  /* ----- Bandeau cookies (RGPD) ----- */
  var COOKIE_KEY = "mp-cookie-consent";
  var cookieBanner = document.getElementById("cookieBanner");
  function hideCookies() { if (cookieBanner) cookieBanner.classList.remove("is-visible"); }
  if (cookieBanner) {
    var stored = null;
    try { stored = window.localStorage.getItem(COOKIE_KEY); } catch (e) { /* stockage indisponible */ }
    if (!stored) {
      window.setTimeout(function () { cookieBanner.classList.add("is-visible"); }, 900);
    }
    cookieBanner.addEventListener("click", function (e) {
      var action = e.target.closest("[data-cookie-choice]");
      if (!action) return;
      var choice = action.getAttribute("data-cookie-choice");
      try { window.localStorage.setItem(COOKIE_KEY, choice); } catch (err) { /* ignore */ }
      hideCookies();
      if (choice === "all") {
        window.showToast(window.t("cookie.toastAllT", "Cookies acceptés"), window.t("cookie.toastAllP", "Vos préférences ont été enregistrées. Merci !"));
      } else if (choice === "none") {
        window.showToast(window.t("cookie.toastNoneT", "Cookies refusés"), window.t("cookie.toastNoneP", "Seuls les cookies strictement nécessaires restent actifs."));
      } else {
        window.showToast(window.t("cookie.toastCustomT", "Personnalisation"), window.t("cookie.toastCustomP", "Aucun traceur tiers n'est chargé sur ce site : seuls des cookies techniques sont utilisés."));
      }
    });
  }

  /* ----- Cotation express (widget héro) ----- */
  var quick = document.getElementById("quickQuote");
  if (quick) {
    quick.addEventListener("submit", function (e) {
      e.preventDefault();
      var from = quick.querySelector('[name="from"]').value;
      var to = quick.querySelector('[name="to"]').value;
      var mode = quick.querySelector('[name="mode"]').value;
      var params = new URLSearchParams();
      if (from) params.set("from", from);
      if (to) params.set("to", to);
      if (mode) params.set("mode", mode);
      window.location.href = "cotation.html" + (params.toString() ? "?" + params.toString() : "");
    });
  }

  /* ----- Newsletter footer ----- */
  var news = document.getElementById("newsletterForm");
  if (news) {
    news.addEventListener("submit", function (e) {
      e.preventDefault();
      var email = news.querySelector('input[type="email"]');
      if (!email.value || email.value.indexOf("@") < 0) {
        window.showToast(window.t("foot.newsErrT", "Adresse invalide"), window.t("foot.newsErrP", "Merci de saisir une adresse e-mail valide."));
        email.focus();
        return;
      }
      news.reset();
      window.showToast(window.t("foot.newsOkT", "Inscription confirmée"), window.t("foot.newsOkP", "Vous recevrez notre veille fret Europe–Afrique chaque mois."));
    });
  }
})();
