# Mise en ligne chez LWS + e-mails

Ce n’est **pas WordPress**. Il n’y a pas de plugin à installer.  
Uploadez les fichiers dans **`htdocs`** (LWS Panel) ou **`public_html`** (cPanel).

## E-mails (formulaires de devis)

1. Ouvrez `config-mail.php`
2. Remplacez `'to' => 'accueil.dg@gondrand.fr'` par **votre vraie adresse** (idéalement une boîte créée chez LWS, ex. `contact@votre-domaine.fr`)
3. Chez LWS : **E-mails** → créez cette adresse
4. Testez un devis sur le site

LWS envoie le mail via PHP `mail()`. La fonction marche uniquement **une fois le site en ligne** (pas en local).
