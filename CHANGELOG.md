# Journal des modifications (Changelog)

Toutes les modifications notables apportées à ce projet sont documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/)
et ce projet adhère à la spécification [Semantic Versioning](https://semver.org/lang/fr/).

---

## [Unreleased]

### Ajouté
- **Pagination dynamique** :
  - Création de `PaginationDTO` pour encapsuler le calcul des pages, limites, décalages et URLs avec filtres conservés.
  - Pagination sur la liste des salles et la liste des réservations avec navigation (Précédent, Suivant, numéros de pages).
  - Styles CSS personnalisés pour les composants de pagination (`.pagination`, `.pagination-item`, `.pagination-info`).
- **Recherche multicritère** :
  - Filtrage des salles par mot-clé (nom ou bâtiment), type de salle, capacité minimale et statut (active/inactive).
  - Filtrage des réservations par salle, responsable/email, motif, intervalle de dates (`dateDebut`, `dateFin`) et statut (`confirmée`, `annulée`).
  - Formulaire de recherche stylisé avec conservation des filtres et bouton de réinitialisation.
- **Transactions de base de données (ACID)** :
  - Encapsulation des opérations de réservation et d'annulation dans des transactions Eloquent (`Capsule::getConnection()->transaction(...)`).
  - Détection et rejet atomique des conflits de réservation pour prévenir les conflits d'accès concurrents.
- **Diagramme de classes UML** :
  - Création du fichier `DIAGRAMME_CLASSES.md` avec diagramme Mermaid complet modélisant les modèles, enums, DTOs, repositories, services, validateurs et contrôleurs.
- Tests unitaires pour `PaginationDTO` (`tests/Unit/DTO/PaginationDTOTest.php`).

---

## [0.12.1] - 2026-09-08

### Ajouté
- Mise en forme CSS complète via `public/assets/css/style.css` reliée au layout global `templates/layout/base.html.php`.
- Composants d'interface : cartes, tables modernes, badges de statut colorés, alertes et états vides.
- `FlashService` pour la gestion des notifications éphémères en session (succès, avertissement, erreur, information).
- Page d'erreur personnalisée HTTP 500 (`templates/errors/500.html.php`) et amélioration des vues d'erreurs 404 et 405.
- Tests unitaires pour `FlashService` (`tests/Unit/Service/FlashServiceTest.php`).

### Modifié
- Contrôleurs `SalleController` et `ReservationController` :
  - Rendu des formulaires avec conservation intégrale des données saisies en cas d'erreur de validation.
  - Affichage des messages d'erreurs globaux et sous chaque champ invalide.
  - Redirections avec messages flash de succès.
  - Interception des exceptions métier `SalleIntrouvableException`, `ReservationIntrouvableException`, `SalleIndisponibleException`.
- `Application.php` : gestion centralisée des exceptions d'exécution avec interception 404, 405 et 500.

### Corrigé
- Suppression du code de débogage `var_dump()` et `die;` dans `ReservationController::cancel()`.
- Correction de l'ordre de validation dans `ReservationController::store()` pour éviter un crash lors de la construction de `DateTimeImmutable` sur des dates invalides.

---

## [0.12.0] - 2026-09-08

### Ajouté
- Configuration de l'environnement de conteneurisation Docker avec `docker-compose.yml`.
- Images Docker pour PHP (PHP-FPM) et Nginx.
- Workflows GitHub Actions pour l'intégration continue et le déploiement sur Docker Hub.

---

## [0.11.0] - 2026-09-07

### Ajouté
- Suite de tests avec PHPUnit 12.
- Tests unitaires métier pour `CreerReservationService`.
- Faux dépôts en mémoire (`FakeSalleRepository`, `FakeReservationRepository`) pour tester les services sans dépendre de MySQL.
- Tests d'intégration pour `SalleRepository` et `ReservationRepository`.

---

## [0.10.0] - 2026-09-07

### Ajouté
- Conteneur d'injection de dépendances (DI) avec la bibliothèque PHP-DI 7.
- Configuration centralisée des définitions dans `config/container.php` avec autowiring.
- Point d'entrée `public/index.php` instanciant l'application via le conteneur.

---

## [0.9.1] - 2026-09-06

### Corrigé
- Ajustement des routes et des handlers FastRoute dans `routes/web.php`.
- Gestion des espaces de noms (namespaces) dans les contrôleurs.

---

## [0.9.0] - 2026-09-06

### Ajouté
- Moteur de routage HTTP avec Nikic/FastRoute.
- Définition des routes web pour les salles et les réservations (`GET` et `POST`).
- Contrôleurs web `SalleController` et `ReservationController`.
- Moteur de rendu de vues `App\View\View` avec système de layouts.
- Templates HTML/PHP pour les opérations CRUD sur les salles et réservations.

---

## [0.8.0] - 2026-09-06

### Ajouté
- Couche de services applicatifs et métier :
  - `CreerReservationService` avec règles de non-chevauchement, durée maximale (4h) et date future.
  - `AnnulerReservationService`.
  - `AfficherReservationService` et `ListerReservationsService`.
  - `CreerSalleService`, `ModifierSalleService`, `AfficherSalleService`, `ListerSallesService`.
- Exceptions personnalisées : `SalleIndisponibleException`, `SalleIntrouvableException`, `ReservationIntrouvableException`.

---

## [0.7.0] - 2026-09-06

### Ajouté
- Abstraction de l'accès aux données avec le patron Repository :
  - `SalleRepositoryInterface` et implémentation Eloquent `SalleRepository`.
  - `ReservationRepositoryInterface` et implémentation Eloquent `ReservationRepository`.
- Méthodes de détection de chevauchement de créneaux `rechercherConflit()`.

---

## [0.6.0] - 2026-09-06

### Ajouté
- Objets de transfert de données (DTO) pour découpler la couche présentation du domaine :
  - `CreerSalleDTO` et `ModifierSalleDTO`.
  - `SalleDetailDTO` et `SalleListeDTO`.
  - `CreerReservationDTO` et `AnnulerReservationDTO`.
  - `ReservationDetailDTO` et `ReservationListeDTO`.

---

## [0.5.0] - 2026-09-05

### Ajouté
- Validation des entrées avec `Respect/Validation`.
- `ValidatorInterface` et classe de résultat `ValidationResult`.
- Validateurs dédiés : `SalleValidator`, `ReservationValidator`, `AnnulationReservationValidator`.

---

## [0.4.0] - 2026-09-05

### Ajouté
- Script de migration de base de données `database/Migration.php`.
- Script de peuplement de données reproductible `database/Seed.php`.
- Outil en ligne de commande CLI `app` pour exécuter `php app migrate` et `php app seed`.

---

## [0.3.0] - 2026-09-05

### Ajouté
- Modèles Eloquent `App\Model\Salle` et `App\Model\Reservation`.
- Relation Eloquent One-to-Many entre `Salle` (`hasMany`) et `Reservation` (`belongsTo`).
- Énumérations typées `TypeSalleEnum` et `StatutReservationEnum`.

---

## [0.2.0] - 2026-09-05

### Ajouté
- Configuration de l'ORM Illuminate Database (Eloquent) en dehors de Laravel via `Capsule\Manager`.
- Configuration de connexion à la base de données via variables d'environnement (`.env`).
- Classe utilitaire `App\Repository\Database` pour la gestion de l'instance et de la base.

---

## [0.1.0] - 2026-09-05

### Ajouté
- Initialisation du projet avec Composer.
- Autoloading PSR-4 pour les espaces de noms `App\` et `Database\`.
- Dépendances initiales (`illuminate/database`, `respect/validation`, `vlucas/phpdotenv`).
