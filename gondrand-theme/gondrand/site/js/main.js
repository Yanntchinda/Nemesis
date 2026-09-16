/* GONDRAND France site logic */
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
      loc_title: "GONDRAND FRANCE EMPLACEMENTS",
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
      network: "Réseau Gondrand",
      hq: "Siège",
      legal: "Mentions légales",
      privacy: "Politique de confidentialité",
      rights: "© 2026 NGL Gondrand Group SA • Tous droits réservés.",
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
      quote_ok: "Votre demande a bien été transmise. Un commercial Gondrand vous répondra dans les plus brefs délais.",
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
      loc_title: "GONDRAND FRANCE LOCATIONS",
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
      network: "Gondrand network",
      hq: "Head office",
      legal: "Legal notice",
      privacy: "Privacy policy",
      rights: "© 2026 NGL Gondrand Group SA • All rights reserved.",
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
      quote_ok: "Your request has been sent. A Gondrand sales contact will get back to you shortly.",
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
    A: [
      { n: "ANNEMASSE", a: "15, rue des Esserts\nB.P. 276\n74106 ANNEMASSE Cedex", t: "04 50 37 43 80", f: "04 50 37 43 20", e: ["thomas.sailer@gondrand.fr"] }
    ],
    B: [
      { n: "BORDEAUX MÉRIGNAC", a: "Aérogare de fret\nBâtiment des Transitaires\nCidex B. 18\nRue Camille Flammarion\n33700 MÉRIGNAC Aéroport", t: "05 56 34 13 35", f: "05 56 34 35 37", e: ["sandrine.kaufling@gondrand.fr"] }
    ],
    C: [
      { n: "CALAIS PORT", a: "Terminal Transmanche\n62100 CALAIS", t: "03 21 19 03 30", e: ["sps.calais@gondrand.fr", "aurelie.mourmand@gondrand.fr"] },
      { n: "CHAMBÉRY", a: "B.P. 9403\n430 rue Félix Esclangon\n73290 LA MOTTE SERVOLEX", t: "04 79 69 14 18", f: "04 79 69 77 17", e: ["enzo.vallario@gondrand.fr"] },
      { n: "CHAUMONT", a: "Route de Jonchery - RN 19\nB.P. 89\n52003 CHAUMONT Cedex", t: "03 25 35 31 10", f: "03 25 32 59 20", e: ["jessica.lebatteux@gondrand.fr", "nathalie.lecuillier@gondrand.fr"] },
      { n: "CHADRAC / LE PUY", a: "14 Bld de la Petite Mer\nB.P. 702 CHADRAC\n43770 LE PUY EN VELAY", t: "04 71 03 69 35", e: ["olivier.chaussende@gondrandvalence.com", "dominique.arod@gondrand.fr"] },
      { n: "COLMAR", a: "13, rue Curie\nB.P. 1339\n68013 COLMAR Cedex", t: "03 89 21 03 21", f: "03 89 21 03 20", e: ["elodie.dochez@gondrand.fr"] },
      { n: "CAYENNE GUYANE", a: "N°10 c Rue des Quais\nZ.I Port de Dégrad des Cannes\nImm. SCI MOMA/Stock Mahury\n97354 REMIRE-MONTJOLY", t: "05 94 35 82 50", f: "05 94 35 89 61", e: ["marie.therese.moraux@gondrand.fr", "fabian.decodts@gondrand.fr"] },
      { n: "CREIL", a: "19 rue de Dheisheh\n60160 MONTATAIRE", t: "03 44 55 86 20", f: "03 44 55 14 84", e: ["paul.ferreira@gondrand.fr"] }
    ],
    D: [
      { n: "DUNKERQUE", a: "11/19, quai du Risban\nB.P. 3513\n59383 DUNKERQUE Cedex 1", t: "03 28 58 04 30", f: "03 28 66 08 48", e: ["fabian.decodts@gondrand.fr"] }
    ],
    E: [
      { n: "ENNERY", a: "Rue du Douanier Rousseau\nEnceinte Garolor\nBâtiment A1 - entrée A\nBP 20003\n57365 ENNERY", t: "03 87 70 83 80", f: "03 87 70 83 81", e: ["gerard.greff@gondrand.fr"] }
    ],
    F: [
      { n: "FERNEY-VOLTAIRE", a: "Service Douanes\nRoute de Genève\n01210 FERNEY-VOLTAIRE\nB.P. 276\n74106 ANNEMASSE Cedex", t: "04 50 40 55 39 (Road) / 04 50 28 07 05 (Air)", f: "04 50 40 80 65", e: ["thomas.sailer@gondrand.fr"] }
    ],
    G: [
      { n: "GOLBEY", a: "4, Rue du Général Leclerc\n88190 GOLBEY", t: "03 29 31 18 01", f: "03 29 31 10 73", e: ["direction.epinal@gondrand.fr"] },
      { n: "GRAULHET", a: "43, Carrefour de l'Europe\n81300 GRAULHET", t: "05 63 34 35 45", f: "05 63 34 73 46", e: ["serge.seguier@gondrand.fr"] }
    ],
    H: [
      { n: "HALLUIN", a: "10 Chemin de la Cavale Rouge\n59250 HALLUIN", t: "03 20 28 25 30", f: "03 20 03 36 25", e: ["fabian.decodts@gondrand.fr", "vincent.hedin@gondrand.fr"] }
    ],
    L: [
      { n: "LE HAVRE", a: "Zone Industrielle Est du Havre\nPort 5383\nVoie des Sarcelles\n76430 ST VIGOR D'YMONVILLE", t: "02 35 25 59 00", f: "02 35 53 15 07", e: ["emmanuel.couteau@gondrand.fr"] },
      { n: "LAFRANQUE & CIE", a: "26, Rue Robert Destarac\n65000 TARBES", t: "05 62 36 04 77", f: "05 62 36 20 89", e: ["contact@demenagements-lafranque.com"] },
      { n: "LESQUIN", a: "Aérogare de fret\nCRT n° 1\n59817 LESQUIN Cedex", t: "03 20 90 79 00", f: "03 20 90 79 01", e: ["melanie.lietaert@gondrand.fr"] },
      { n: "LYON", a: "42 Avenue du Progrès\nB.P. 60\n69684 CHASSIEU CEDEX", t: "04 78 69 50 02", f: "04 72 73 47 98", e: ["elodie.beauchet@gondrand.fr"] },
      { n: "LYON EUREXPO", a: "Entrée \"Poids lourds\"\n69680 CHASSIEU", t: "04 72 22 30 22", f: "04 72 22 30 59", e: ["didier.fanton@gondrand.fr"] },
      { n: "LYON AIR FREIGHT", a: "Bâtiment L'ARCHER\nZAC SATOLAS GREEN\n69330 PUSIGNAN", t: "04 72 22 63 30", f: "04 72 22 75 73", e: ["christian.beaudot@gondrand.fr"] }
    ],
    M: [
      { n: "MARIGNANE", a: "Aéroport MARSEILLE-PROVENCE\nGare de fret\nB.P. 42079\n13846 VITROLLES Cedex 09", t: "04 42 89 04 77", f: "04 42 79 75 58", e: ["serge.giannuzzi@gondrand.fr"] },
      { n: "MAZAMET", a: "10, allée de la Falgalarié\nB.P. 512\n81204 AUSSILLON MAZAMET", t: "05 63 98 70 70", f: "05 63 61 52 14", e: ["serge.seguier@gondrand.fr"] },
      { n: "MONTATAIRE", a: "19 rue de Dheisheh\n60160 MONTATAIRE", t: "03 44 25 12 37", f: "03 44 24 62 80", e: ["paul.ferreira@gondrand.fr"] }
    ],
    N: [
      { n: "NANTES BOUGUENAIS", a: "Aéroport de NANTES ATLANTIQUE\nAérogare de fret\n44340 BOUGUENAIS", t: "02 55 16 02 91", e: ["corinne.meuriel@gondrand.fr"] },
      { n: "NOUMÉA", a: "1, rue Anatole France\nB.P. 4962\n98847 Nouméa", t: "00 687 27 41 18", f: "00 687 27 53 55", e: ["direction@gondrand.nc"] },
      { n: "NICE", a: "Aéroport Zone de fret\nEntrée 2 - Bureau 202\n06281 NICE Cedex 3", t: "04 89 22 41 68", f: "04 93 21 38 53", e: ["michael.maldonado@gondrand.fr"] }
    ],
    P: [
      { n: "PARIS (SIÈGE)", a: "11 rue de Lübeck\n75116 Paris", t: "+33 1 44 13 14 00", f: "+33 1 44 13 14 10", e: ["accueil.dg@gondrand.fr"] },
      { n: "PARIS ROISSY", a: "8 rue du Cercle\nROISSYTECH-ZF 4\n95723 ROISSY CDG", t: "01 41 84 59 61", f: "01 41 84 59 80", e: ["laure.blondel@gondrand.fr"] },
      { n: "PARIS ORLY", a: "Zone Cargo\nRue de la Soie\n94392 ORLY AEROGARE CEDEX", t: "01 88 15 00 91", e: ["exploitation.ory@gondrand.fr"] },
      { n: "PARIS LES ULIS", a: "3, Avenue d'Amazonie\n91940 LES ULIS", t: "01 69 18 80 70/77", f: "01 69 07 76 68", e: ["herve.madec@gondrand.fr"] },
      { n: "PARIS GONESSE", a: "Chez Mazet\n1 Avenue Nungesser et Coli\n95502 Gonesse Cedex", t: "01 45 91 64 64", e: ["aline.vanschuerbeek@gondrand.fr"] },
      { n: "PARIS GONDRAND VOYAGES", a: "37 Avenue Paul Doumer\n75116 PARIS", t: "01 45 04 63 09", f: "01 44 13 14 05", e: ["doumer@gondrand-voyages.fr"] },
      { n: "PERSAN", a: "Société VICTOR MARTINET & Cie\nZAC des Quatre Rainettes\n15 Rue du Général de Gaulle\n60530 LE MESNIL-EN-THELLE", t: "01 39 37 40 47", f: "01 30 34 43 03", e: ["landry.leulier@v-martinet.fr"] },
      { n: "PORT SAINT LOUIS DU RHÔNE / FOS", a: "ZI Distriport\nAvenue de Shanghai BT B2\n13230 Port Saint Louis du Rhône", t: "04 42 11 70 90", f: "04 42 48 91 65", e: ["emilie.auddino@gondrand.fr"] }
    ],
    S: [
      { n: "SAINT DIZIER", a: "Z.I. de Troisfontaines\nB.P. 42\n52102 SAINT DIZIER Cedex", t: "03 25 56 74 00", f: "03 25 05 79 21", e: ["nathalie.lecuillier@gondrand.fr"] },
      { n: "SARAN", a: "ZA Les Vallées\nR.N. 20\n45770 SARAN", t: "02 38 43 09 00", f: "02 38 43 97 39", e: ["michel.lorient@gondrand.fr"] },
      { n: "SAUSHEIM", a: "Autoport\nCentre routier douanier\n68390 SAUSHEIM", t: "03 89 31 73 50", f: "03 89 61 73 51", e: ["elodie.dochez@gondrand.fr"] },
      { n: "STRASBOURG", a: "31, rue de Bayonne\nB.P. 50053\n67020 STRASBOURG Cedex 1", t: "03 90 40 45 69", f: "03 90 40 45 51", e: ["Yannick.fleurette@gondrand.fr"] },
      { n: "SAINT LOUIS", a: "Parking TIR\nB.P. 50049\n68302 SAINT LOUIS cedex", t: "03 89 69 57 85", f: "03 89 69 06 30", e: ["elodie.dochez@gondrand.fr"] },
      { n: "ST JULIEN EN GENEVOIS (FRANCE)", a: "Zac de Puy Saint Martin\nPlateforme de BARDONNEX\nB.P. 225\n74160 ST JULIEN EN GENEVOIS", t: "Export 04 50 49 35 95 / Import 04 50 49 17 27", f: "04 50 49 28 42", e: ["thomas.sailer@gondrand.fr"] },
      { n: "ST JULIEN EN GENEVOIS (SUISSE)", a: "20 Chemin des Epinglis\nBP 42\n1257 BARDONNEX / 1258 PERLY (SUISSE)", t: "Export 04 50 35 17 27 / Import +41 227 714 272", f: "04 50 49 28 42", e: ["thomas.sailer@gondrand.fr"] }
    ],
    T: [
      { n: "TAHITI", a: "15, rue du Docteur Cassiau\nB.P. 475\n98713 Papeete", t: "+689 40 54 31 52", f: "+689 40 42 28 33", e: ["commercial-exploitation.ppt@gondrand.pf"] },
      { n: "THOUARÉ SUR LOIRE", a: "Actipôle\nRue du Danube\nB.P. 24\n44470 THOUARÉ-SUR-LOIRE", t: "02 51 13 02 00", f: "02 51 13 06 40", e: ["martin.sevin@gondrand.fr"] },
      { n: "TOULOUSE", a: "71, rue Jules Verne\n31200 TOULOUSE", t: "05 61 47 90 06", f: "05 61 47 21 64", e: ["marjorie.gonzalez@gondrand.fr"] },
      { n: "THONEX VALLARD", a: "74100 THONEX VALLARD\nB.P. 276\n74106 ANNEMASSE Cedex", t: "00 41 22 869 88 10", f: "00 41 22 869 88 19", e: ["thomas.sailer@gondrand.fr"] }
    ],
    V: [
      { n: "VALENCE", a: "Z.I. La Motte Nord\nRue Jacques Yves Cousteau\nB.P. 6\n26801 PORTES-LES-VALENCE Cedex", t: "04 75 57 11 22", f: "04 75 57 21 69", e: ["dominique.arod@gondrand.fr"] },
      { n: "VITROLLES", a: "11, rue d'Athènes\nZ.I. Les Estroublans\nB.P. 42079\n13846 VITROLLES Cedex 09", t: "04 42 46 18 71", f: "04 42 46 20 54", e: ["virgil.maze@gondrand.fr"] },
      { n: "VILLEPINTE", a: "PARIS NORD II\nBâtiment 2\n93420 VILLEPINTE", t: "01 48 63 32 77", f: "01 48 63 32 87", e: ["didier.fanton@gondrand.fr"] }
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
          <a href="${url("entreprise/index.html")}">${t("nav_company")}</a>
          <a href="${url("contact/index.html")}#emploi">${t("nav_jobs")}</a>
          <a href="${url("contact/index.html")}">${t("nav_locations")}</a>
          <a href="${url("demande-de-cotation/index.html")}">${t("nav_rfq")}</a>
        </div>
      </div>
    </div>
    <header class="header">
      <div class="wrap">
        <a class="logo" href="${url("index.html")}" aria-label="Gondrand accueil">
          <img src="${BASE}images/logo-gondrand.png" alt="GONDRAND">
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
          <a class="brand" href="${url("index.html")}">GONDRAND<small>FRANCE</small></a>
          <a class="brand ngl" href="https://ngl-germany.eu/" target="_blank" rel="noopener">NGL<small>GERMANY</small></a>
          <a class="brand mon" href="https://www.monnard.com/" target="_blank" rel="noopener">MONNARD<small>SPEDITION</small></a>
          <a class="brand mf" href="https://www.monfreight.com/" target="_blank" rel="noopener">MONFREIGHT<small>INC.</small></a>
          <a class="brand cf" href="https://www.cargoflores.com/en/" target="_blank" rel="noopener">CARGO FLORES<small>NGL GROUP</small></a>
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
              <span>${t("belgium")}</span><span>${t("china")}</span><span>${t("czech")}</span>
              <span>France</span><span>${t("germany_ngl")}</span><span>${t("germany_mon")}</span>
              <span>${t("lux")}</span><span>${t("mexico")}</span><span>${t("morocco")}</span>
              <span>${t("nl")}</span><span>${t("nc")}</span><span>${t("senegal")}</span>
              <span>${t("spain")}</span><span>${t("ch")}</span><span>${t("tahiti")}</span>
              <span>${t("turkey")}</span><span>${t("uk")}</span><span>USA</span>
            </div>
          </div>
          <div>
            <h4>${t("hq")}</h4>
            <p>11 rue de Lübeck<br>75116 Paris<br>${t("tel")} +33 1 44 13 14 00<br>
            <a href="mailto:accueil.dg@gondrand.fr">accueil.dg@gondrand.fr</a></p>
            <p style="margin-top:12px"><a href="${url("mentions-legales/index.html")}">${t("legal")}</a><br>
            <a href="${url("mentions-legales/index.html")}#privacy">${t("privacy")}</a></p>
          </div>
        </div>
        <div class="copy">
          <div class="certs"><span>IATA</span><span>OEA</span><span>ISO 9001</span></div>
          <div>© 2026 NGL Gondrand Group SA • Tous droits réservés.</div>
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

  window.Gondrand = { ICONS, LOCS, quoteHTML, specialHTML, url };

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

  function renderLocations(letter) {
    const grid = document.getElementById("loc-grid");
    if (!grid) return;
    const keys = Object.keys(LOCS);
    const L = letter && LOCS[letter] ? letter : keys[0];
    document.querySelectorAll(".az button").forEach(b => b.classList.toggle("on", b.dataset.l === L));
    const items = LOCS[L] || [];
    grid.innerHTML = items.map(x => `
      <article class="loc">
        <h3>${x.n}</h3>
        <p>${x.a.replace(/\n/g, "<br>")}</p>
        <p>${t("tel")} ${x.t}${x.f ? "<br>Fax " + x.f : ""}</p>
        ${(x.e || []).map(m => `<a href="mailto:${m}">${m}</a>`).join("")}
      </article>`).join("");
  }

  function mountAZ() {
    const az = document.getElementById("az");
    if (!az) return;
    const letters = "ABCDEFGHIJKLMNOPQRSTUVWXYZ".split("");
    az.innerHTML = letters.map(l => `<button data-l="${l}" ${LOCS[l] ? "" : "disabled"}>${l}</button>`).join("");
    az.addEventListener("click", (e) => {
      const b = e.target.closest("button");
      if (!b || b.disabled) return;
      renderLocations(b.dataset.l);
    });
    renderLocations("P");
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
