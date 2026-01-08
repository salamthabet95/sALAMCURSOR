-- Database Schema for Ramadan Imsakiya SaaS
-- Run this SQL in your Hostinger MySQL database
-- 
-- ⚠️ ملاحظة: إذا كنت تستخدم phpMyAdmin، أنشئ قاعدة البيانات أولاً
-- ثم استورد هذا الملف. السطر CREATE DATABASE قد لا يعمل في بعض الحالات.

-- CREATE DATABASE IF NOT EXISTS ramadan_imsakiya CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE ramadan_imsakiya;

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) UNIQUE NOT NULL,
    city VARCHAR(100) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    company_phone VARCHAR(20),
    company_address TEXT,
    logo_path VARCHAR(255),
    template_id INT NOT NULL DEFAULT 1,
    customer_name VARCHAR(255) NOT NULL,
    customer_email VARCHAR(255) NOT NULL,
    customer_phone VARCHAR(20),
    status ENUM('pending', 'processing', 'completed', 'failed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_order_id (order_id),
    INDEX idx_status (status),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Generated files table
CREATE TABLE IF NOT EXISTS generated_files (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR(50) NOT NULL,
    pdf_path VARCHAR(255),
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(order_id) ON DELETE CASCADE,
    INDEX idx_order_id (order_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache table (optional, for prayer times caching)
CREATE TABLE IF NOT EXISTS prayer_times_cache (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cache_key VARCHAR(255) UNIQUE NOT NULL,
    cache_data TEXT NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_cache_key (cache_key),
    INDEX idx_expires_at (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
