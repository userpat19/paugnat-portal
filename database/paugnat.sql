CREATE DATABASE IF NOT EXISTS paugnat_db;
USE paugnat_db;

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS colleges (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    points INT DEFAULT 0
);

CREATE TABLE IF NOT EXISTS events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_name VARCHAR(100) NOT NULL,
    event_date DATE NOT NULL
);

CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('new','read') NOT NULL DEFAULT 'new',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password)
VALUES (
    'admin',
    '$2y$10$mJIOM72geqmZLoOlbGQDB.4Z9RqI02GArWnegTyOLNkwZ7U0UHuH6'
);

INSERT INTO colleges (name, points) VALUES
('College of Engineering', 150),
('College of Science', 120),
('College of Business', 100),
('College of Education', 90),
('College of Arts', 80);

INSERT INTO events (event_name, event_date) VALUES
('Basketball Championship', '2027-03-15'),
('E-Sports Tournament', '2027-03-16'),
('Mass Dance Competition', '2027-03-17');