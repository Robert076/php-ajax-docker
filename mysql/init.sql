CREATE DATABASE IF NOT EXISTS db;

CREATE USER IF NOT EXISTS 'admin'@'%' IDENTIFIED WITH mysql_native_password BY 'admin';

GRANT ALL PRIVILEGES ON db.* TO 'admin'@'%';

FLUSH PRIVILEGES;

CREATE TABLE IF NOT EXISTS books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    price DECIMAL(10, 2) NOT NULL
);

INSERT INTO books (author, title, price) 
VALUES ('George Orwell', '1984', 15.99);

INSERT INTO books (author, title, price) 
VALUES ('J.K. Rowling', 'Harry Potter and the Sorcerer\'s Stone', 20.99);

INSERT INTO books (author, title, price) 
VALUES ('J.R.R. Tolkien', 'The Lord of the Rings', 25.50);
