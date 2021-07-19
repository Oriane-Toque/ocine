# MCD et SQL

## Contexte

Nous souhaitons réaliser un site qui recense des **films**, les **genres** de films, les **acteurs** et leur **rôle** dans chaque film. Les **équipes de tournage** devront également apparaitre pour chaque film, une **personne** pouvant travailler sur un film selon son **métier** (scénariste, chef décorateur, producteur, compositeur, etc.), dans **un secteur d'activité** donné (scénario, bande son, équipe technique, production, etc.). Des **utilisateurs** pourront se connecter au site afin de poster **des critiques** de films ou d'administrer les données du site.

Voici les infos obtenues suite au brief du projet :

**Sprint 1 (à traiter aujourd'hui)**

- Un film peut appartenir à plusieurs genres et vice-versa.
- Un acteur peut jouer dans plusieurs films et avoir un à plusieurs rôles par film.
    - Un film accueille plusieurs acteurs.
- Des critiques pourront être postés par les utilisateurs sur chaque film.

**Sprint 2 (à traiter dans les jours à venir)**

- Une personne peut travailler sur un film selon son métier.
- Un secteur d'activité contient plusieurs métiers.
    - Un métier n'appartient qu'à un seul secteur d'activité.
- Les utilisateurs du site auront un rôle donné (utilisateur ou administrateur).

## MCD

Dans un premier temps nous allons nous concentrer sur le **Sprint 1**. Vous devez concevoir le MCD correspondant :

**Etape 1**

- Créer les entités.
    - Identifier les attributs de chaque entité (1 à 3 maximum pour aller au plus simple).

A faire avec papier/crayon ou avec [MoCoDo](http://mocodo.wingi.net/).

**Correction possible**

<details>
    <summary>SPOILER! Ne cliquer qu'après avoir essayé ;)</summary>

```
MOVIE: movie title, release date, duration
GENRE: genre name
PERSON: person name, birth date
REVIEW: content, rating, published date
USER: name, email
```

A visualiser avec [MoCoDo](http://mocodo.wingi.net/)

</details>

**Etape 2**

- Nommer les relations qui existent entre les entités.

**Etape 3**

- Préciser les cardinalités de chaque côté de la relation.

## SQL

> Créer une base de données et importez la base contenu dans le script `cinema.sql`.

<details>
    <summary>SPOILER</summary>

Vous avez 6 tables existantes (`movie`, `genre`, `casting`, `person`, `review`, `user`) dont la mise en relation n'a pas été complètement réalisée.

</details>

## Foreign Keys

- Ajouter les clés étrangères, selon le MCD mis à jour.
- Au moment d'ajouter les contraintes des clés étrangères, identifier le souci avec les données existantes.

### Exercice 2

> Le cas échéant, consigner chaque requête d'exercice dans un script (fichier `.sql` par exemple).

- Rajouter les relations manquantes à nos tables via Adminer ou PhpMyAdmin, grâce aux clés étrangères.
 
- Faire en sorte qu'il ne puisse pas y avoir deux films identiques.
    - identifier les autres champs uniques.

### Bonus 1

- Récupérer tous les films.
- Récupérer les critiques pour un film donné.
    - Récupérer le nombre de critiques par film, pour tous les films (en une seule requête).
- Récupérer les acteurs et leur(s) rôle(s) pour un film donné.
- Récupérer tous les films pour une année de sortie donnée.

<details>
  <summary>SPOILER</summary>
  
  `WHERE YEAR(my_date) = '1996'`

</details>

- Récupérer les genres associés à un film donné.
- Récupérer les critiques pour un film donné, ainsi que le nom de l'utiisateur associé.

### Bonus 2 

Modifiez votre schéma pour permettre :

1. Qu'une critique puisse n'être associée à aucun utilisateur (anonyme).
2. Que la suppression d'un film implique la suppression de l'intégralité de son contenu (cascade).
