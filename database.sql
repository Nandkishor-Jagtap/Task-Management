CREATE DATABASE IF NOT EXISTS task_manager;
USE task_manager;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    user_id INT,
    status ENUM('pending', 'in_progress', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

INSERT INTO users (name, email) VALUES 
('John Doe', 'john@example.com'),
('Jane Smith', 'jane@example.com'),
('Mike Johnson', 'mike@example.com');

INSERT INTO tasks (title, description, user_id, status) VALUES 
('Learn PHP', 'Complete PHP tutorial and practice exercises', 1, 'in_progress'),
('Build Task Manager', 'Create a web application for task management', 1, 'pending'),
('Write Unit Tests', 'Write comprehensive unit tests for all features', 2, 'completed'),
('Setup Database', 'Configure MySQL database and create tables', 2, 'completed'),
('Design UI', 'Create responsive user interface with CSS', 3, 'in_progress');
