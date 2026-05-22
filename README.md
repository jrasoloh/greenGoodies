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

## 🛠️ Stack technique

| Composant       | Technologie                  |
|----------------|------------------------------|
| Framework      | Symfony 7.4                  |
| ORM            | Doctrine ORM 3              |
| Template       | Twig                         |
| Assets         | Symfony Asset Mapper         |
| Auth API       | Lexik JWT Authentication     |

## 📄 Licence

Propriétaire.

