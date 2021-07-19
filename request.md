# Request on `cinema`

## Request to recover all movies

```sql
SELECT * FROM `movie`
```

## Request to recover all reviews from movie

```sql
SELECT * FROM `review`
WHERE `movie_id` = 1
```

## Request to recover nb reviews by movie, for every movies

```sql
SELECT `movie_id`, COUNT(*) AS 'nbr resultat' 
FROM `review` GROUP BY `movie_id`
```

## Request to recover all actors & roles from movie

```sql
SELECT * FROM `casting` 
INNER JOIN `person` ON `casting`.`person_id` = `person`.`id`
WHERE `movie_id` = 1
```

## Request to recover all movies by year

```sql
SELECT * FROM `movie`
WHERE YEAR(`release_date`) = '1992'
```

## Request to recover all genres from movie

```sql
SELECT * FROM `movie_genre`
INNER JOIN `genre` on `movie_genre`.`genre_id` = `genre`.`id`
WHERE `movie_id` = 1
```

## Recover all reviews by movie & nickname's user

```sql
SELECT `review`.*, `user`.`nickname` FROM `review`
INNER JOIN `user` ON `user`.`id`=`review`.`user_id`
WHERE `movie_id` = 1
```
