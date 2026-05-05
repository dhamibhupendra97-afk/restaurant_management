

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(120) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS food (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    description TEXT NOT NULL,
    food_type ENUM('veg','non_veg') NOT NULL DEFAULT 'veg',
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    food_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (food_id) REFERENCES food(id) ON DELETE CASCADE
);

-- Default admin login: admin@tastetown.com / admin123
INSERT INTO users (name, email, password, role)
SELECT 'Admin', 'admin@tastetown.com', '$2y$10$D66uUgpL8n4xjkAy9WGS0uDPKxANf0VFu79mA8QJxX8M19mcpGhGW', 'admin'
WHERE NOT EXISTS (
    SELECT 1 FROM users WHERE email = 'admin@tastetown.com'
);