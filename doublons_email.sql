SELECT DISTINCT *
FROM `POLY_INT_users` t1
WHERE EXISTS (
              SELECT *
              FROM `POLY_INT_users` t2
              WHERE t1.id <> t2.id
              AND   t1.email = t2.email )