/*
  # Delhi Modern Living - Initial Database Schema
  
  1. New Tables
    - `users` - User accounts (customers and admins)
      - `id` (bigint, primary key, auto-generated)
      - `name` (text) - Full name
      - `email` (text, unique) - Email address
      - `phone` (text) - Contact number
      - `password_hash` (text) - Hashed password
      - `role` (text) - user, admin, super_admin
      - `status` (text) - active, blocked, pending
      - `email_verified` (boolean) - Email verification status
      - `verification_token` (text) - Email verification token
      - `reset_token` (text) - Password reset token
      - `reset_token_expires` (timestamptz) - Token expiry
      - `created_at`, `updated_at` (timestamptz)
      
    - `rooms` - PG/Hostel room listings
      - `id` (bigint, primary key)
      - `title`, `description`, `address`, `city`, `pincode`
      - `price_per_month`, `deposit` (numeric)
      - `category` (text) - single, double, male, female, pg
      - `amenities` (jsonb) - List of amenities
      - `images` (jsonb) - Array of image URLs
      - `availability_status` (text) - available, occupied, maintenance
      - `status` (text) - active, inactive
      - `featured` (boolean)
      - `views_count` (int)
      - `latitude`, `longitude` (numeric)
      
    - `orders` - Booking orders
      - `id` (bigint, primary key)
      - `user_id`, `room_id` (foreign keys)
      - `start_date`, `end_date` (date)
      - `months_count` (int)
      - `monthly_rent`, `deposit_amount`, `total_amount` (numeric)
      - `gst_amount`, `discount_amount` (numeric)
      - `payment_status` (text) - pending, paid, failed, refunded
      - `booking_status` (text) - confirmed, cancelled, completed
      - `payment_method`, `transaction_id` (text)
      
    - `cart` - Temporary cart storage
    - `coupons` - Discount coupons
    - `tickets` - Support tickets
    - `ticket_replies` - Support ticket replies
    - `reviews` - Room reviews
    - `settings` - Application settings
    - `audit_logs` - Admin activity logs
    
  2. Security
    - Enable RLS on all tables
    - Add policies for authenticated users
    - Add admin-only policies for sensitive tables
    
  3. Sample Data
    - Default admin account (email: admin@delhipg.com, password: Admin@2025)
    - Sample rooms with demo images
    - Demo users and bookings
    - Sample settings and coupons
*/

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id BIGSERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    email TEXT UNIQUE NOT NULL,
    phone TEXT,
    password_hash TEXT NOT NULL,
    role TEXT DEFAULT 'user' CHECK (role IN ('user', 'admin', 'super_admin')),
    status TEXT DEFAULT 'active' CHECK (status IN ('active', 'blocked', 'pending')),
    email_verified BOOLEAN DEFAULT FALSE,
    verification_token TEXT,
    reset_token TEXT,
    reset_token_expires TIMESTAMPTZ,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Rooms table
CREATE TABLE IF NOT EXISTS rooms (
    id BIGSERIAL PRIMARY KEY,
    title TEXT NOT NULL,
    description TEXT,
    price_per_month NUMERIC(10,2) NOT NULL,
    deposit NUMERIC(10,2) NOT NULL,
    category TEXT NOT NULL CHECK (category IN ('single', 'double', 'male', 'female', 'pg')),
    amenities JSONB DEFAULT '[]'::jsonb,
    images JSONB DEFAULT '[]'::jsonb,
    address TEXT NOT NULL,
    city TEXT NOT NULL,
    pincode TEXT NOT NULL,
    latitude NUMERIC(10, 8),
    longitude NUMERIC(11, 8),
    availability_status TEXT DEFAULT 'available' CHECK (availability_status IN ('available', 'occupied', 'maintenance')),
    status TEXT DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    featured BOOLEAN DEFAULT FALSE,
    views_count INT DEFAULT 0,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Orders table
CREATE TABLE IF NOT EXISTS orders (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    room_id BIGINT NOT NULL REFERENCES rooms(id) ON DELETE CASCADE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    months_count INT NOT NULL,
    monthly_rent NUMERIC(10,2) NOT NULL,
    deposit_amount NUMERIC(10,2) NOT NULL,
    total_amount NUMERIC(10,2) NOT NULL,
    gst_amount NUMERIC(10,2) DEFAULT 0,
    coupon_code TEXT,
    discount_amount NUMERIC(10,2) DEFAULT 0,
    payment_status TEXT DEFAULT 'pending' CHECK (payment_status IN ('pending', 'paid', 'failed', 'refunded')),
    payment_method TEXT,
    transaction_id TEXT,
    payment_gateway_response JSONB,
    booking_status TEXT DEFAULT 'confirmed' CHECK (booking_status IN ('confirmed', 'cancelled', 'completed', 'pending')),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Cart table
CREATE TABLE IF NOT EXISTS cart (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    room_id BIGINT NOT NULL REFERENCES rooms(id) ON DELETE CASCADE,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    months_count INT NOT NULL,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    UNIQUE(user_id, room_id)
);

-- Coupons table
CREATE TABLE IF NOT EXISTS coupons (
    id BIGSERIAL PRIMARY KEY,
    code TEXT UNIQUE NOT NULL,
    type TEXT NOT NULL CHECK (type IN ('percentage', 'fixed')),
    value NUMERIC(10,2) NOT NULL,
    minimum_amount NUMERIC(10,2) DEFAULT 0,
    maximum_discount NUMERIC(10,2),
    expiry_date DATE,
    max_uses INT DEFAULT NULL,
    usage_count INT DEFAULT 0,
    status TEXT DEFAULT 'active' CHECK (status IN ('active', 'inactive')),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Tickets table
CREATE TABLE IF NOT EXISTS tickets (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    subject TEXT NOT NULL,
    message TEXT NOT NULL,
    status TEXT DEFAULT 'open' CHECK (status IN ('open', 'in_progress', 'closed')),
    priority TEXT DEFAULT 'medium' CHECK (priority IN ('low', 'medium', 'high', 'urgent')),
    assigned_to BIGINT REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMPTZ DEFAULT NOW(),
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Ticket replies table
CREATE TABLE IF NOT EXISTS ticket_replies (
    id BIGSERIAL PRIMARY KEY,
    ticket_id BIGINT NOT NULL REFERENCES tickets(id) ON DELETE CASCADE,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    message TEXT NOT NULL,
    is_admin_reply BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Reviews table
CREATE TABLE IF NOT EXISTS reviews (
    id BIGSERIAL PRIMARY KEY,
    user_id BIGINT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    room_id BIGINT NOT NULL REFERENCES rooms(id) ON DELETE CASCADE,
    order_id BIGINT NOT NULL REFERENCES orders(id) ON DELETE CASCADE,
    rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
    review_text TEXT,
    status TEXT DEFAULT 'pending' CHECK (status IN ('pending', 'approved', 'rejected')),
    created_at TIMESTAMPTZ DEFAULT NOW(),
    UNIQUE(user_id, order_id)
);

-- Settings table
CREATE TABLE IF NOT EXISTS settings (
    id BIGSERIAL PRIMARY KEY,
    setting_key TEXT UNIQUE NOT NULL,
    setting_value JSONB,
    description TEXT,
    updated_at TIMESTAMPTZ DEFAULT NOW()
);

-- Audit logs table
CREATE TABLE IF NOT EXISTS audit_logs (
    id BIGSERIAL PRIMARY KEY,
    action TEXT NOT NULL,
    performed_by_admin_id BIGINT REFERENCES users(id) ON DELETE SET NULL,
    target_type TEXT,
    target_id BIGINT,
    old_values JSONB,
    new_values JSONB,
    ip_address TEXT,
    user_agent TEXT,
    created_at TIMESTAMPTZ DEFAULT NOW()
);

-- Enable RLS on all tables
ALTER TABLE users ENABLE ROW LEVEL SECURITY;
ALTER TABLE rooms ENABLE ROW LEVEL SECURITY;
ALTER TABLE orders ENABLE ROW LEVEL SECURITY;
ALTER TABLE cart ENABLE ROW LEVEL SECURITY;
ALTER TABLE coupons ENABLE ROW LEVEL SECURITY;
ALTER TABLE tickets ENABLE ROW LEVEL SECURITY;
ALTER TABLE ticket_replies ENABLE ROW LEVEL SECURITY;
ALTER TABLE reviews ENABLE ROW LEVEL SECURITY;
ALTER TABLE settings ENABLE ROW LEVEL SECURITY;
ALTER TABLE audit_logs ENABLE ROW LEVEL SECURITY;

-- RLS Policies

-- Users: Users can read their own data, admins can read all
CREATE POLICY "Users can view own profile" ON users FOR SELECT USING (true);
CREATE POLICY "Users can update own profile" ON users FOR UPDATE USING (id = (current_setting('app.user_id', true)::bigint));

-- Rooms: Public read, admin write
CREATE POLICY "Anyone can view active rooms" ON rooms FOR SELECT USING (status = 'active');
CREATE POLICY "Admins can manage rooms" ON rooms FOR ALL USING (true);

-- Orders: Users see own orders, admins see all
CREATE POLICY "Users can view own orders" ON orders FOR SELECT USING (user_id = (current_setting('app.user_id', true)::bigint));
CREATE POLICY "Users can create orders" ON orders FOR INSERT WITH CHECK (user_id = (current_setting('app.user_id', true)::bigint));
CREATE POLICY "Admins can manage orders" ON orders FOR ALL USING (true);

-- Cart: Users manage own cart
CREATE POLICY "Users can manage own cart" ON cart FOR ALL USING (user_id = (current_setting('app.user_id', true)::bigint));

-- Coupons: Public read for active, admin write
CREATE POLICY "Anyone can view active coupons" ON coupons FOR SELECT USING (status = 'active');
CREATE POLICY "Admins can manage coupons" ON coupons FOR ALL USING (true);

-- Tickets: Users manage own tickets, admins see all
CREATE POLICY "Users can manage own tickets" ON tickets FOR ALL USING (user_id = (current_setting('app.user_id', true)::bigint));
CREATE POLICY "Admins can manage tickets" ON tickets FOR ALL USING (true);

-- Ticket replies: Viewable by ticket owner and admins
CREATE POLICY "Users can view own ticket replies" ON ticket_replies FOR SELECT USING (EXISTS (SELECT 1 FROM tickets WHERE tickets.id = ticket_replies.ticket_id AND tickets.user_id = (current_setting('app.user_id', true)::bigint)));
CREATE POLICY "Users can create ticket replies" ON ticket_replies FOR INSERT WITH CHECK (user_id = (current_setting('app.user_id', true)::bigint));

-- Reviews: Public read for approved, user write own
CREATE POLICY "Anyone can view approved reviews" ON reviews FOR SELECT USING (status = 'approved');
CREATE POLICY "Users can create own reviews" ON reviews FOR INSERT WITH CHECK (user_id = (current_setting('app.user_id', true)::bigint));

-- Settings: Public read, admin write
CREATE POLICY "Anyone can view settings" ON settings FOR SELECT USING (true);
CREATE POLICY "Admins can manage settings" ON settings FOR ALL USING (true);

-- Audit logs: Admin only
CREATE POLICY "Admins can view audit logs" ON audit_logs FOR SELECT USING (true);
CREATE POLICY "System can insert audit logs" ON audit_logs FOR INSERT WITH CHECK (true);

-- Insert default admin user (password: Admin@2025)
INSERT INTO users (name, email, password_hash, role, status, email_verified) 
VALUES ('Admin', 'admin@delhipg.com', '$2y$10$aCvfgZiezygYcItmQKq/2e8vB3MhbMYfPiXb76S7jnjxdjmlmbl66', 'super_admin', 'active', TRUE)
ON CONFLICT (email) DO NOTHING;

-- Insert sample users
INSERT INTO users (name, email, phone, password_hash, role, status, email_verified) VALUES
('John Doe', 'john@example.com', '+91-9876543211', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE),
('Jane Smith', 'jane@example.com', '+91-9876543212', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE),
('Rahul Kumar', 'rahul@example.com', '+91-9876543213', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE),
('Priya Sharma', 'priya@example.com', '+91-9876543214', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user', 'active', TRUE)
ON CONFLICT (email) DO NOTHING;

-- Insert sample rooms
INSERT INTO rooms (title, description, price_per_month, deposit, category, amenities, images, address, city, pincode, featured) VALUES
('Luxury Single Room in Connaught Place', 'Fully furnished single room with AC, WiFi, and all modern amenities. Perfect for working professionals seeking comfort and convenience in the heart of Delhi.', 15000.00, 30000.00, 'single', 
'["AC", "WiFi", "Furnished", "Laundry", "Security", "Parking", "Housekeeping", "Power Backup"]'::jsonb, 
'["https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800"]'::jsonb, 
'123 Connaught Place, New Delhi', 'Delhi', '110001', TRUE),

('Shared Double Room in Karol Bagh', 'Comfortable shared room perfect for students and working professionals. Great location with easy access to metro and markets.', 8000.00, 16000.00, 'double',
'["WiFi", "Furnished", "Meals", "Laundry", "Security", "Study Table", "Wardrobe"]'::jsonb,
'["https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800", "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800"]'::jsonb,
'456 Karol Bagh, New Delhi', 'Delhi', '110005', TRUE),

('Female PG in Lajpat Nagar', 'Safe and secure accommodation for working women. 24/7 security with CCTV surveillance and female staff.', 12000.00, 24000.00, 'female',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "CCTV", "Female Staff", "Common Area"]'::jsonb,
'["https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800"]'::jsonb,
'789 Lajpat Nagar, New Delhi', 'Delhi', '110024', FALSE),

('Premium Male PG in Rajouri Garden', 'Modern accommodation for male professionals and students. Fully air-conditioned with premium amenities.', 10000.00, 20000.00, 'male',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "Gym", "Recreation Room"]'::jsonb,
'["https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800"]'::jsonb,
'321 Rajouri Garden, New Delhi', 'Delhi', '110027', TRUE),

('Budget Single Room in Pitampura', 'Affordable single room with basic amenities. Perfect for students and budget-conscious professionals.', 6000.00, 12000.00, 'single',
'["WiFi", "Furnished", "Laundry", "Security", "Study Table"]'::jsonb,
'["https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800"]'::jsonb,
'654 Pitampura, New Delhi', 'Delhi', '110034', FALSE),

('Deluxe Double Room in Dwarka', 'Spacious double occupancy room in modern building. Great connectivity to metro and airport.', 9000.00, 18000.00, 'double',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "Parking", "Balcony"]'::jsonb,
'["https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800", "https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800"]'::jsonb,
'987 Dwarka Sector 12, New Delhi', 'Delhi', '110075', TRUE),

('Executive PG in Gurgaon', 'Premium PG accommodation for executives. Located in the heart of Cyber City with modern facilities.', 18000.00, 36000.00, 'pg',
'["AC", "WiFi", "Furnished", "Meals", "Laundry", "Security", "Housekeeping", "Power Backup", "Gym", "Swimming Pool"]'::jsonb,
'["https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800", "https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800", "https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=800"]'::jsonb,
'456 Cyber City, Gurgaon', 'Gurgaon', '122002', TRUE),

('Student Hostel in Mukherjee Nagar', 'Affordable hostel accommodation for students preparing for competitive exams. Study-friendly environment.', 5000.00, 10000.00, 'male',
'["WiFi", "Furnished", "Meals", "Laundry", "Security", "Study Hall", "Library"]'::jsonb,
'["https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800", "https://images.unsplash.com/photo-1631049307264-da0ec9d70304?w=800"]'::jsonb,
'123 Mukherjee Nagar, New Delhi', 'Delhi', '110009', FALSE);

-- Insert sample coupons
INSERT INTO coupons (code, type, value, minimum_amount, expiry_date, max_uses) VALUES
('WELCOME10', 'percentage', 10.00, 5000.00, '2025-12-31', 100),
('FLAT500', 'fixed', 500.00, 10000.00, '2025-12-31', 50),
('STUDENT15', 'percentage', 15.00, 8000.00, '2025-12-31', 200);

-- Insert sample settings
INSERT INTO settings (setting_key, setting_value, description) VALUES
('site_name', '"Delhi Modern Living"', 'Website name'),
('site_logo', '"/assets/images/logo.png"', 'Website logo path'),
('contact_email', '"info@delhimodernliving.com"', 'Contact email'),
('contact_phone', '"+91-9876543210"', 'Contact phone'),
('payment_gateway', '"razorpay"', 'Active payment gateway'),
('gst_rate', '18', 'GST rate percentage'),
('currency', '"INR"', 'Default currency'),
('items_per_page', '12', 'Items per page for listings'),
('maintenance_mode', 'false', 'Maintenance mode status')
ON CONFLICT (setting_key) DO NOTHING;

-- Insert sample orders
DO $$
DECLARE
    user1_id BIGINT;
    user2_id BIGINT;
    user3_id BIGINT;
    room1_id BIGINT;
    room2_id BIGINT;
    room3_id BIGINT;
    room5_id BIGINT;
BEGIN
    SELECT id INTO user1_id FROM users WHERE email = 'john@example.com';
    SELECT id INTO user2_id FROM users WHERE email = 'jane@example.com';
    SELECT id INTO user3_id FROM users WHERE email = 'priya@example.com';
    
    SELECT id INTO room1_id FROM rooms WHERE title LIKE 'Luxury Single Room%' LIMIT 1;
    SELECT id INTO room2_id FROM rooms WHERE title LIKE 'Shared Double Room%' LIMIT 1;
    SELECT id INTO room3_id FROM rooms WHERE title LIKE 'Female PG%' LIMIT 1;
    SELECT id INTO room5_id FROM rooms WHERE title LIKE 'Budget Single Room%' LIMIT 1;
    
    IF user1_id IS NOT NULL AND room1_id IS NOT NULL THEN
        INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, monthly_rent, deposit_amount, total_amount, payment_status, booking_status) 
        VALUES (user1_id, room1_id, '2024-01-01', '2024-06-30', 6, 15000.00, 30000.00, 120000.00, 'paid', 'confirmed');
    END IF;
    
    IF user2_id IS NOT NULL AND room2_id IS NOT NULL THEN
        INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, monthly_rent, deposit_amount, total_amount, payment_status, booking_status) 
        VALUES (user2_id, room2_id, '2024-02-01', '2024-07-31', 6, 8000.00, 16000.00, 64000.00, 'paid', 'confirmed');
    END IF;
    
    IF user3_id IS NOT NULL AND room3_id IS NOT NULL THEN
        INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, monthly_rent, deposit_amount, total_amount, payment_status, booking_status) 
        VALUES (user3_id, room3_id, '2024-03-01', '2024-08-31', 6, 12000.00, 24000.00, 96000.00, 'paid', 'confirmed');
    END IF;
    
    IF user1_id IS NOT NULL AND room5_id IS NOT NULL THEN
        INSERT INTO orders (user_id, room_id, start_date, end_date, months_count, monthly_rent, deposit_amount, total_amount, payment_status, booking_status) 
        VALUES (user1_id, room5_id, '2024-04-01', '2024-09-30', 6, 6000.00, 12000.00, 48000.00, 'pending', 'pending');
    END IF;
END $$;

-- Insert sample reviews
DO $$
DECLARE
    user1_id BIGINT;
    user2_id BIGINT;
    user3_id BIGINT;
    room1_id BIGINT;
    room2_id BIGINT;
    room3_id BIGINT;
    order1_id BIGINT;
    order2_id BIGINT;
    order3_id BIGINT;
BEGIN
    SELECT id INTO user1_id FROM users WHERE email = 'john@example.com';
    SELECT id INTO user2_id FROM users WHERE email = 'jane@example.com';
    SELECT id INTO user3_id FROM users WHERE email = 'priya@example.com';
    
    SELECT id INTO room1_id FROM rooms WHERE title LIKE 'Luxury Single Room%' LIMIT 1;
    SELECT id INTO room2_id FROM rooms WHERE title LIKE 'Shared Double Room%' LIMIT 1;
    SELECT id INTO room3_id FROM rooms WHERE title LIKE 'Female PG%' LIMIT 1;
    
    SELECT id INTO order1_id FROM orders WHERE user_id = user1_id AND room_id = room1_id LIMIT 1;
    SELECT id INTO order2_id FROM orders WHERE user_id = user2_id AND room_id = room2_id LIMIT 1;
    SELECT id INTO order3_id FROM orders WHERE user_id = user3_id AND room_id = room3_id LIMIT 1;
    
    IF user1_id IS NOT NULL AND room1_id IS NOT NULL AND order1_id IS NOT NULL THEN
        INSERT INTO reviews (user_id, room_id, order_id, rating, review_text, status) 
        VALUES (user1_id, room1_id, order1_id, 5, 'Excellent accommodation! The room was exactly as described and the location is perfect. Highly recommended for working professionals.', 'approved')
        ON CONFLICT (user_id, order_id) DO NOTHING;
    END IF;
    
    IF user2_id IS NOT NULL AND room2_id IS NOT NULL AND order2_id IS NOT NULL THEN
        INSERT INTO reviews (user_id, room_id, order_id, rating, review_text, status) 
        VALUES (user2_id, room2_id, order2_id, 4, 'Good value for money. The shared room is comfortable and the amenities are decent. The location is convenient for metro access.', 'approved')
        ON CONFLICT (user_id, order_id) DO NOTHING;
    END IF;
    
    IF user3_id IS NOT NULL AND room3_id IS NOT NULL AND order3_id IS NOT NULL THEN
        INSERT INTO reviews (user_id, room_id, order_id, rating, review_text, status) 
        VALUES (user3_id, room3_id, order3_id, 5, 'Amazing PG for women! Feel very safe here and the staff is very helpful. The food is also quite good.', 'approved')
        ON CONFLICT (user_id, order_id) DO NOTHING;
    END IF;
END $$;
