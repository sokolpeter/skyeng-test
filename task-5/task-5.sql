WITH s AS (
  SELECT
    t1.id,
    (SELECT t2.id FROM test t2 WHERE t2.id > t1.id ORDER BY t2.id LIMIT 1) AS next_id
  FROM test t1
)
SELECT id AS "FROM", next_id AS "TO"
FROM s WHERE id < next_id - 1;
