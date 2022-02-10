SET SQL_SAFE_UPDATES = 0;

CREATE DATABASE IF NOT EXISTS library;
SHOW DATABASES;

USE library;

CREATE TABLE IF NOT EXISTS book(
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(64) NOT NULL,
	isbn VARCHAR(13) NOT NULL UNIQUE,
	authorlastname VARCHAR(32) NOT NULL,
	datepublished DATE 
);

SHOW TABLES;
DESCRIBE book;


DELETE FROM book;

INSERT INTO book(
	id, title, isbn, authorlastname, datepublished
) VALUES(
	100, 'four hour workweek', '444444', 'ferriss', '2000-02-28'
);

INSERT INTO book(
	id, title, isbn, authorlastname, datepublished
) VALUES(
	200, 'getting real', 'abc123', '37 signals', null
);

INSERT INTO book(
	id, title, isbn, authorlastname, datepublished
) VALUES(
	300, 'getting things done', 'xyz456', 'allen', '1999-12-25'
);

SELECT * FROM book;