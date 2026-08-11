<div align="center">

# SFBB — Symfony Bulletin Board

**Une reproduction moderne de phpBB construite avec Symfony.**

![PHP](https://img.shields.io/badge/PHP-8.5-777BB4?logo=php&logoColor=white)
![Symfony](https://img.shields.io/badge/Symfony-8.1-000000?logo=symfony&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16-336791?logo=postgresql&logoColor=white)
![Docker](https://img.shields.io/badge/Docker-Compose-2496ED?logo=docker&logoColor=white)
![Tests](https://img.shields.io/badge/Tests-PHPUnit%2012-DB7093?logo=phpunit&logoColor=white)
![License](https://img.shields.io/github/license/lsoulier42/sfbb)

</div>

Forum de discussion complet calqué sur le modèle de phpBB : hiérarchie
**Catégorie → Forum → Sujet → Message**, messagerie instantanée, messages
directs, modération et panneau d'administration. Le tout dans un stack Docker
prêt à l'emploi.

---

## Table des matières

- [Fonctionnalités](#fonctionnalités)
- [Stack technique](#stack-technique)
- [Prérequis](#prérequis)
- [Installation](#installation)
- [Démarrage rapide](#démarrage-rapide)
- [Commandes utiles](#commandes-utiles)
- [Configuration](#configuration)
- [Base de données](#base-de-données)
- [Comptes de démonstration](#comptes-de-démonstration)
- [Emails et notifications](#emails-et-notifications)
- [Frontend et assets](#frontend-et-assets)
- [Tests](#tests)
- [Qualité de code](#qualité-de-code)
- [Structure du projet](#structure-du-projet)
- [Rôles et permissions](#rôles-et-permissions)
- [Dépannage](#dépannage)
- [Contribuer](#contribuer)
- [Licence](#licence)

## Fonctionnalités

- **Forum hiérarchique** : catégories, forums, sujets et messages
  (hiérarchie identique à phpBB).
- **Pagination** des sujets et des messages (Pagerfanta).
- **Éditeur riche** : CKEditor intégré pour la rédaction des messages.
- **Modération** : ordonnancement des catégories/forums, gestion des
  modérateurs par forum, profil des membres.
- **Panneau d'administration** : configuration du site (nom, description),
  gestion complète des catégories, forums, modérateurs et utilisateurs.
- **Membres** : inscription, connexion par nom d'utilisateur, profil public,
  liste des membres filtrable.
- **Messagerie** : chat avec participants et messages directs.
- **« Qui est en ligne »** : activité des utilisateurs sur les 5 dernières
  minutes.
- **Fil d'Ariane** dynamique selon la page visitée.
- **Notifications par email** : envoi asynchrone via Messenger (Doctrine).
- **Interface 100 % française** et locale `fr` par défaut.

## Stack technique

| Technologie        | Version  |
| ------------------ | -------- |
| PHP                | 8.5      |
| Symfony            | 8.1      |
| Doctrine ORM       | 3.x      |
| PostgreSQL         | 16       |
| Nginx              | 1.23     |
| Twig               | 3.x      |
| AssetMapper        | 8.x      |
| Sass (scssphp)     | 0.10     |
| CKEditor (FOS)     | 2.8      |
| Stimulus           | 3.x      |
| Pagerfanta         | 4.6      |
| PHPUnit            | 12       |
| PHPStan            | 2.x      |

## Prérequis

- [Docker](https://docs.docker.com/engine/install/) (20.10 ou plus)
- [Docker Compose](https://docs.docker.com/compose/install/) v2
- **Aucune installation PHP locale** : tout le tooling tourne dans les
  conteneurs.

## Installation

```bash
git clone git@github.com:lsoulier42/sfbb.git
cd sfbb
cp .env .env.local   # facultatif : personnaliser les variables (cf. Configuration)
make install
```

`make install` effectue, dans l'ordre :

1. le build des images Docker (PHP 8.5, Nginx, PostgreSQL, Mailcatcher) ;
2. l'installation des dépendances Composer ;
3. l'exécution des migrations de base de données ;
4. le chargement des fixtures ;
5. l'installation des assets (importmap, Sass, CKEditor) ;
6. le démarrage de la stack.

## Démarrage rapide

| Commande            | Description                                            |
| ------------------- | ------------------------------------------------------ |
| `make start`        | Démarre tous les conteneurs                            |
| `make stop`         | Arrête tous les conteneurs                             |
| `make connect`      | Ouvre un shell dans le conteneur PHP                   |
| `make install`      | Build + composer install + migrations + fixtures + assets |
| `make composer-install` | Installe les dépendances Composer                 |
| `make composer-update`  | Met à jour les dépendances Composer (`-W`)         |
| `make db-migrate`   | Applique les migrations                                |
| `make db-fixtures`  | Recharge les fixtures (purge + truncate)               |
| `make db-migrations-diff` | Génère une migration depuis les entités        |
| `make db-reset`     | ⚠️ Destructif : supprime les migrations, recrée la base |
| `make clear`        | Vide le cache (à lancer hors conteneur)                |
| `make assets-install`   | Installe les assets (importmap)                    |
| `make assets-compile`   | Compile les assets pour la production              |

> ⚠️ **`make db-reset` est destructif** : il supprime *tous* les fichiers de
> `migrations/`, recrée la base de données et regénère un diff complet.
> N'exécutez cette commande que si vous en avez clairement l'intention.

### URL par défaut

| Service              | URL / port                          |
| -------------------- | ----------------------------------- |
| Application (dev)    | http://localhost:8776               |
| PostgreSQL (host)    | `localhost:5664`                    |
| Mailcatcher (UI)     | http://localhost:1180               |
| Mailcatcher (SMTP)   | `localhost:1125`                    |

## Configuration

Toutes les variables d'environnement sont définies dans [`.env`](.env).
Pour personnaliser sans versionner vos modifications, créez un fichier
`.env.local` (celui-ci est ignoré par git).

| Variable                    | Description                                 | Défaut              |
| --------------------------- | ------------------------------------------- | ------------------- |
| `APP_ENV`                   | Environnement (`dev`, `prod`, `test`)       | `dev`               |
| `APP_VERSION`               | Version affichée dans l'application         | `0.1.0`             |
| `APP_PORT`                  | Port HTTP exposé sur l'hôte                 | `8776`              |
| `APP_SECRET`                | Secret de l'application                     | *(à changer en prod)* |
| `DATABASE_USER`             | Utilisateur PostgreSQL                      | `root`              |
| `DATABASE_PASSWORD`         | Mot de passe PostgreSQL                     | `password`          |
| `DATABASE_NAME`             | Nom de la base                              | `sfbb`              |
| `DATABASE_HOST`             | Hôte PostgreSQL (nom de service Docker)     | `database`          |
| `DATABASE_PORT`             | Port interne PostgreSQL                     | `5432`              |
| `MESSENGER_TRANSPORT_DSN`   | Transport async Messenger (Doctrine)        | `doctrine://default`|
| `MAILER_DSN`                | Transport mailer                            | `smtp://mailer:1025`|

## Base de données

- Les migrations sont dans [`migrations/`](migrations/).
- Génération d'une migration depuis les entités :

  ```bash
  make db-migrations-diff
  ```

- Application des migrations :

  ```bash
  make db-migrate
  ```

- Les fixtures (`src/DataFixtures/AppFixtures.php`) créent un jeu de données
  réaliste : utilisateurs, catégories, forums, sujets, messages, chans, etc.

### Tests

Une base de données séparée est utilisée en environnement de test (suffixe
`_test` ajouté par la configuration `when@test` de Doctrine). Aucune collision
avec vos données de développement.

## Comptes de démonstration

Les fixtures créent les comptes suivants (mot de passe commun : `bidule`) :

| Nom d'utilisateur | Rôle                  |
| ----------------- | --------------------- |
| `louise`          | Administrateur        |
| `jean-marc`       | Super-modérateur      |
| `jean-michel`     | Modérateur            |
| *(10 générés)*    | Utilisateur           |

> ⚠️ Ces comptes sont destinés au développement uniquement. Changez toujours
> les mots de passe par défaut en production.

## Emails et notifications

- Les emails sont envoyés **de manière asynchrone** via le transport
  `async` de Messenger (Doctrine) puis traités par les workers Supervisor
  (`mailer-messenger` et `failed-messenger`) dans le conteneur PHP.
- En développement, tous les emails transitent par **Mailcatcher**
  (http://localhost:1180) : aucun email réel n'est envoyé.
- En cas d'échec, les messages sont redirigés vers le transport `failed`
  (3 tentatives avec backoff).

## Frontend et assets

- **AssetMapper** (`importmap.php`) gère les dépendances JavaScript
  (importmap natif, aucun bundler).
- Les styles sont compilés depuis `assets/styles/app.scss` via
  **symfonycasts/sass-bundle**.
- Le point d'entrée JavaScript est `assets/app.js`.
- **CKEditor** est intégré via FOSCKEditorBundle ; ses plugins sont dans
  `public/ckeditor-plugins/`.
- Après toute modification de styles/JS :

  ```bash
  make assets-install
  ```

- Pour la production, les assets sont compilés dans `public/build` :

  ```bash
  make assets-compile
  ```

## Tests

L'infrastructure de test est en place (PHPUnit 12, `tests/bootstrap.php`)
mais **aucun test n'est encore écrit**. Pour lancer la suite :

```bash
docker compose run --rm php bin/phpunit
```

L'ajout de tests (unitaires et fonctionnels) est bienvenu — voir
[Contribuer](#contribuer).

## Qualité de code

Les outils d'analyse sont exécutés **dans le conteneur Docker** :

```bash
# PHPStan — niveau 8 sur src/ (nécessite le cache dev warmé)
docker compose run --rm php bash -ci 'php -d memory_limit=4G bin/console cache:clear'
docker compose run --rm php bash -ci 'php -d memory_limit=4G vendor/bin/phpstan analyse -c phpstan.neon --no-progress'

# PHPCS — PSR12 sur src/ et tests/
docker compose run --rm php bash -ci 'vendor/bin/phpcs --standard=phpcs.xml.dist src/ tests/'
```

## Structure du projet

```
.
├── assets/                  # JS (app.js) et SCSS (app.scss)
├── config/                  # Configuration Symfony
│   ├── packages/            #   Framework, Doctrine, Security, Twig, ...
│   └── routes/              #   Routes framework/profiler
├── docker/                  # Dockerfile PHP, configs nginx et supervisord
├── migrations/              # Migrations Doctrine
├── public/                  # Point d'entrée web + assets publics (ckeditor-plugins)
├── src/
│   ├── Contract/            # Interfaces des services
│   ├── Controller/          # Contrôleurs (front + admin)
│   ├── DataFixtures/        # Fixtures
│   ├── Dto/                 # DTOs (formulaires, pagination, vues)
│   ├── Entity/              # Entités Doctrine
│   ├── Enum/                # Énums (rôles, ordre)
│   ├── EventListener/       # Écouteurs (activité, connexion, mot de passe)
│   ├── Form/                # Types de formulaires
│   ├── Helper/              # Helpers
│   ├── Repository/          # Repositories
│   ├── Security/            # UserChecker (connexion par username)
│   ├── Service/             # Couche métier
│   ├── Traits/              # Traits transverses (timestampable)
│   └── Twig/                # Extension Twig
├── templates/               # Vues Twig
├── tests/                   # Tests (PHPUnit)
├── translations/            # Traductions (fr)
├── docker-compose.yaml      # Stack Docker (php, nginx, database, mailer)
└── Makefile                 # Workflow principal (make install, start, ...)
```

## Rôles et permissions

| Rôle                    | Droits                                                      |
| ----------------------- | ----------------------------------------------------------- |
| `ROLE_USER`             | Consulter, poster et éditer ses messages                    |
| `ROLE_MODERATOR`        | Modération basique                                          |
| `ROLE_SUPER_MODERATOR`  | Modération étendue                                          |
| `ROLE_ADMIN`            | Accès complet au panneau `/admin`                           |

La hiérarchie des rôles (`role_hierarchy`) est définie dans
[`config/packages/security.yaml`](config/packages/security.yaml). La connexion
se fait **par nom d'utilisateur** (cf. `App\Security\UserChecker`).

## Dépannage

**Le port 1125/1180 est déjà utilisé (Mailcatcher)**
: un autre projet occupe le port. Arrêtez l'autre conteneur ou modifiez le
mapping de ports du service `mailer` dans `docker-compose.yaml`.

**Les fixtures échouent avec une violation de contrainte sur `id`**
: le schéma n'est pas à jour. Appliquez les migrations
(`make db-migrate`) ou réinitialisez la base (`make db-reset`).

**Erreur lors de l'analyse PHPStan**
: le cache dev doit être warmé (voir [Qualité de code](#qualité-de-code)).

## Contribuer

1. Forkez le projet et créez une branche dédiée.
2. Respectez la qualité de code (PHPStan niveau 8 + PSR12) avant de soumettre.
3. Les messages de commit sont rédigés en français.
4. L'UI, les traductions et les fixtures sont en français.

Toute contribution — code, tests, documentation — est la bienvenue.

## Licence

Distribué sous licence [MIT](LICENSE).
