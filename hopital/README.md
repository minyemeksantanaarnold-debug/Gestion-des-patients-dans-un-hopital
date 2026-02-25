# 🏥 HôpitalSys — Gestion des Patients

Application web de gestion des patients en milieu hospitalier développée en PHP / MySQL.

## Fonctionnalités
- Connexion sécurisée pour le personnel
- Enregistrement et gestion des patients
- Création automatique du dossier médical
- Suivi des consultations par médecin
- Gestion des ordonnances
- Répertoire des médecins (internes et externes)
- Tableau de bord avec statistiques

## Stack technique
- **Backend** : PHP 8+
- **Base de données** : MySQL (via XAMPP)
- **Frontend** : HTML5 / CSS3 vanilla

## Installation

1. Copier le dossier `hopital/` dans `C:/xampp/htdocs/`
2. Démarrer Apache et MySQL dans XAMPP
3. Ouvrir phpMyAdmin : http://localhost/phpmyadmin
4. Créer une base `hopital_db` et importer `database.sql`
5. Copier `includes/config.example.php` → `includes/config.php`
6. Accéder à l'app : http://localhost/hopital

## Identifiants de test
- **Login** : `admin`
- **Mot de passe** : `password`

## Structure du projet
```
hopital/
├── index.php              # Page de connexion
├── logout.php             # Déconnexion
├── database.sql           # Script de création BDD
├── includes/
│   ├── config.php         # Connexion BDD (ne pas commit)
│   ├── config.example.php # Template config
│   ├── head_styles.php    # CSS global
│   ├── sidebar.php        # Navigation
│   └── topbar.php         # Barre supérieure
└── pages/
    ├── dashboard.php      # Tableau de bord
    ├── patients.php       # Liste patients
    ├── ajouter_patient.php # Formulaire patient
    ├── dossier.php        # Dossier médical complet
    └── medecins.php       # Gestion médecins
```
