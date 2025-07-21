# Morpion Multijoueur Temps Réel

Un jeu de morpion (Tic-Tac-Toe) multijoueur en temps réel avec gestion des rooms, attribution automatique des rôles X/O, chat et synchronisation des coups, développé en **Vue.js** (frontend), **Symfony** (backend), **Node.js WebSocket** (serveur temps réel), le tout orchestré avec **Docker**.

---

## ✨ Fonctionnalités

- Création et gestion de rooms
- Attribution automatique des rôles X et O
- Démarrage automatique de la partie dès que deux joueurs sont présents
- Jeu de morpion multijoueur en temps réel (WebSocket)
- Chat intégré à la room
- Affichage en temps réel de l'état des joueurs (prêt/en attente)
- Authentification sécurisée avec JWT
- Interface responsive et moderne (Tailwind CSS)
- Administration de la base de données via phpMyAdmin

---

## 🛠️ Stack technique

- **Frontend** : Vue.js 3, Pinia, Tailwind CSS
- **Backend** : Symfony, PHP 8.2
- **WebSocket Server** : Node.js 20
- **Base de données** : MySQL 8.0
- **phpMyAdmin** : gestion BDD
- **Docker** : orchestration complète

---

## 🚀 Prérequis

- Docker et Docker Compose

---

## ⚡ Installation

1. Clonez le projet :

   git clone <https://github.com/ThysmaJS/Roomies.git>
   cd <Roomies>

2. Lancez l’application avec Docker Compose :

   docker compose up --build

   Les services accessibles :

   - Frontend Vue : http://localhost:5173
   - Backend Symfony : http://localhost:8000
   - phpMyAdmin : http://localhost:8080
   - WebSocket Server : ws://localhost:3001
   - BDD MySQL : localhost:3306 (user : symfony / password : symfony)

3. (Facultatif) Initialisez la base de données Symfony :

   docker exec -it symfony_backend bash
   php bin/console doctrine:migrations:migrate

4. (Facultatif) Installez les dépendances manuellement (hors Docker) :

   - Frontend :

     cd frontend
     npm install
     npm run dev

   - Backend :

     cd backend
     composer install
     php -S 0.0.0.0:8000 -t public

   - WebSocket :

     cd websocket
     npm install
     node server.js

---

## ⚙️ Variables d’environnement

Adaptez le `.env` du backend (Symfony) si besoin :

DATABASE_URL="mysql://symfony:symfony@db:3306/symfony"

---

## 🕹️ Utilisation

- Créez un compte ou connectez-vous.
- Rejoignez ou créez une room.
- Dès 2 joueurs présents : la partie démarre.
- Plateau et chat en temps réel.

---

## 🗄️ Accès à la base de données

- phpMyAdmin : http://localhost:8080  
  Utilisateur : root  
  Mot de passe : root  
  Hôte : db

---

## 🐳 Architecture Docker

backend     # Symfony API  
frontend    # Vue.js + Tailwind CSS  
websocket   # Serveur WebSocket Node.js  
docker-compose.yml
