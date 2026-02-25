# 🚀 Guide — Mettre le projet sur GitHub

## Étape 1 — Installer Git (si pas encore fait)
Va sur https://git-scm.com/downloads et installe Git.
Vérifie l'installation dans ton terminal :
```bash
git --version
```

---

## Étape 2 — Configurer ton identité Git (une seule fois)
```bash
git config --global user.name "Ton Nom"
git config --global user.email "ton@email.com"
```

---

## Étape 3 — Créer un dépôt sur GitHub
1. Va sur https://github.com
2. Clique sur **"New repository"** (bouton vert)
3. Nom du dépôt : `gestion-hopital` (par exemple)
4. Laisse-le en **Public** ou **Private** selon ton choix
5. **NE PAS** cocher "Initialize with README" (tu vas le faire toi-même)
6. Clique **"Create repository"**

---

## Étape 4 — Initialiser Git dans ton projet

Ouvre un terminal dans ton dossier `hopital/` :

```bash
cd C:/xampp/htdocs/hopital
git init
```

---

## Étape 5 — Créer un fichier .gitignore

Crée un fichier `.gitignore` à la racine du projet avec ce contenu :

```
# Fichiers sensibles
includes/config.php

# Dossiers inutiles
.DS_Store
Thumbs.db
*.log
```

> ⚠️ **IMPORTANT** : Ne mets JAMAIS config.php sur GitHub car il contient tes identifiants de base de données !

---

## Étape 6 — Créer un config.example.php

Crée `includes/config.example.php` (sans vraies données) pour que les autres sachent quoi remplir :

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'hopital_db');
```

---

## Étape 7 — Créer un README.md

Crée `README.md` à la racine :

```markdown
# 🏥 HôpitalSys — Gestion des Patients

Application web de gestion des patients en milieu hospitalier.

## Stack technique
- PHP 8+
- MySQL (XAMPP)
- HTML / CSS

## Installation

1. Cloner le dépôt dans htdocs/
2. Copier `includes/config.example.php` → `includes/config.php`
3. Remplir vos identifiants dans `config.php`
4. Importer `database.sql` dans phpMyAdmin
5. Accéder via http://localhost/hopital

## Identifiants de test
- Login : admin
- Mot de passe : password
```

---

## Étape 8 — Ajouter et envoyer les fichiers

```bash
# Ajouter tous les fichiers
git add .

# Premier commit
git commit -m "Initial commit — HôpitalSys"

# Lier à ton dépôt GitHub (remplace l'URL par la tienne)
git remote add origin https://github.com/TON_USERNAME/gestion-hopital.git

# Envoyer sur GitHub
git push -u origin main
```

> Si tu as une erreur avec `main`, essaie avec `master` :
> ```bash
> git push -u origin master
> ```

---

## Étape 9 — Les commandes du quotidien

Chaque fois que tu modifies des fichiers :

```bash
git add .
git commit -m "Description de ce que tu as changé"
git push
```

Pour récupérer les modifications de ton binôme :
```bash
git pull
```

---

## ✅ Résumé en 3 commandes (après la config initiale)

```bash
git add .
git commit -m "Mon message"
git push
```

C'est tout ! 🎉
