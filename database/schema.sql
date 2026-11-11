CREATE DATABASE IF NOT EXISTS carebridge CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE carebridge;

CREATE TABLE IF NOT EXISTS patients (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_code VARCHAR(24) NOT NULL UNIQUE,
  full_name VARCHAR(120) NOT NULL,
  mobile VARCHAR(30) NOT NULL,
  address TEXT NOT NULL,
  age TINYINT UNSIGNED NULL,
  gender ENUM('Female','Male','Other','Prefer not to say') NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS checkups (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id BIGINT UNSIGNED NOT NULL,
  symptoms TEXT NOT NULL,
  duration VARCHAR(100) NULL,
  temperature_c DECIMAL(4,1) NULL,
  systolic SMALLINT UNSIGNED NULL,
  diastolic SMALLINT UNSIGNED NULL,
  medical_history TEXT NULL,
  status ENUM('Pending','In review','Reviewed') NOT NULL DEFAULT 'Pending',
  clinician_notes TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_checkups_patient FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE,
  INDEX idx_checkups_status (status),
  INDEX idx_checkups_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS medicines (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  medicine_name VARCHAR(160) NOT NULL,
  generic_name VARCHAR(160) NULL,
  category VARCHAR(100) NULL,
  reference_information TEXT NULL,
  warnings TEXT NULL,
  source_name VARCHAR(200) NULL,
  source_url VARCHAR(500) NULL,
  verified TINYINT(1) NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_medicine_name (medicine_name),
  INDEX idx_generic_name (generic_name)
) ENGINE=InnoDB;

-- Add medicines only from trusted, current official formularies or regulator sources.
-- Do not use unverified sample drug/dose data as clinical guidance.
