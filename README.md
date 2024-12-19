# Gestion des Factures et Clients

Ce projet est une application Symfony permettant de gérer des factures et des clients. Il inclut des fonctionnalités telles que la création, la modification, la suppression, et la recherche.

---

## **Installation**

1. **Clonez le projet depuis le dépôt Git** :
   ```bash
   git clone <URL_DU_DEPOT>
   cd <NOM_DU_REPERTOIRE>
   code .

2. Installez les dependances :
   composer install

3. Modification du .env :
  (Remplacer la ligne ci-dessous avec vos informations pour acceder à votre base de donnée) 
   ligne 29 : DATABASE_URL="mysql://root:root@127.0.0.1:8889/hackathon?serverVersion=5.7.39&charset=utf8mb4"
   
5. Faites les migrations vers la base de donnée :
    php bin/console doctrine:database:create
    php bin/console doctrine:migrations:migrate

6. Installez les assets front-end :
    npm install
    npm run build

7. Demarrer le serveur :
   symfony server:start

\\ la page client se trouve sur le "/client" et la page facture sur le "/facture" 


   
   
