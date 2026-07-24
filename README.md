# 🌿 GreenGoodies

Site e-commerce de produits éco-responsables développé avec **Symfony 7.4**.

## 📋 Prérequis

- PHP >= 8.2
- Composer
- MySQL / MariaDB / PostgreSQL
- Symfony CLI (recommandé)

## 🚀 Installation

```bash
# Cloner le projet
git clone <url-du-repo>
cd GreenGoodies

# Installer les dépendances
composer install

# Configurer l'environnement
cp .env .env.local
# Éditer .env.local avec vos paramètres de base de données

# Créer la base de données et exécuter les migrations
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate

# Charger les fixtures (données de démonstration)
php bin/console doctrine:fixtures:load
# Un utilisateur de test est créé automatiquement :
#   Email : test@test.com
#   Mot de passe : test

# Lancer le serveur de développement
symfony server:start
```

## 🏗️ Architecture

```
src/
├── Controller/
│   ├── HomeController.php          # Page d'accueil
│   ├── ProductController.php       # Catalogue produits
│   ├── CartController.php          # Gestion du panier
│   ├── AccountController.php       # Espace utilisateur
│   ├── RegistrationController.php  # Inscription
│   ├── SecurityController.php      # Connexion / Déconnexion
│   └── Api/
│       └── ProductController.php   # API REST produits (JWT)
├── Entity/
│   ├── User.php
│   ├── Product.php
│   ├── Category.php
│   ├── Order.php
│   └── OrderLine.php
├── Repository/
├── DataFixtures/
└── Kernel.php
```

## ✨ Fonctionnalités

- 🛍️ Catalogue de produits par catégorie
- 🛒 Gestion du panier
- 👤 Inscription / Connexion (avec Remember Me)
- 📦 Passage de commandes
- 🔐 API REST sécurisée par JWT (`/api/products`)

## 🔑 Authentification

### Web
Authentification classique via formulaire (session).

### API
Authentification stateless via **Lexik JWT** :

```bash
# Obtenir un token
curl -X POST http://localhost:8000/api/login_check \
  -H "Content-Type: application/json" \
  -d '{"username": "user@example.com", "password": "password"}'

# Utiliser le token
curl http://localhost:8000/api/products \
  -H "Authorization: Bearer <token>"
```

## 📮 Postman

Une collection et un environnement Postman sont fournis dans le dossier `postman/` pour tester l'API rapidement :

| Fichier                                      | Rôle                                          |
|----------------------------------------------|-----------------------------------------------|
| `GreenGoodies.postman_collection.json`       | Requêtes de l'API (`Auth`, `Get all products`) |
| `GreenGoodies.postman_environment.json`      | Variables d'environnement (`base_url`, `jwt_token`) |

### Utilisation

1. Importer les deux fichiers dans Postman (**Import**).
2. Sélectionner l'environnement **GreenGoodies** et renseigner `base_url` (ex. `http://localhost:8000`).
3. Lancer la requête **Auth** (`POST {{base_url}}/api/login_check`) avec les identifiants de test
   (`test@test.com` / `test`). Le token JWT est automatiquement stocké dans la variable
   `jwt_token` via le script de test.
4. Lancer **Get all products** (`GET {{base_url}}/api/products`) : le token est injecté
   automatiquement dans l'en-tête `Authorization: Bearer`.

> ℹ️ En cas d'échec d'authentification (401/403), la variable `jwt_token` est vidée
> automatiquement par sécurité.

## 🛠️ Stack technique

| Composant       | Technologie                  |
|----------------|------------------------------|
| Framework      | Symfony 7.4                  |
| ORM            | Doctrine ORM 3              |
| Template       | Twig                         |
| Assets         | Symfony Asset Mapper         |
| Auth API       | Lexik JWT Authentication     |

## 👤 Auteur

Jossy Rasoloharijaona

## 📄 Licence

Logiciel propriétaire — tous droits réservés.

© 2026 Jossy Rasoloharijaona

