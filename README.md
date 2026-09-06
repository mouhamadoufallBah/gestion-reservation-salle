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

# Étape 3 — Créer les modèles

### 1.Quel type de relation Eloquent avez-vous utilisé ?
J'ai utiliser une relation one to many entre salle et reservation autrement dit une salle peut etre dans reservation et un reservation appartient a une salle.
Ce dernier a ete materialise comme suit:

- Dans le model salle on a une methode `reservations` qui utilise la methode du class model qu'on a herite `hasMany(Reservation::class)` 
- Dans le model reservation on a une methode `salle` qui utilise methode du class model qu'on a herite `belongTo(Salle::class)`.

---

### 2.Pourquoi déclarer $fillable ou $guarded ?

Cela permet d'indiquer explicitement les champs qu'Eloquent est autorisé à remplir automatiquement pour éviter qu'un champ sensible, comme `id`, soit modifié involontairement.

---

### 3.Pourquoi convertir active en booléen ?

Dans la base de données, la valeur peut être représentée par 0 ou 1.

---

### 4.Pourquoi convertir les dates en objets ?

Pour pouvoir utiliser les fonctionnalités de manipulation des dates plutôt que de travailler avec de simples chaînes de caractères.

---

# Étape 4 — Ajouter les données initiales

### 1.Quelle différence existe entre migration et seeder ?

La migration permet de creer ou de modifier la structure de la base de donne tandis que les seeder permet d'inserer des ligne dans le table de la base de donne.

---

### 2.Pourquoi les données initiales doivent-elles être reproductibles ?

Les données initiales doivent être reproductibles pour pouvoir exécuter plusieurs fois le seeder sans créer de données incorrectes ou de doublons.

C'est particulièrement utile lorsqu'on :
- installe le projet sur une nouvelle machine ;
- recree la base de donnees ;
- travaille en equipe ;
- veut reinitialiser rapidement les donnees.

---

### 3.Comment empêcher les doublons ?

On peut mettre une collone a unique ou utiliser UpdateOrInsert() qui verifie l'existance pour savoir s'il doit cree ou modifie.
---

# Étape 5 — Créer la validation

### 1.Pourquoi séparer la validation syntaxique des règles métier ?

On les separer pour appliquer le principe `SRP` la validation verifie unique si les donnee sont valide on les regle metier concerne le fonctionnement de l'application

---

### 2.Pourquoi créer une interface de validation ?

On cree l'interface pour que tout nos validators aient une meme methode qui change de comportement

---

### 3.Pourquoi le validateur ne doit-il pas enregistrer les données ?

Parce que sont role est de veiller a la coherence des donnees pas plus. il y a une autre couche qui se charge de ca.

---

### 4.Comment retourner plusieurs erreurs en une seule fois ?

On le stock dans un tableau associatif

---

# Étape 6 — Créer les objets de transport

### 1.Quelle différence existe entre DTO et modèle Eloquent ?

Le DTO veille a que le service ait les donnees qu'il faut lors d'une operation de mis a jours ou de recuperation .

---

### 2.Pourquoi le DTO ne doit-il pas appeler save() ?

Parce qu'il ne gere pas insertion a la base il veille que le service aient tout les donnees qu'il faut pour utiliser la methode save du repository .

---

### 3.À quel moment transforme-t-on les chaînes en dates ?

C'est au niveau du DTO.

---

### 4.Le DTO doit-il contenir la règle de chevauchement ?

Non car le chevauchement est un regle metier.

---

# Étape 7 — Créer l’accès aux données

### 1.Eloquent constitue-t-il déjà un accès aux données ?

Oui eloquent a access a notre base de doone

---

### 2.Pourquoi ajouter un Repository au-dessus d’Eloquent ?

Pour Isoler du reste de l'aplication

---

### 3.Cette abstraction est-elle toujours nécessaire ?

C'est pas neccessaire on peut utiliser eloquent dans le service ou meme le controller mais pour une bonne evolutivite de l'application mieux vaut l'isoler. Car si demain on devait changer d'orm on touchera ni au service ni au controller mais au repository seulement.

---

### 4.Quel avantage apporte-t-elle ?

Il rend notre application beaucoup plus flexible.

---






