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