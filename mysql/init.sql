CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,  
    author VARCHAR(255) NOT NULL,       
    title VARCHAR(255) NOT NULL,        
    price DECIMAL(10, 2) NOT NULL       
);