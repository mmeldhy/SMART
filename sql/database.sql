-- Create database
CREATE DATABASE IF NOT EXISTS rt_management;
USE rt_management;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'warga') NOT NULL DEFAULT 'warga',
    address TEXT,
    phone VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP
);

-- Fees table
CREATE TABLE IF NOT EXISTS fees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    amount DECIMAL(10, 2) NOT NULL,
    due_date DATE NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    fee_id INT NOT NULL,
    payment_date DATE NOT NULL,
    proof_image VARCHAR(255),
    status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
    admin_response TEXT, -- Added from the second schema
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (fee_id) REFERENCES fees(id) ON DELETE CASCADE
);

-- Announcements table
CREATE TABLE IF NOT EXISTS announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    type VARCHAR(30) NOT NULL DEFAULT 'general', -- Added from the second schema
    start_date DATE DEFAULT NULL, -- Added from the second schema
    end_date DATE DEFAULT NULL, -- Added from the second schema
    image_url VARCHAR(255) DEFAULT NULL, -- Added from the second schema
    is_pinned TINYINT(1) NOT NULL DEFAULT '0', -- Added from the second schema
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP
);

-- Schedules table
CREATE TABLE IF NOT EXISTS schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    schedule_datetime DATETIME DEFAULT NULL, -- Combined date and time into one DATETIME column for better consistency
    location VARCHAR(255) DEFAULT NULL, -- Added from the second schema
    type VARCHAR(50) DEFAULT NULL, -- Added from the second schema
    status VARCHAR(50) DEFAULT NULL, -- Added from the second schema
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP
);

-- Reports table
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255),
    status ENUM('pending', 'in_progress', 'resolved', 'rejected') NOT NULL DEFAULT 'pending',
    admin_response TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Settings table (Added from the second schema for general RT information)
CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    rt_name VARCHAR(255) DEFAULT NULL,
    rt_number VARCHAR(10) DEFAULT NULL,
    rw_number VARCHAR(10) DEFAULT NULL,
    district VARCHAR(255) DEFAULT NULL,
    city VARCHAR(255) DEFAULT NULL,
    province VARCHAR(255) DEFAULT NULL,
    contact_email VARCHAR(255) DEFAULT NULL,
    contact_phone VARCHAR(20) DEFAULT NULL,
    address TEXT,
    maintenance_mode TINYINT(1) DEFAULT '0',
    registration_enabled TINYINT(1) DEFAULT '1',
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user
INSERT INTO users (name, username, password, role, address, phone) VALUES
('Administrator', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'Kantor RT', '08123456789');
-- Password for admin: admin123

-- Insert sample resident user
INSERT INTO users (name, username, password, role, address, phone) VALUES
('Warga Satu', 'warga1', '$2y$10$hPZQYQLiLrxVw3ZGQJ2Bxu/Jm1vHH.Wl/5hQvDMrNTCJNGOXMqA4.', 'warga', 'Jl. Contoh No. 1', '08111222333');
-- Password for warga1: warga123

-- Insert sample fees
INSERT INTO fees (name, description, amount, due_date) VALUES
('Iuran Kebersihan', 'Iuran bulanan untuk kebersihan lingkungan', 50000, DATE_ADD(CURRENT_DATE, INTERVAL 1 MONTH)),
('Iuran Keamanan', 'Iuran bulanan untuk keamanan lingkungan', 75000, DATE_ADD(CURRENT_DATE, INTERVAL 1 MONTH)),
('Iuran Pembangunan', 'Iuran untuk pembangunan fasilitas RT', 100000, DATE_ADD(CURRENT_DATE, INTERVAL 3 MONTH));

-- Insert sample announcements
INSERT INTO announcements (title, content, type, start_date, end_date, is_pinned) VALUES
('Jadwal Kerja Bakti', '<p>Diberitahukan kepada seluruh warga bahwa akan diadakan kerja bakti pada:</p><ul><li>Hari/Tanggal: Minggu, 15 Juni 2023</li><li>Waktu: 07.00 - 10.00 WIB</li><li>Tempat: Lingkungan RT</li></ul><p>Diharapkan partisipasi dari seluruh warga.</p>', 'event', '2023-06-01', '2023-06-15', 1),
('Pemberitahuan Pemadaman Listrik', '<p>Diberitahukan kepada seluruh warga bahwa akan ada pemadaman listrik pada:</p><ul><li>Hari/Tanggal: Sabtu, 10 Juni 2023</li><li>Waktu: 09.00 - 14.00 WIB</li></ul><p>Harap warga dapat mempersiapkan diri.</p>', 'info', '2023-06-05', '2023-06-10', 0);

-- Insert sample schedules
INSERT INTO schedules (title, description, schedule_datetime, location, type, status) VALUES
('Kerja Bakti', 'Kerja bakti membersihkan lingkungan RT', CONCAT(DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY), ' 07:00:00'), 'Lingkungan RT', 'Community Event', 'Scheduled'),
('Rapat Warga', 'Rapat evaluasi program RT', CONCAT(DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY), ' 19:30:00'), 'Balai Warga', 'Meeting', 'Scheduled'),
('Posyandu', 'Pemeriksaan kesehatan rutin untuk balita dan lansia', CONCAT(DATE_ADD(CURRENT_DATE, INTERVAL 21 DAY), ' 09:00:00'), 'Posyandu RT', 'Health Program', 'Scheduled');

-- Insert sample reports
INSERT INTO reports (user_id, title, description, category, status) VALUES
(2, 'Lampu Jalan Mati', 'Lampu jalan di depan rumah no. 10 mati sejak 3 hari yang lalu', 'Infrastruktur', 'pending'),
(2, 'Saluran Air Tersumbat', 'Saluran air di Jl. Contoh No. 5 tersumbat dan menyebabkan genangan saat hujan', 'Lingkungan', 'in_progress');

-- Insert default settings (assuming a single row for RT settings)
INSERT INTO settings (rt_name, rt_number, rw_number, district, city, province, contact_email, contact_phone, address, maintenance_mode, registration_enabled) VALUES
('RT Bahagia', '001', '005', 'Cihideung', 'Tasikmalaya', 'Jawa Barat', 'info@rtbahagia.org', '081234567890', 'Jl. Kebahagiaan No. 1, Tasikmalaya', 0, 1);