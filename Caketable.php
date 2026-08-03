CREATE TABLE cake (
    cake_id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    cake_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    description TEXT,
    image VARCHAR(255) NOT NULL,
    status ENUM('Active','Inactive') DEFAULT 'Active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES category(cid)
);
