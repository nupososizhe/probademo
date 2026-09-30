CREATE DATABASE IF NOT EXISTS probademo
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE probademo;

DROP TABLE IF EXISTS reviews;
DROP TABLE IF EXISTS applications;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(150) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE rooms (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    type ENUM('Аудитория', 'Коворкинг', 'Кинозал') NOT NULL,
    capacity INT UNSIGNED NULL
) ENGINE=InnoDB;

CREATE TABLE applications (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    room_id INT UNSIGNED NOT NULL,
    conference_date DATE NOT NULL,
    payment_method ENUM('Очное посещение', 'СБП') NOT NULL,
    status ENUM('Новая', 'Мероприятие назначено', 'Завершено')
        NOT NULL DEFAULT 'Новая',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_app_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_app_room
        FOREIGN KEY (room_id) REFERENCES rooms(id)
        ON DELETE RESTRICT,

    INDEX idx_app_user (user_id),
    INDEX idx_app_status (status),
    INDEX idx_app_date (conference_date)
) ENGINE=InnoDB;

CREATE TABLE reviews (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    application_id INT UNSIGNED NOT NULL UNIQUE,
    user_id INT UNSIGNED NOT NULL,
    rating TINYINT UNSIGNED NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_review_app
        FOREIGN KEY (application_id) REFERENCES applications(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_review_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,

    CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB;

INSERT INTO rooms (name, type, capacity) VALUES
('Аудитория А-101', 'Аудитория', 80),
('Аудитория Б-204', 'Аудитория', 120),
('Коворкинг Central', 'Коворкинг', 45),
('Коворкинг Space', 'Коворкинг', 60),
('Кинозал Премьер', 'Кинозал', 220);
