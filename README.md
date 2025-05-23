#  Application Laravel SaaS - SNEE

Sneesaas est une application SaaS développée avec Laravel, utilisant Blade pour le templating et TailwindCSS pour le design.  
L’application est hébergée sur Render, la base de données MySQL est managée via OVH Cloud, et le nom de domaine est configuré sur OVH.

Ce dépôt contient le code source de l'application Laravel utilisant Blade, TailwindCSS et MySQL. Elle est destinée à être déployée via Render avec une gestion des environnements de développement, test (beta) et production.

##  Structure des branches

| Branche     | Environnement     | Base de données         | Usage                          |
|-------------|-------------------|--------------------------|---------------------------------|
| `main`      | Render (prod)     | MySQL Render (en ligne) | Version stable en production   |
| `beta`      | Local             | MySQL Render (en ligne) | Version de pré-prod/test       |
| `develop`   | Local             | MySQL local (fictive)   | Développement quotidien        |

---

##  Environnements

Trois environnements sont définis via des fichiers `.env` :

- `.env.production` → utilisé sur Render
- `.env.beta` → pour tester localement avec la base de données **de production**
- `.env.development` → utilisé pour développer avec une base locale (ex: `127.0.0.1`)

**Important :** Ne jamais commiter de `.env` dans le dépôt. Utiliser les fichiers d'exemple pour configurer le projet :

```bash
cp .env.example .env
# ou
cp .env.development .env
php artisan config:clear
```

## Architecture du projet

```plaintext
┌─────────────┐       ┌───────────────┐       ┌─────────────┐
│  Navigateur │ <---> │ Render (App)  │ <---> │ OVH Cloud   │
│ (Utilisateur)│      │ Laravel +     │       │ MySQL DB    │
│             │       │ Blade + Tailwind │     │ Managé      │
└─────────────┘       └───────────────┘       └─────────────┘
         │
         │
         ▼
  Nom de domaine OVH
(sneesaas.com redirige vers Render)
