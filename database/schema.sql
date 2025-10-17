-- Delhi Modern Living Database Schema
CREATE DATABASE IF NOT EXISTS delhi_modern_living;
USE delhi_modern_living;

-- Users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin', 'super_admin') DEFAULT 'user',
    status ENUM('active', 'blocked', 'pending') DEFAULT 'active',
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token VARCHAR(255),
    reset_token VARCHAR(255),
    reset_token_expires DATETIME,
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Rooms table
CREATE TABLE rooms (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    price_per_month DECIMAL(10,2) NOT NULL,
    deposit DECIMAL(10,2) NOT NULL,
    category ENUM('single', 'double', 'male', 'female', 'pg') NOT NULL,
    amenities JSON,
    images JSON,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    pincode VARCHAR(10) NOT NULL,
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    availability_status ENUM('available', 'occupied', 'maintenance') DEFAULT 'available',
    status ENUM('active', 'inactive') DEFAULT 'active',
    featured BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Orders/Bookings table
CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    months_count INT NOT NULL,
    monthly_rent DECIMAL(10,2) NOT NULL,
    deposit_amount DECIMAL(10,2) NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    gst_amount DECIMAL(10,2) DEFAULT 0,
    coupon_code VARCHAR(50),
    discount_amount DECIMAL(10,2) DEFAULT 0,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    payment_method VARCHAR(50),
    transaction_id VARCHAR(100),
    payment_gateway_response JSON,
    booking_status ENUM('confirmed', 'cancelled', 'completed') DEFAULT 'confirmed',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Downloads table for secure invoice/contract downloads
CREATE TABLE downloads (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_type ENUM('invoice', 'contract') NOT NULL,
    token VARCHAR(255) UNIQUE NOT NULL,
    expires_at DATETIME NOT NULL,
    download_count INT DEFAULT 0,
    max_downloads INT DEFAULT 5,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Coupons table
CREATE TABLE coupons (
    id INT PRIMARY KEY AUTO_INCREMENT,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10,2) NOT NULL,
    minimum_amount DECIMAL(10,2) DEFAULT 0,
    maximum_discount DECIMAL(10,2),
    expiry_date DATE,
    max_uses INT DEFAULT NULL,
    usage_count INT DEFAULT 0,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tickets/Support table
CREATE TABLE tickets (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    subject VARCHAR(200) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('open', 'in_progress', 'closed') DEFAULT 'open',
    priority ENUM('low', 'medium', 'high', 'urgent') DEFAULT 'medium',
    assigned_to INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
);

-- Ticket replies table
CREATE TABLE ticket_replies (
    id INT PRIMARY KEY AUTO_INCREMENT,
    ticket_id INT NOT NULL,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    is_admin_reply BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table for admin configuration
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value JSON,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Audit logs table
CREATE TABLE audit_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    action VARCHAR(100) NOT NULL,
    performed_by_admin_id INT,
    target_type VARCHAR(50),
    target_id INT,
    old_values JSON,
    new_values JSON,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (performed_by_admin_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Cart table for temporary storage
CREATE TABLE cart (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    months_count INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_room (user_id, room_id)
);

-- Reviews table
CREATE TABLE reviews (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    room_id INT NOT NULL,
    order_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    UNIQUE KEY unique_user_order_review (user_id, order_id)
);

-- Insert default admin user
-- Email: admin@delhipg.com | Password: Admin@2025
INSERT INTO users (name, email, password_hash, role, status, email_verified) 
VALUES ('Admin', 'admin@delhipg.com', '$2y$10$aCvfgZiezygYcItmQKq/2e8vB3MhbMYfPiXb76S7jnjxdjmlmbl66', 'super_admin', 'active', TRUE);

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', '"Delhi Modern Living"', 'Website name'),
('site_logo', '"/assets/images/logo.png"', 'Website logo path'),
('contact_email', '"info@delhimodernliving.com"', 'Contact email'),
('contact_phone', '"+91-9876543210"', 'Contact phone'),
('payment_gateway', '"razorpay"', 'Active payment gateway'),
('gst_rate', '18', 'GST rate percentage'),
('currency', '"INR"', 'Default currency'),
('items_per_page', '12', 'Items per page for listings'),
('maintenance_mode', 'false', 'Maintenance mode status');

-- Insert sample rooms
INSERT INTO rooms (title, description, price_per_month, deposit, category, amenities, images, address, city, pincode, featured) VALUES
('Luxury Single Room in Connaught Place', 'Fully furnished single room with AC, WiFi, and all modern amenities. Perfect for working professionals seeking comfort and convenience in the heart of Delhi.', 15000.00, 30000.00, 'single', 
'["AC", "WiFi", "Furnished", "Laundry", "Security", "Parking", "Housekeeping", "Power Backup"]', 
'["https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800"]', 
'123 Connaught Place, New Delhi', 'Delhi', '110001', TRUE),

('Shared Double Room in Karol Bagh', 'Comfortable shared room perfect for students and working professionals. Great location with easy access to metro and markets.', 8000.00, 16000.00, 'double',
'["WiFi", "Furnished", "Meals", "Laundry", "Security", "Study Table", "Wardrobe"]',
'["https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800", "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800"]',
'456 Karol Bagh, New Delhi', 'Delhi', '110005', TRUE),

('Female PG in Lajpat Nagar', 'Safe and secure accommodation for working women. 24/7 security with CCTV surveillance and female staff.', 12000.00, 24000.00, 'female',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "CCTV", "Female Staff", "Common Area"]',
'["https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800"]',
'789 Lajpat Nagar, New Delhi', 'Delhi', '110024', FALSE),

('Premium Male PG in Rajouri Garden', 'Modern accommodation for male professionals and students. Fully air-conditioned with premium amenities.', 10000.00, 20000.00, 'male',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "Gym", "Recreation Room"]',
'["https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800"]',
'321 Rajouri Garden, New Delhi', 'Delhi', '110027', TRUE),

('Budget Single Room in Pitampura', 'Affordable single room with basic amenities. Perfect for students and budget-conscious professionals.', 6000.00, 12000.00, 'single',
'["WiFi", "Furnished", "Laundry", "Security", "Study Table"]',
'["https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800"]',
'654 Pitampura, New Delhi', 'Delhi', '110034', FALSE),

('Deluxe Double Room in Dwarka', 'Spacious double occupancy room in modern building. Great connectivity to metro and airport.', 9000.00, 18000.00, 'double',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "Parking", "Balcony"]',
'["https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800", "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800"]',
'987 Dwarka Sector 12, New Delhi', 'Delhi', '110075', TRUE),

('Executive PG in Gurgaon', 'Premium PG accommodation for executives. Located in the heart of Cyber City with modern facilities.', 18000.00, 36000.00, 'pg',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "Housekeeping", "Power Backup", "Gym", "Swimming Pool"]',
'["https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800"]',
'456 Cyber City, Gurgaon', 'Gurgaon', '122002', TRUE),

('Student Hostel in Mukherjee Nagar', 'Affordable hostel accommodation for students preparing for competitive exams. Study-friendly environment.', 5000.00, 10000.00, 'male',
'["WiFi", "Furnished", "Meals", "Laundry", "Security", "Study Hall", "Library"]',
'["https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800"]',
'123 Mukherjee Nagar, New Delhi', 'Delhi', '110009', FALSE);

-- Insert sample coupons
INSERT INTO coupons (code, type, value, minimum_amount, expiry_date, max_uses) VALUES
('WELCOME10', 'percentage', 10.00, 5000.00, '2024-12-31', 100),
('FLAT500', 'fixed', 500.00, 10000.00, '2024-12-31', 50),
('STUDENT15', 'percentage', 15.00, 8000.00, '2024-12-31', 200);

-- Insert sample users
INSERT INTO users (name, email, phone, password_hash, role, status, email_verified) VALUES
('John Doe', 'john@example.com', '+91-9876543211', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE),
('Jane Smith', 'jane@example.com', '+91-9876543212', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE),
('Rahul Kumar', 'rahul@example.com', '+91-9876543213', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE),
('Priya Sharma', 'priya@example.com', '+91-9876543214', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE);

-- Insert sample orders
INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, total_amount, payment_status, booking_status) VALUES
(2, 1, '2024-01-01', '2024-06-30', 6, 90000.00, 'paid', 'confirmed'),
(3, 2, '2024-02-01', '2024-07-31', 6, 48000.00, 'paid', 'confirmed'),
(4, 3, '2024-03-01', '2024-08-31', 6, 72000.00, 'paid', 'active'),
(2, 5, '2024-04-01', '2024-09-30', 6, 36000.00, 'pending', 'pending');

-- Insert sample reviews
INSERT INTO reviews (user_id, room_id, order_id, rating, review_text, status) VALUES
(2, 1, 1, 5, 'Excellent accommodation! The room was exactly as described and the location is perfect. Highly recommended for working professionals.', 'approved'),
(3, 2, 2, 4, 'Good value for money. The shared room is comfortable and the amenities are decent. The location is convenient for metro access.', 'approved'),
(4, 3, 3, 5, 'Amazing PG for women! Feel very safe here and the staff is very helpful. The food is also quite good.', 'approved');
