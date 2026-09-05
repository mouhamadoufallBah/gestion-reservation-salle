# Étape 1 — Initialiser le projet Composer

### 1. Quel est le rôle de Composer ?

Composer est un gestionnaire de dépendances pour PHP.  
Il permet de gérer les bibliothèques et les dépendances utilisées dans notre projet.

---

### 2. Quelle différence existe entre `require` et `require-dev` ?

- **`require`** : contient les dépendances nécessaires au fonctionnement du projet. Elles sont installées en développement et en production.

- **`require-dev`** : contient les dépendances utilisées uniquement pendant le développement, comme les outils de test ou de débogage. Elles ne sont généralement pas installées en production.

---

### 3. Pourquoi faut-il versionner `composer.lock` ?

Il faut versionner `composer.lock` afin que tous les collaborateurs utilisent exactement les mêmes versions des dépendances.

Lorsqu’un collaborateur clone le projet et exécute :

```bash
composer install
```
---

### 4. Pourquoi ne versionne-t-on pas `vendor/`?

Le dossier `vendor/` contient le code source des dépendances installées par Composer.

On ne le versionne généralement pas pour plusieurs raisons :
- Il peut contenir beaucoup de fichiers et augmenter considérablement la taille du dépôt.
  
- Les dépendances peuvent être facilement réinstallées avec Composer.
  
- Il est inutile de stocker dans Git du code provenant de bibliothèques externes.

# Étape 2 — Configurer Eloquent

### 1.Quel rôle joue Capsule\Manager ?

`Capsule\Manager` nous permet d'utiliser l'orm eloquent en dehors de laravel. 
il nous permet:
  - de configuerer la connexion a la base de donnee
  - et nous permet d'utiliser de communiquer avec notre base de donner sans ecrire de requette.

--- 

### 2.Pourquoi Eloquent peut-il fonctionner sans Laravel ?

`Eloquent` peut fonctionner sans Laravel parce qu'il est disponible sous forme de composant indépendant.

---

### 3.Où doit se trouver le démarrage de l’ORM ?

Le démarrage de `l'ORM `doit se trouver dans la partie initialisation de l'application, et non dans les classes métier.

---

### 4.Quelle différence existe entre ORM et SQL écrit à la main ?

Avec du `SQL` on écrit directement les requêtes tandis qu'avec un `ORM` on manipule les données à travers des `objets` et des `méthodes` mais en arriere plan `l'orm` se charge de générer et d'exécuter la requête SQL correspondante.

---

