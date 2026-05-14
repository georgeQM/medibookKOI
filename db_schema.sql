-- MediBook Database Schema
-- Run this in phpMyAdmin > medibook > SQL tab

SET FOREIGN_KEY_CHECKS = 0;

-- ─────────────────────────────────────────
-- USERS
-- ─────────────────────────────────────────
CREATE TABLE users (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role          ENUM('patient','doctor','admin') NOT NULL DEFAULT 'patient',
  created_at    DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ─────────────────────────────────────────
-- DOCTORS
-- ─────────────────────────────────────────
CREATE TABLE doctors (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  user_id   INT NOT NULL UNIQUE,
  specialty VARCHAR(100) NOT NULL,
  bio       TEXT,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ─────────────────────────────────────────
-- TIME SLOTS
-- ─────────────────────────────────────────
CREATE TABLE time_slots (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  doctor_id   INT NOT NULL,
  day_of_week ENUM('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday') NOT NULL,
  start_time  TIME NOT NULL,
  end_time    TIME NOT NULL,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- ─────────────────────────────────────────
-- APPOINTMENTS
-- ─────────────────────────────────────────
CREATE TABLE appointments (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  patient_id INT NOT NULL,
  doctor_id  INT NOT NULL,
  slot_id    INT NOT NULL,
  date       DATE NOT NULL,
  status     ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  notes      TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id)  REFERENCES doctors(id) ON DELETE CASCADE,
  FOREIGN KEY (slot_id)    REFERENCES time_slots(id) ON DELETE CASCADE
);

-- ─────────────────────────────────────────
-- AUDIT LOG
-- ─────────────────────────────────────────
CREATE TABLE audit_log (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  user_id    INT,
  action     VARCHAR(100) NOT NULL,
  timestamp  DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

SET FOREIGN_KEY_CHECKS = 1;

-- ─────────────────────────────────────────
-- SEED DATA
-- ─────────────────────────────────────────

-- Admin user (password: admin123)
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin', 'admin@medibook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

-- Doctor users (password: doctor123)
INSERT INTO users (name, email, password_hash, role) VALUES
('Dr. Rachel Morgan', 'rachel@medibook.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor'),
('Dr. James Kim',    'james@medibook.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor'),
('Dr. Priya Anand',  'priya@medibook.com',  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'doctor');

-- Doctor profiles
INSERT INTO doctors (user_id, specialty, bio) VALUES
(2, 'General Practitioner', '15 years of experience in general practice. Special interest in chronic disease management and preventive care.'),
(3, 'Cardiologist',         'Specialist in cardiovascular health with over a decade in clinical cardiology across Sydney and Melbourne.'),
(4, 'Psychologist',         'Registered psychologist focused on anxiety, depression, and workplace stress. Offers in-person and telehealth sessions.');

-- Time slots
INSERT INTO time_slots (doctor_id, day_of_week, start_time, end_time) VALUES
(1, 'Monday',    '08:00:00', '08:30:00'),
(1, 'Monday',    '08:30:00', '09:00:00'),
(1, 'Wednesday', '10:00:00', '10:30:00'),
(1, 'Friday',    '14:00:00', '14:30:00'),
(2, 'Tuesday',   '09:00:00', '09:30:00'),
(2, 'Thursday',  '11:00:00', '11:30:00'),
(3, 'Monday',    '13:00:00', '13:30:00'),
(3, 'Wednesday', '15:00:00', '15:30:00');
