DROP DATABASE IF EXISTS sportscalendar;
CREATE DATABASE sportscalendar CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE sportscalendar;

CREATE TABLE sports (
    sport_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE venues (
    venue_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100) NOT NULL,
    address VARCHAR(255),
    capacity INT,
    UNIQUE KEY unique_venue (name, city),
    INDEX idx_city (city),
    INDEX idx_name (name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE teams (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    _sport_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    city VARCHAR(100),
    FOREIGN KEY (_sport_id) REFERENCES sports(sport_id) ON DELETE RESTRICT,
    UNIQUE KEY unique_team_sport (name, _sport_id),
    INDEX idx_name (name),
    INDEX idx_sport (_sport_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    _sport_id INT NOT NULL,
    _venue_id INT,
    event_date DATE NOT NULL,
    event_time TIME NOT NULL,
    description TEXT,
    status ENUM('scheduled', 'completed') DEFAULT 'scheduled',
    FOREIGN KEY (_sport_id) REFERENCES sports(sport_id) ON DELETE RESTRICT,
    FOREIGN KEY (_venue_id) REFERENCES venues(venue_id) ON DELETE SET NULL,
    INDEX idx_date (event_date),
    INDEX idx_status (status),
    INDEX idx_sport_date (_sport_id, event_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE event_teams (
    event_team_id INT AUTO_INCREMENT PRIMARY KEY,
    _event_id INT NOT NULL,
    _team_id INT NOT NULL,
    is_home BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (_event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (_team_id) REFERENCES teams(team_id) ON DELETE CASCADE,
    UNIQUE KEY unique_event_team (_event_id, _team_id),
    INDEX idx_event (_event_id),
    INDEX idx_team (_team_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE results (
    result_id INT AUTO_INCREMENT PRIMARY KEY,
    _event_id INT NOT NULL UNIQUE,
    _winner_id INT,
    home_score INT NOT NULL DEFAULT 0,
    away_score INT NOT NULL DEFAULT 0,
    notes TEXT,
    FOREIGN KEY (_event_id) REFERENCES events(event_id) ON DELETE CASCADE,
    FOREIGN KEY (_winner_id) REFERENCES teams(team_id) ON DELETE SET NULL,
    INDEX idx_event (_event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SELECT 'Database schema created successfully!' AS Status;