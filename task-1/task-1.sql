DROP TABLE IF EXISTS books;

CREATE TABLE books(
  id SERIAL PRIMARY KEY,
  title TEXT
);

DROP TABLE IF EXISTS authors;

CREATE TABLE authors(
  id SERIAL PRIMARY KEY,
  name TEXT
);

DROP TABLE IF EXISTS book_authors;

CREATE TABLE book_authors(
  id SERIAL PRIMARY KEY,
  book_id INTEGER CONSTRAINT book_fk REFERENCES books(id),
  author_id INTEGER CONSTRAINT author_fk REFERENCES authors(id)
);

INSERT INTO books (id, title) VALUES
(1, 'book-1'),
(2, 'book-2'),
(3, 'book-3');

INSERT INTO authors (id, name) VALUES
(1, 'author-1'),
(2, 'author-2'),
(3, 'author-3'),
(4, 'author-4'),
(5, 'author-5'),
(6, 'author-6'),
(7, 'author-7'),
(8, 'author-8'),
(9, 'author-9');

INSERT INTO book_authors (book_id, author_id) VALUES
(1,1),
(1,2),
(2,3),
(2,4),
(2,5),
(3,6),
(3,7),
(3,8),
(3,9);

SELECT b.title as book_title, COUNT(ba.author_id) AS author_count FROM books b
INNER JOIN book_authors ba ON ba.book_id = b.id
GROUP BY ba.book_id, b.title
HAVING COUNT(ba.author_id) = 3;
