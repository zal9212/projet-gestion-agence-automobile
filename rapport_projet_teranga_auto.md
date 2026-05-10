# Rapport de Projet : Teranga Auto ERP
## Solution Intégrée de Gestion de Location de Véhicules

---

## 1. Introduction
**Teranga Auto ERP** est une plateforme web moderne conçue pour automatiser les opérations d'une agence de location de voitures. L'objectif est de simplifier l'expérience client tout en offrant des outils de pilotage puissants pour les administrateurs et agents.

---

## 2. Stack Technique
Le projet utilise des technologies stables et performantes pour garantir une compatibilité maximale :
*   **Backend** : PHP 8.2 (Architecture modulaire procédurale).
*   **Base de Données** : MySQL / MariaDB (Optimisée avec index et clés étrangères).
*   **Frontend** : HTML5, CSS3 (Glassmorphism), JavaScript (Vanilla).
*   **Framework CSS** : Bootstrap 5.3 (Pour le responsive).
*   **Icônes** : FontAwesome 6.

---

## 3. Analyse des Fonctionnalités & Captures

### A. Interface Client (Front-Office)

#### 1. Page d'Accueil & Hero Section
Une section immersive avec une vidéo ou image haute résolution, présentant l'agence et les offres premium.
> **[INSÉRER CAPTURE : Page d'accueil - Hero Section Desktop]**
> **[INSÉRER CAPTURE : Page d'accueil - Vue Mobile]**

#### 2. Moteur de Recherche Multicritère
Barre de recherche permettant de filtrer par dates, marque et modèle de véhicule.
> **[INSÉRER CAPTURE : Barre de recherche interactive]**

#### 3. Catalogue et Favoris
Grille de véhicules élégante avec système de mise en favoris instantané (AJAX-like).
> **[INSÉRER CAPTURE : Grille des véhicules]**

---

### B. Processus de Réservation

#### 1. Sélection et Devis
Le client choisit ses dates. Le système calcule automatiquement le prix total en fonction de la durée.
> **[INSÉRER CAPTURE : Formulaire de réservation et calcul du prix]**

#### 2. Confirmation et Signature
Signature électronique sur écran pour valider le contrat de location.
> **[INSÉRER CAPTURE : Module de signature électronique]**

---

### C. Interface Administration (Back-Office)

#### 1. Tableau de Bord (Dashboard)
Vue d'ensemble du Chiffre d'Affaire, de la taille de la flotte et des alertes de maintenance (assurances).
> **[INSÉRER CAPTURE : Dashboard Administrateur]**

#### 2. Planning de Flotte (Gantt)
Visualisation graphique de l'occupation des véhicules sur le mois.
> **[INSÉRER CAPTURE : Planning Gantt Interactif]**

#### 3. Gestion des Véhicules (CRUD)
Interface complète pour ajouter, modifier ou supprimer des véhicules avec sélecteur de logos.
> **[INSÉRER CAPTURE : Formulaire d'ajout de véhicule avec logos]**

#### 4. Gestion des Réservations & Contrats
Validation des dossiers et génération de contrats d'impression professionnels.
> **[INSÉRER CAPTURE : Liste des réservations et bouton export]**
> **[INSÉRER CAPTURE : Rendu du contrat de location final]**

---

## 4. Architecture de la Base de Données
Le système repose sur un schéma relationnel de 6 tables principales garantissant une intégrité totale des données.
> **[INSÉRER CAPTURE : Structure des tables dans PHPMyAdmin]**

---

## 5. Sécurité et Performances
*   **Injections SQL** : Toutes les requêtes sont préparées (PDO).
*   **RGPD / Confidentialité** : Hachage des mots de passe et restriction d'accès aux employés (masquage du CA).
*   **Vitesse** : Optimisation des images et minification des scripts pour un chargement rapide au Sénégal.

---

## 6. Conclusion
Teranga Auto ERP représente une avancée majeure pour l'agence, permettant une transition numérique complète avec un outil sur mesure, esthétique et sécurisé.

---
**Développé par Antigravity pour Teranga Auto**
