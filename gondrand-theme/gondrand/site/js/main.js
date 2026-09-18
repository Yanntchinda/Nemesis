/* TRAVEX GLOBAL FORWARDING France site logic */
(function () {
  const BASE = window.BASE || "";
  const PAGE = window.PAGE || "";
  const LANG = localStorage.getItem("g-lang") === "en" ? "en" : "fr";

  const I18N = {
    fr: {
      nav_home: "Accueil", nav_company: "Entreprise", nav_services: "Services",
      nav_quote: "Devis", nav_contact: "Contact", nav_jobs: "Espace emploi",
      nav_locations: "Emplacements", nav_rfq: "Demande de cotation",
      svc_road: "Transport terrestre", svc_air: "Fret aérien", svc_sea: "Fret maritime",
      svc_special: "Trafics spéciaux", svc_customs: "Douane",
      svc_vat: "TVA / Représentation fiscale", svc_vat_short: "TVA/DOUANES",
      read_more: "Lire la suite →", read_more_colon: "Lire la suite : →",
      slide_road: "Nous traitons votre transport avec le professionnalisme et la minutie que vous pouvez attendre.",
      slide_air: "Avec nos solutions d'expédition de fret aérien, nous fournissons la rapidité et la fiabilité dont vous avez besoin pour votre expédition de fret aérien.",
      slide_sea: "Quels que soient votre origine ou votre destination, la taille de votre cargaison ou sa complexité, nos normes de qualité mondiales et nos systèmes éprouvés garantissent un service sûr et fiable pour une livraison à temps.",
      slide_special: "L'efficacité et le haut niveau sont la clé de votre succès. Nous avons l'expérience nécessaire pour gérer vos besoins en logistique et en transport d'un endroit à un autre. Peu importe quelle taille.",
      slide_customs: "Les règles applicables aux douanes et au commerce extérieur sont en augmentation constante. En nous conformant aux lois et réglementations européennes, nous pouvons vous offrir une valeur ajoutée positive.",
      slide_vat: "Vous êtes un entrepreneur étranger sans numéro de TVA belge et vous réalisez néanmoins un bénéfice substantiel en Belgique ? Notre service de représentation fiscale vous aide à vous conformer très facilement à la réglementation en matière de TVA.",
      about_sub: "Logistique depuis 1866",
      about_p1: "Le service qui nous est offert va bien au-delà de la gestion courante des commandes de logistique et de transport. Nous donnons une touche personnelle à tout ce que nous faisons grâce à notre personnel, qui soutient ce service sur mesure. Ils s’adaptent à vos besoins et non l’inverse.",
      about_p2: "Nous voulons apprendre à vous connaître – et vous devriez également nous connaître personnellement. Nous avons l’intention de créer une relation de confiance à long terme avec vous, comme nous le faisons avec tous nos clients depuis des années, voire des décennies.",
      loc_title: "TRAVEX GLOBAL FORWARDING EMPLACEMENTS",
      loc_lead: "Un réseau d’agences de métropole, d’outre-mer et de frontière suisse. Sélectionnez une lettre.",
      svc_specials: "Services spéciaux",
      svc_specials_lead: "En tant que membre d’un réseau d’investisseurs internationaux, nous disposons des ressources financières et logistiques nécessaires à la définition et à la réalisation des objectifs de nos clients, tout en les accompagnant tout au long du processus.",
      about_us: "About us",
      card_about: "Le service qui nous est offert va bien au-delà de la gestion courante des commandes de logistique et de transport.",
      card_road: "Une organisation qui vous propose des services de porte à porte sur tout le territoire de l’Ancien Monde.",
      card_air: "Notre équipe de fret aérien vous rassure en sachant que vos marchandises sont entre de bonnes mains.",
      card_sea: "Nos professionnels du fret maritime tirent parti de leur vaste expérience pour gérer les flux de marchandises.",
      card_special: "L’activité inhérente au transport exceptionnel est adossée à un cabinet spécifique pour l’étude et la mise.",
      card_customs: "Ainsi, vos marchandises sont placées sous un régime suspensif de taxes, jusqu’à la destination finale que vous aurez choisie.",
      all_locations: "Toutes les agences",
      group: "NOTRE GROUPE D'ENTREPRISES",
      downloads: "Téléchargements",
      terms: "Conditions générales",
      containers: "Dimensions conteneurs",
      ges: "Bilan émissions GES",
      airlines: "Codes compagnies aériennes",
      vat_ecom: "TVA & E-Commerce",
      customs_form: "Douanes & formalités",
      network: "Réseau Travex Global Forwarding",
      hq: "Siège",
      legal: "Mentions légales",
      privacy: "Politique de confidentialité",
      rights: "© 2026 Travex Global Forwarding • Tous droits réservés.",
      since: "Logistique depuis 1866",
      cookie: "Nous utilisons des cookies et d'autres technologies sur notre site. Certains sont essentiels, d'autres nous aident à améliorer votre expérience. Les services Google (Maps, YouTube) ne sont chargés qu'après consentement. Plus d'informations dans notre politique de confidentialité.",
      accept: "Accepter",
      essential: "Cookies essentiels",
      quote_title: "Demander un devis gratuit",
      transport_type: "Type de transport",
      transport_type_ph: "Type de Transport",
      warehousing: "Entreposage",
      air_t: "Transport aérien",
      sea_t: "Transport maritime",
      multi_t: "Transport multimodal",
      road_t: "Transport terrestre",
      incoterms: "Conditions de vente",
      from_city: "Ville de départ",
      to_city: "Ville d'arrivée",
      weight: "Poids (kg)",
      email: "E-mail",
      privacy_ok: "Politique de confidentialité acceptée.",
      quote_ext: "Pour une version étendue du formulaire, cliquez ici.",
      click_here: "cliquez ici",
      send: "Envoyer →",
      quote_ok: "Votre demande a bien été transmise. Un commercial Travex Global Forwarding vous répondra dans les plus brefs délais.",
      special_title: "Qu'est-ce qui nous rend spécial ?",
      special_lead: "En tant que société européenne présente dans le monde entier, nous proposons à nos clients un portefeuille de services complet.",
      pack: "Emballage et stockage", pack_p: "Conditionnement et protection de vos marchandises.",
      ware: "Service d'entreposage", ware_p: "Plateformes sécurisées en France et à l'international.",
      road_f: "Transport terrestre", road_p: "Groupage et lots complets sur l'Ancien Monde.",
      logi: "Services logistiques", logi_p: "Pilotage de flux de bout en bout.",
      tel: "Tél.",
      belgium: "Belgique", china: "Chine", czech: "Tchéquie", germany_ngl: "Allemagne (NGL)",
      germany_mon: "Allemagne (Monnard)", lux: "Luxembourg", mexico: "Mexique", morocco: "Maroc",
      nl: "Pays-Bas", nc: "Nouvelle-Calédonie", senegal: "Sénégal", spain: "Espagne",
      ch: "Suisse", tahiti: "Tahiti", turkey: "Turquie", uk: "Royaume-Uni",
      home_crumb: "Accueil"
    },
    en: {
      nav_home: "Home", nav_company: "Company", nav_services: "Services",
      nav_quote: "Quote", nav_contact: "Contact", nav_jobs: "Careers",
      nav_locations: "Locations", nav_rfq: "Request a quote",
      svc_road: "Road freight", svc_air: "Air freight", svc_sea: "Sea freight",
      svc_special: "Special cargo", svc_customs: "Customs",
      svc_vat: "VAT / Tax representation", svc_vat_short: "VAT/CUSTOMS",
      read_more: "Read more →", read_more_colon: "Read more: →",
      slide_road: "We handle your transport with the professionalism and care you can expect.",
      slide_air: "With our air freight shipping solutions, we provide the speed and reliability you need for your air cargo.",
      slide_sea: "Whatever your origin or destination, cargo size or complexity, our global quality standards and proven systems ensure a safe, reliable, on-time service.",
      slide_special: "Efficiency and high standards are the key to your success. We have the experience to manage your logistics and transport needs from one place to another — whatever the size.",
      slide_customs: "Customs and foreign-trade rules keep growing. By complying with European laws and regulations, we deliver real added value.",
      slide_vat: "Are you a foreign entrepreneur without a Belgian VAT number, yet generating substantial activity in Belgium? Our tax representation service helps you comply easily with VAT regulations.",
      about_sub: "Logistics since 1866",
      about_p1: "The service we provide goes far beyond routine logistics and transport order handling. We give a personal touch to everything we do, thanks to our people who stand behind this tailored service. They adapt to your needs — not the other way around.",
      about_p2: "We want to get to know you — and you should get to know us personally. We intend to build a long-term relationship of trust with you, as we have done with our clients for years, even decades.",
      loc_title: "TRAVEX GLOBAL FORWARDING LOCATIONS",
      loc_lead: "A network of agencies in mainland France, overseas territories and on the Swiss border. Select a letter.",
      svc_specials: "Special services",
      svc_specials_lead: "As a member of an international investor network, we have the financial and logistics resources to define and achieve our clients’ goals, supporting them throughout the process.",
      about_us: "About us",
      card_about: "The service we provide goes far beyond routine logistics and transport order handling.",
      card_road: "An organisation offering door-to-door services across the whole of the Old World.",
      card_air: "Our air freight team gives you the reassurance that your goods are in good hands.",
      card_sea: "Our sea freight professionals draw on extensive experience to manage cargo flows.",
      card_special: "Oversized transport is backed by a dedicated desk for study and implementation.",
      card_customs: "Your goods are placed under a duty-suspension regime until the final destination you choose.",
      all_locations: "All locations",
      group: "OUR GROUP OF COMPANIES",
      downloads: "Downloads",
      terms: "General terms and conditions",
      containers: "Container dimensions",
      ges: "GHG emissions report",
      airlines: "Airline codes",
      vat_ecom: "VAT & E-Commerce",
      customs_form: "Customs & formalities",
      network: "Travex Global Forwarding network",
      hq: "Head office",
      legal: "Legal notice",
      privacy: "Privacy policy",
      rights: "© 2026 Travex Global Forwarding • All rights reserved.",
      since: "Logistics since 1866",
      cookie: "We use cookies and other technologies on our website. Some are essential, others help us improve your experience. Google services (Maps, YouTube) are only loaded after consent. More information in our privacy policy.",
      accept: "Accept",
      essential: "Essential cookies",
      quote_title: "Request a free quote",
      transport_type: "Type of transport",
      transport_type_ph: "Type of Transport",
      warehousing: "Warehousing",
      air_t: "Air transport",
      sea_t: "Sea transport",
      multi_t: "Multimodal transport",
      road_t: "Road transport",
      incoterms: "Terms of sale",
      from_city: "City of departure",
      to_city: "City of arrival",
      weight: "Weight (kg)",
      email: "E-mail",
      privacy_ok: "Privacy policy accepted.",
      quote_ext: "For an extended version of the form, click here.",
      click_here: "click here",
      send: "Send →",
      quote_ok: "Your request has been sent. A Travex Global Forwarding sales contact will get back to you shortly.",
      special_title: "What makes us special?",
      special_lead: "As a European company with a worldwide presence, we offer our clients a complete portfolio of services.",
      pack: "Packing and storage", pack_p: "Conditioning and protection of your goods.",
      ware: "Warehousing service", ware_p: "Secure platforms in France and worldwide.",
      road_f: "Road freight", road_p: "Groupage and full loads across the Old World.",
      logi: "Logistics services", logi_p: "End-to-end flow management.",
      tel: "Tel.",
      belgium: "Belgium", china: "China", czech: "Czech Republic", germany_ngl: "Germany (NGL)",
      germany_mon: "Germany (Monnard)", lux: "Luxembourg", mexico: "Mexico", morocco: "Morocco",
      nl: "Netherlands", nc: "New Caledonia", senegal: "Senegal", spain: "Spain",
      ch: "Switzerland", tahiti: "Tahiti", turkey: "Turkey", uk: "United Kingdom",
      home_crumb: "Home"
    }
  };

  function t(key) {
    const cms = window.GONDRAND_CMS || {};
    if (cms[key]) return cms[key];
    return (I18N[LANG] && I18N[LANG][key]) || (I18N.fr[key]) || key;
  }

  const ICONS = {
    truck: `<svg viewBox="0 0 24 24"><rect x="1" y="7" width="15" height="10"/><path d="M16 11h4l3 3v3h-7"/><circle cx="6" cy="19" r="2"/><circle cx="18" cy="19" r="2"/></svg>`,
    plane: `<svg viewBox="0 0 24 24"><path d="M21 16v-2l-8-5V3.5A1.5 1.5 0 0 0 11.5 2 1.5 1.5 0 0 0 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5z"/></svg>`,
    ship: `<svg viewBox="0 0 24 24"><path d="M3 17c1.5 1.5 4 3 9 3s7.5-1.5 9-3"/><path d="M4 13h16l-1.5 4H5.5z"/><path d="M6 13V8h4v5M12 13V6h4v7"/></svg>`,
    special: `<svg viewBox="0 0 24 24"><rect x="3" y="10" width="18" height="8"/><path d="M7 10V7h10v3"/><circle cx="7" cy="20" r="2"/><circle cx="17" cy="20" r="2"/></svg>`,
    customs: `<svg viewBox="0 0 24 24"><path d="M4 4h16v4H4z"/><path d="M6 8v12M18 8v12M6 20h12"/><path d="M9 12h6M9 16h6"/></svg>`,
    tax: `<svg viewBox="0 0 24 24"><path d="M6 3h9l3 3v15H6z"/><path d="M15 3v4h4"/><path d="M9 13h6M9 17h4"/></svg>`
  };

  const NAV = [
    { href: "index.html", label: t("nav_home"), id: "home" },
    { href: "entreprise/index.html", label: t("nav_company"), id: "entreprise" },
    {
      label: t("nav_services"), id: "services", children: [
        { href: "services/luftfracht-2/", label: t("svc_road") },
        { href: "services/ueber-uns/", label: t("svc_air") },
        { href: "services/beratung-2/", label: t("svc_sea") },
        { href: "services/seefracht-2/", label: t("svc_special") },
        { href: "services/zoll-2/", label: t("svc_customs") },
        { href: "representation-fiscale/index.html", label: t("svc_vat") }
      ]
    },
    { href: "demande-de-cotation/index.html", label: t("nav_quote"), id: "devis" },
    { href: "contact/index.html", label: t("nav_contact"), id: "contact" }
  ];


  const LOCS = {
    R: [
      { n: "TRAVEX GLOBAL FORWARDING – RENCHEN", a: "Im Brünnel 2\n77871 Renchen", t: "", e: [], nearby: "Notre point de réception est facilement accessible depuis :\n\n🇩🇪 Allemagne\n• Renchen – 0 km\n• Appenweier – env. 5 km\n• Achern – env. 8 km\n• Oberkirch – env. 9 km\n• Offenburg – env. 14 km\n• Baden-Baden – env. 25 km\n• Kehl – env. 20 km\n• Karlsruhe – env. 60 km\n• Freiburg – env. 70 km\n• Pforzheim – env. 80 km\n• Stuttgart – env. 130 km\n\n🇫🇷 France\n• Strasbourg – env. 35 km\n• Alsace et environs – facilement accessibles\n\n🇨🇭 Suisse\n• Bâle – env. 140 km\n• Zurich – env. 200 km\n• Berne – env. 220 km" }
    ]
  };


  function url(path) {
    if (!path || path === "index.html") return BASE ? BASE + "index.html" : "index.html";
    return BASE + path;
  }

  function headerHTML() {
    const links = NAV.map(item => {
      if (item.children) {
        return `<div class="drop">
          <span>${item.label} ▾</span>
          <div class="drop-menu">${item.children.map(c => `<a href="${url(c.href)}">${c.label}</a>`).join("")}</div>
        </div>`;
      }
      const active = item.id === PAGE ? "active" : "";
      return `<a class="${active}" href="${url(item.href)}">${item.label}</a>`;
    }).join("");

    return `
    <div class="topbar">
      <div class="wrap">
        <div class="lang">
          <button type="button" class="lang-btn${LANG === "fr" ? " on" : ""}" data-lang="fr">FR</button>
          <button type="button" class="lang-btn${LANG === "en" ? " on" : ""}" data-lang="en">EN</button>
        </div>
        <div class="top-links">
          <a href="${url("demande-de-cotation/index.html")}">${t("nav_rfq")}</a>
        </div>
      </div>
    </div>
    <header class="header">
      <div class="wrap">
        <a class="logo" href="${url("index.html")}" aria-label="Travex Global Forwarding accueil">
          <img src="${window.GONDRAND_LOGO || ((window.GONDRAND_ASSETS || BASE) + "images/logo-gondrand.png")}" alt="TRAVEX GLOBAL FORWARDING">
        </a>
        <button class="burger" id="burger" aria-label="Menu">☰</button>
        <nav class="nav" id="nav">${links}</nav>
      </div>
    </header>`;
  }

  function footerHTML() {
    return `
    <section class="group">
      <div class="wrap">
        <h2>${t("group")}</h2>
        <div class="brands">
          <a class="brand" href="${url("index.html")}">TRAVEX<small>GLOBAL FORWARDING</small></a>
        </div>
      </div>
    </section>
    <footer class="footer">
      <div class="wrap">
        <div class="fgrid">
          <div>
            <h4>${t("downloads")}</h4>
            <ul>
              <li><a href="#">${t("terms")}</a></li>
              <li><a href="#">${t("containers")}</a></li>
              <li><a href="#">${t("ges")}</a></li>
              <li><a href="#">${t("airlines")}</a></li>
              <li><a href="#">${t("vat_ecom")}</a></li>
              <li><a href="#">${t("customs_form")}</a></li>
            </ul>
          </div>
          <div>
            <h4>${t("network")}</h4>
            <div class="countries">
              <span>Allemagne</span><span>France</span><span>Suisse</span>
            </div>
          </div>
          <div>
            <h4>${t("hq")}</h4>
            <p>Im Brünnel 2<br>77871 Renchen</p>
            <p style="margin-top:12px"><a href="${url("mentions-legales/index.html")}">${t("legal")}</a><br>
            <a href="${url("mentions-legales/index.html")}#privacy">${t("privacy")}</a></p>
          </div>
        </div>
        <div class="copy">
          <div class="certs"><span>IATA</span><span>OEA</span><span>ISO 9001</span></div>
          <div>© 2026 Travex Global Forwarding • Tous droits réservés.</div>
          <div>Logistique depuis 1866</div>
        </div>
      </div>
    </footer>
    <div class="cookie" id="cookie">
      <p>${t("cookie")} <a href="${url("mentions-legales/index.html")}#privacy">${t("privacy")}</a>.</p>
      <div class="actions">
        <button class="btn" id="cookie-ok">${t("accept")}</button>
        <button class="btn ghost" id="cookie-min">${t("essential")}</button>
      </div>
    </div>`;
  }

  function quoteHTML(compact) {
    return `
    <div class="quote" id="devis">
      <h2>${t("quote_title")}</h2>
      <form id="quote-form" action="${url("send.php")}" method="post">
        <input type="text" name="website" class="hp" tabindex="-1" autocomplete="off" aria-hidden="true">
        <div class="grid-2">
          <div class="row">
            <label>${t("transport_type")}</label>
            <select name="type" required>
              <option value="">${t("transport_type_ph")}</option>
              <option>${t("warehousing")}</option>
              <option>${t("air_t")}</option>
              <option>${t("sea_t")}</option>
              <option>${t("multi_t")}</option>
              <option>${t("road_t")}</option>
            </select>
          </div>
          <div class="row">
            <label>${t("incoterms")}</label>
            <select name="incoterms">
              <option value="">${t("incoterms")}</option>
              <option>EXW</option><option>FCA</option><option>FAS</option>
              <option>FOB</option><option>CFR</option><option>CIF</option>
              <option>CPT</option><option>CIP</option><option>DAP</option>
              <option>DPU</option><option>DDP</option>
            </select>
          </div>
        </div>
        ${compact ? "" : `
        <div class="grid-2">
          <div class="row"><label>${t("from_city")}</label><input name="from" required placeholder="City / country"></div>
          <div class="row"><label>${t("to_city")}</label><input name="to" required placeholder="City / country"></div>
        </div>
        <div class="grid-2">
          <div class="row"><label>${t("weight")}</label><input name="weight" type="number" min="0"></div>
          <div class="row"><label>${t("email")}</label><input name="email" type="email" required placeholder="you@company.com"></div>
        </div>`}
        ${compact ? `<div class="row"><label>${t("email")}</label><input name="email" type="email" required placeholder="you@company.com"></div>` : ""}
        <div class="row">
          <label class="check"><input type="checkbox" required> <span><a href="${url("mentions-legales/index.html")}#privacy">${t("privacy")}</a> — ${t("privacy_ok")}</span></label>
        </div>
        <p style="font-size:12px;color:#64748b;margin-bottom:12px">${t("quote_ext").replace(t("click_here"), `<a href="${url("demande-de-cotation/index.html")}">${t("click_here")}</a>`)}</p>
        <button class="btn ghost" type="submit">${t("send")}</button>
      </form>
      <div class="okbox" id="quote-ok">${t("quote_ok")}</div>
    </div>`;
  }

  function specialHTML() {
    return `
    <div>
      <div class="bar"></div>
      <h2>${t("special_title")}</h2>
      <p class="lead" style="margin-bottom:18px">${t("special_lead")}</p>
      <div class="specials">
        <div class="feat"><div class="ico">▣</div><div><h4>${t("pack")}</h4><p>${t("pack_p")}</p></div></div>
        <div class="feat"><div class="ico">⌂</div><div><h4>${t("ware")}</h4><p>${t("ware_p")}</p></div></div>
        <div class="feat"><div class="ico">▭</div><div><h4>${t("road_f")}</h4><p>${t("road_p")}</p></div></div>
        <div class="feat"><div class="ico">◈</div><div><h4>${t("logi")}</h4><p>${t("logi_p")}</p></div></div>
      </div>
    </div>`;
  }

  window.Travex = { ICONS, LOCS, quoteHTML, specialHTML, url };

  function mountChrome() {
    const h = document.getElementById("site-header");
    const f = document.getElementById("site-footer");
    if (h) h.outerHTML = headerHTML();
    if (f) f.outerHTML = footerHTML();
    const burger = document.getElementById("burger");
    const nav = document.getElementById("nav");
    if (burger && nav) burger.onclick = () => nav.classList.toggle("open");
    const cookie = document.getElementById("cookie");
    if (cookie && !localStorage.getItem("g-cookie")) cookie.classList.add("show");
    const ok = document.getElementById("cookie-ok");
    const min = document.getElementById("cookie-min");
    function hide() { localStorage.setItem("g-cookie", "1"); cookie.classList.remove("show"); }
    if (ok) ok.onclick = hide;
    if (min) min.onclick = hide;
    document.querySelectorAll(".lang-btn").forEach(btn => {
      btn.addEventListener("click", () => {
        const next = btn.getAttribute("data-lang") === "en" ? "en" : "fr";
        localStorage.setItem("g-lang", next);
        location.reload();
      });
    });
  }

  function applyI18n() {
    document.documentElement.lang = LANG;
    document.querySelectorAll("[data-i18n]").forEach(el => {
      const key = el.getAttribute("data-i18n");
      if (I18N[LANG] && I18N[LANG][key]) el.textContent = I18N[LANG][key];
    });
    document.querySelectorAll(".crumbs a").forEach(a => {
      if (/Accueil|Home/.test(a.textContent.trim())) a.textContent = t("home_crumb");
    });
    if (LANG !== "en") return;
    const extra = {
      "GROUPAGE": "GROUPAGE",
      "Vêtements suspendus": "Hanging garments",
      "LOCATION": "CHARTER / HIRE",
      "Module de performance du fret routier": "Road freight performance module",
      "Module de performance de fret aérien": "Air freight performance module",
      "Module de performance du fret maritime": "Sea freight performance module",
      "Module de performance des trafics spéciaux": "Special cargo performance module",
      "Spécialiste sur les DROM-COM": "Specialist for French overseas territories",
      "Historique": "History",
      "Contact & emplacements": "Contact & locations",
      "Paris — Direction générale": "Paris — Head office",
      "Recevez des devis gratuits": "Get free quotes",
      "Mentions légales": "Legal notice",
      "Représentation fiscale": "Tax representation",
      "Services": "Services",
      "Nos services": "Our services",
      "DE VALEUR   EXPRESS   IMPORTANT": "HIGH VALUE   EXPRESS   IMPORTANT",
      "GRANDE VALEUR   RAPIDE   IMPORTANT": "HIGH VALUE   FAST   IMPORTANT",
      "GRANDE VALEUR · RAPIDE · IMPORTANT": "HIGH VALUE · FAST · IMPORTANT"
    };
    Object.keys(I18N.fr).forEach(k => {
      if (I18N.fr[k] && I18N.en[k] && I18N.fr[k] !== I18N.en[k]) extra[I18N.fr[k]] = I18N.en[k];
    });
    const walk = (node) => {
      if (node.nodeType === 3) {
        const trimmed = node.nodeValue.trim();
        if (extra[trimmed]) node.nodeValue = node.nodeValue.replace(trimmed, extra[trimmed]);
      } else if (node.nodeType === 1 && !/^(SCRIPT|STYLE|TEXTAREA|INPUT)$/.test(node.tagName)) {
        node.childNodes.forEach(walk);
      }
    };
    walk(document.body);
  }

  function bindQuote() {
    document.addEventListener("submit", (e) => {
      const form = e.target.closest("#quote-form");
      if (!form) return;
      e.preventDefault();
      const btn = form.querySelector('[type="submit"]');
      if (btn) btn.disabled = true;
      const action = form.getAttribute("action") || (BASE + "send.php");
      fetch(action, { method: "POST", body: new FormData(form) })
        .then(r => r.json().catch(() => ({ ok: false })))
        .then(data => {
          if (data && data.ok) {
            form.style.display = "none";
            const box = form.parentElement.querySelector(".okbox");
            if (box) box.classList.add("show");
          } else {
            alert(LANG === "en" ? "The message could not be sent. Please try again or email accueil.dg@gondrand.fr" : "L'envoi a échoué. Réessayez ou écrivez à accueil.dg@gondrand.fr");
            if (btn) btn.disabled = false;
          }
        })
        .catch(() => {
          alert(LANG === "en" ? "The message could not be sent. Please try again." : "L'envoi a échoué. Réessayez.");
          if (btn) btn.disabled = false;
        });
    });
  }

  function locItems() {
    if (Array.isArray(window.GONDRAND_LOCS) && window.GONDRAND_LOCS.length) {
      return window.GONDRAND_LOCS;
    }
    const out = [];
    Object.keys(LOCS).forEach(k => (LOCS[k] || []).forEach(x => out.push(x)));
    return out;
  }

  function renderLocations() {
    const grid = document.getElementById("loc-grid");
    if (!grid) return;
    const az = document.getElementById("az");
    if (az) az.style.display = "none";
    grid.classList.add("loc-stack");
    const items = locItems();
    grid.innerHTML = items.map(x => `
      <article class="loc loc-wide">
        <h3>${x.n || x.title || ""}</h3>
        <p>${(x.a || x.address || "").replace(/\n/g, "<br>")}</p>
        ${x.t ? `<p>${t("tel")} ${x.t}</p>` : ""}
        ${(x.e || []).map(m => `<a href="mailto:${m}">${m}</a>`).join("")}
        ${x.nearby || x.text ? `<div class="loc-nearby">${(x.nearby || x.text || "").replace(/\n/g, "<br>")}</div>` : ""}
      </article>`).join("");
  }

  function mountAZ() {
    renderLocations();
  }

  function slider() {
    const slides = document.querySelectorAll(".slide");
    if (!slides.length) return;
    let i = 0;
    const navs = document.querySelectorAll(".rev_slider_nav, .icon-card");
    const show = (n) => {
      i = (n + slides.length) % slides.length;
      slides.forEach((s, k) => s.classList.toggle("active", k === i));
      navs.forEach((c, k) => c.classList.toggle("active", k === i));
    };
    navs.forEach((c, k) => c.addEventListener("click", (e) => {
      e.preventDefault();
      const idx = parseInt(c.getAttribute("data-slide"), 10);
      show(Number.isFinite(idx) && idx > 0 ? idx - 1 : k);
    }));
    const prev = document.querySelector(".hero-nav .prev");
    const next = document.querySelector(".hero-nav .next");
    if (prev) prev.onclick = () => show(i - 1);
    if (next) next.onclick = () => show(i + 1);
    setInterval(() => show(i + 1), 7000);
  }

  function tabs() {
    document.querySelectorAll("[data-tabs]").forEach(root => {
      root.addEventListener("click", (e) => {
        const b = e.target.closest("button[data-tab]");
        if (!b) return;
        root.querySelectorAll("button[data-tab]").forEach(x => x.classList.toggle("on", x === b));
        root.parentElement.querySelectorAll(".panel").forEach(p => p.classList.toggle("on", p.id === b.dataset.tab));
      });
    });
  }

  document.addEventListener("DOMContentLoaded", () => {
    mountChrome();
    bindQuote();
    mountAZ();
    slider();
    tabs();
    applyI18n();
  });
})();
