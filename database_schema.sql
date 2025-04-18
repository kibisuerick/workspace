-- Database: car_rental_db

-- Table: users
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'employee', 'customer') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15) NOT NULL,
    loyalty_points INT DEFAULT 0
);

-- Insert dummy data for users
INSERT INTO users (username, password, role, full_name, email, phone, loyalty_points) VALUES
('john_doe', 'password123', 'customer', 'John Doe', 'john.doe@example.com', '1234567890', 100),
('jane_smith', 'password123', 'customer', 'Jane Smith', 'jane.smith@example.com', '0987654321', 200),
('admin_user', 'adminpass', 'admin', 'Admin User', 'admin@example.com', '1112223333', 0);

-- Table: vehicles
CREATE TABLE vehicles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    make VARCHAR(50) NOT NULL,
    model VARCHAR(50) NOT NULL,
    year INT NOT NULL,
    price_per_day DECIMAL(10, 2) NOT NULL,
    availability BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: bookings
CREATE TABLE bookings (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    car_id INT NOT NULL,
    pickup_location_id INT NOT NULL,
    dropoff_location_id INT NOT NULL,
    pickup_date DATE NOT NULL,
    return_date DATE NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    total_amount DECIMAL(10, 2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (car_id) REFERENCES cars(car_id) ON DELETE CASCADE,
    FOREIGN KEY (pickup_location_id) REFERENCES locations(location_id) ON DELETE CASCADE,
    FOREIGN KEY (dropoff_location_id) REFERENCES locations(location_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Insert dummy data for bookings
INSERT INTO bookings (user_id, car_id, pickup_location_id, dropoff_location_id, pickup_date, return_date, booking_status, total_amount) VALUES
(1, 1, 1, 2, '2025-04-15', '2025-04-20', 'pending', 250.00),
(2, 2, 2, 1, '2025-04-10', '2025-04-18', 'confirmed', 480.00),
(1, 3, 1, 1, '2025-04-12', '2025-04-15', 'cancelled', 300.00);

-- Table: roles (optional for extensibility)
CREATE TABLE roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default roles
INSERT INTO roles (name) VALUES ('admin'), ('employee'), ('customer');

-- Table: cars
CREATE TABLE cars (
    car_id INT AUTO_INCREMENT PRIMARY KEY,
    model VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    year YEAR NOT NULL,
    registration_number VARCHAR(50) NOT NULL UNIQUE,
    price_per_day DECIMAL(10, 2) NOT NULL,
    status ENUM('available', 'unavailable', 'maintenance') DEFAULT 'available',
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert dummy data for cars
INSERT INTO cars (model, brand, year, registration_number, price_per_day, status) VALUES
('Corolla', 'Toyota', 2020, 'ABC123', 50.00, 'available'),
('Civic', 'Honda', 2021, 'XYZ789', 60.00, 'available'),
('Model S', 'Tesla', 2022, 'TES123', 100.00, 'maintenance');

-- Table: locations
CREATE TABLE locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert dummy data for locations
INSERT INTO locations (name, address) VALUES
('Downtown', '123 Main St'),
('Airport', '456 Airport Rd');

-- Table: payments
CREATE TABLE payments (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method ENUM('credit_card', 'debit_card', 'paypal', 'cash') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed') DEFAULT 'pending',
    amount_paid DECIMAL(10, 2) NOT NULL,
    paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES bookings(booking_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: reports
CREATE TABLE reports (
    report_id INT AUTO_INCREMENT PRIMARY KEY,
    report_name VARCHAR(100) NOT NULL,
    generated_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    report_data TEXT NOT NULL,
    FOREIGN KEY (generated_by) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: employees
CREATE TABLE employees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15),
    position VARCHAR(50),
    hire_date DATE,
    salary DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert sample employees
INSERT INTO employees (name, email, phone, position, hire_date, salary) VALUES
('John Doe', 'john.doe@example.com', '1234567890', 'Manager', '2023-01-15', 60000.00),
('Jane Smith', 'jane.smith@example.com', '0987654321', 'Assistant', '2024-03-10', 40000.00);