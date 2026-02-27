-- Database schema for Take One Blog System

CREATE DATABASE IF NOT EXISTS takeone_db;
USE takeone_db;

-- Admin Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog Categories Table
CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Blog Posts Table
CREATE TABLE IF NOT EXISTS posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    content TEXT NOT NULL,
    image VARCHAR(255),
    author VARCHAR(100) DEFAULT 'Take One',
    status ENUM('draft', 'published') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

-- Comments Table
CREATE TABLE IF NOT EXISTS comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    comment TEXT NOT NULL,
    status ENUM('pending', 'approved') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

-- Insert default admin (password is 'admin123' - user should change it)
INSERT INTO users (username, password, email) VALUES ('admin', '$2y$10$eImiTXuWVxvH7.XW3M8Jm.dYm1m7z1m7z1m7z1m7z1m7z1m7z1m7z', 'admin@takeone.com.ng');

-- Insert some default categories
INSERT INTO categories (name, slug) VALUES 
('Talent', 'talent'),
('Production', 'production'),
('Studios', 'studios'),
('Industry', 'industry'),
('Achievement', 'achievement'),
('Culture', 'culture');
