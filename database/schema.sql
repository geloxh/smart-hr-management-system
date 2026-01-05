CREATE DATABASE smart_hr_db;
USE smart_hr_db;

-- Users table for authentication
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'hr', 'it') NOT NULL DEFAULT 'employee',
    employee_id INT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(id) ON DELETE SET NULL
);

-- Sessions table for login management
CREATE TABLE user_sessions (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, role) VALUES 
('admin', 'admin@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

CREATE TABLE employees (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id VARCHAR(20) UNIQUE NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    position VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL,
    hire_date DATE NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE attendance (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    date DATE NOT NULL,
    check_in TIME,
    check_out TIME,
    status ENUM('present', 'absent', 'late') DEFAULT 'present',
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

CREATE TABLE leaves (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    type ENUM('sick', 'vacation', 'personal') NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    reason TEXT,
    FOREIGN KEY (employee_id) REFERENCES employees(id)
);

-- Performance Management
CREATE TABLE performance_reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    reviewer_id INT NOT NULL,
    review_period VARCHAR(50),
    overall_rating DECIMAL(3,2),
    goals TEXT,
    achievements TEXT,
    areas_for_improvement TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Payroll
CREATE TABLE payroll (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    pay_period VARCHAR(50),
    basic_salary DECIMAL(10,2),
    overtime_hours DECIMAL(5,2),
    deductions DECIMAL(10,2),
    net_salary DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Notifications
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    employee_id INT NOT NULL,
    title VARCHAR(255),
    message TEXT,
    type ENUM('info', 'warning', 'success', 'error'),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);