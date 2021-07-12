# Request on `cinema`

## Request to recover all movies

```sql
SELECT * FROM `movie`
```

## Request to recover all reviews of one movie

```sql
SELECT * FROM `review`
WHERE `movie_id`=1
```

## Request to recover nb reviews by movie, for every movies

```sql
SELECT `movie_id`, COUNT(*) AS 'nbr resultat' 
FROM `review` GROUP BY `movie_id`
```

## Request to recover all actors & roles of one movie

```sql
SELECT * FROM `casting` 
INNER JOIN `person` ON `casting`.`person_id` = `person`.`id`
WHERE `movie_id` = 1
```
