# MasterCook

MasterCook est une application de démonstration Symfony conçue pour illustrer un workflow fullstack moderne : API, gestion d’entités, tests automatisés, interface utilisateur et déploiement avec Docker.

## 🚀 Objectif

- Exercice de maîtrise de Symfony 6+ et PHP 8+
- CRUD et API REST pour gestion de recettes
- Authentification et sécurité (role-based)
- Internationalisation (FR/EN/IT)

## 🧱 Technologies

- PHP 8.2+
- Symfony 6.x
- Doctrine ORM + migrations
- ApiPlatform (optionnel selon le code)
- Twig + Webpack Encore (front JS/CSS)
- PHPUnit 9.5 (tests unitaires + fonctionnels)
- PHPStan + PHP CS Fixer (qualité du code)
- Docker + Docker Compose

## ✅ Prérequis

- Docker + Docker Compose
- Node.js + npm
- Composer
- Git

## 🛠️ Installation locale (Docker)

```bash
git clone https://github.com/flo1218/mastercook symrecipe
cd symrecipe

docker compose up -d

docker exec -it symrecipe_app composer install
docker exec -it symrecipe_app npm install

docker exec -it symrecipe_app php bin/console doctrine:migrations:migrate --no-interaction
docker exec -it symrecipe_app php bin/console doctrine:fixtures:load --no-interaction

# construction assets front
docker exec -it symrecipe_app npm run build
```

## 🧪 Exécution des tests

```bash
docker exec -it symrecipe_app ./vendor/bin/phpunit -c phpunit.xml.dist
# ou via Symfony bridge (recommandé)
# docker exec -it symrecipe_app ./vendor/bin/simple-phpunit
```

## ▶️ Lancer l’application

- Accéder à `http://localhost` (selon config Nginx/Docker)
- Back-office / API selon routes définies dans `config/routes/*.yaml`

## 📄 Structure du projet

- `src/Entity` : entités Doctrine
- `src/Repository` : repos personnalisés
- `src/Controller` : contrôleurs HTTP
- `src/Service` : logique métier
- `src/Form` : formulaires Symfony
- `src/Twig` : extensions Twig
- `tests/` : tests unitaires et fonctionnels
- `config/` : configuration Symfony
- `docker/` : configs Docker (xdebug, etc.)

## 🧹 Qualité de code

- `vendor/bin/phpstan analyse` (ou `composer phpstan`) pour l’analyse statique
- `vendor/bin/php-cs-fixer fix --dry-run --diff` pour la conformité aux standards

## 🗺️ Notes spécifiques

- `phpunit.xml.dist` configure `SymfonyTestsListener` et l’environnement `APP_ENV=test`
- `translations/` gère i18n pour FR/EN/IT
- `Isbn.php` contient la logique d’import ou validation ISBN

## 🔧 Développement

- `docker exec -it symrecipe_app php bin/console cache:clear --env=dev`
- `docker exec -it symrecipe_app npm run dev -- --watch`
