<?php
// Database Manager

require_once __DIR__ . '/db.php';

/**
 * Get all sports
 */
function getAllSports() {
    global $pdo;
    $stmt = $pdo->query("SELECT sport_id, name, description FROM sports ORDER BY name");
    return $stmt->fetchAll();
}

/**
 * Add new sport
 */
function addSport($name, $description = '') {
    global $pdo;
    try {
        $stmt = $pdo->prepare("INSERT INTO sports (name, description) VALUES (?, ?)");
        $stmt->execute([trim($name), trim($description)]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get all venues
 */
function getAllVenues() {
    global $pdo;
    $stmt = $pdo->query("SELECT venue_id, name, city, address, capacity FROM venues ORDER BY name");
    return $stmt->fetchAll();
}

/**
 * Add new venue
 */
function addVenue($name, $city, $address = '', $capacity = null) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO venues (name, city, address, capacity) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([trim($name), trim($city), trim($address), $capacity]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get all teams (optionally filtered by sport)
 */
function getAllTeams($sportId = null) {
    global $pdo;
    
    if ($sportId) {
        $stmt = $pdo->prepare("
            SELECT t.team_id, t.name, t.city, t._sport_id, s.name as sport_name
            FROM teams t
            JOIN sports s ON t._sport_id = s.sport_id
            WHERE t._sport_id = ?
            ORDER BY t.name
        ");
        $stmt->execute([$sportId]);
    } else {
        $stmt = $pdo->query("
            SELECT t.team_id, t.name, t.city, t._sport_id, s.name as sport_name
            FROM teams t
            JOIN sports s ON t._sport_id = s.sport_id
            ORDER BY t.name
        ");
    }
    
    return $stmt->fetchAll();
}

/**
 * Add new team
 */
function addTeam($sportId, $name, $city = '', $foundedYear = null) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            INSERT INTO teams (_sport_id, name, city, founded_year) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$sportId, trim($name), trim($city), $foundedYear]);
        return $pdo->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get all events
 */
function getAllEvents() {
    global $pdo;
    
    $sql = "
        SELECT 
            e.event_id,
            e.event_date,
            e.event_time,
            e.description,
            e.status,
            s.name AS sport_name,
            v.name AS venue_name,
            v.city AS venue_city,
            t_home.name AS home_team,
            t_away.name AS away_team,
            r.home_score,
            r.away_score,
            t_winner.name AS winner_name
        FROM events e
        JOIN sports s ON e._sport_id = s.sport_id
        LEFT JOIN venues v ON e._venue_id = v.venue_id
        JOIN event_teams et_home ON e.event_id = et_home._event_id AND et_home.is_home = 1
        JOIN teams t_home ON et_home._team_id = t_home.team_id
        JOIN event_teams et_away ON e.event_id = et_away._event_id AND et_away.is_home = 0
        JOIN teams t_away ON et_away._team_id = t_away.team_id
        LEFT JOIN results r ON e.event_id = r._event_id
        LEFT JOIN teams t_winner ON r._winner_id = t_winner.team_id
        ORDER BY e.event_date DESC, e.event_time DESC
    ";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Get upcoming events
 */
function getUpcomingEvents() {
    global $pdo;
    
    $sql = "
        SELECT 
            e.event_id,
            e.event_date,
            e.event_time,
            e.description,
            e.status,
            s.name AS sport_name,
            v.name AS venue_name,
            v.city AS venue_city,
            t_home.name AS home_team,
            t_away.name AS away_team
        FROM events e
        JOIN sports s ON e._sport_id = s.sport_id
        LEFT JOIN venues v ON e._venue_id = v.venue_id
        JOIN event_teams et_home ON e.event_id = et_home._event_id AND et_home.is_home = 1
        JOIN teams t_home ON et_home._team_id = t_home.team_id
        JOIN event_teams et_away ON e.event_id = et_away._event_id AND et_away.is_home = 0
        JOIN teams t_away ON et_away._team_id = t_away.team_id
        WHERE e.event_date >= CURDATE() AND e.status = 'scheduled'
        ORDER BY e.event_date ASC, e.event_time ASC
    ";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Get past events
 */
function getPastEvents() {
    global $pdo;
    
    $sql = "
        SELECT 
            e.event_id,
            e.event_date,
            e.event_time,
            e.description,
            e.status,
            s.name AS sport_name,
            v.name AS venue_name,
            v.city AS venue_city,
            t_home.name AS home_team,
            t_away.name AS away_team,
            r.home_score,
            r.away_score,
            t_winner.name AS winner_name
        FROM events e
        JOIN sports s ON e._sport_id = s.sport_id
        LEFT JOIN venues v ON e._venue_id = v.venue_id
        JOIN event_teams et_home ON e.event_id = et_home._event_id AND et_home.is_home = 1
        JOIN teams t_home ON et_home._team_id = t_home.team_id
        JOIN event_teams et_away ON e.event_id = et_away._event_id AND et_away.is_home = 0
        JOIN teams t_away ON et_away._team_id = t_away.team_id
        LEFT JOIN results r ON e.event_id = r._event_id
        LEFT JOIN teams t_winner ON r._winner_id = t_winner.team_id
        WHERE e.status = 'completed'
        ORDER BY e.event_date DESC, e.event_time DESC
    ";
    
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll();
}

/**
 * Add new event with teams
 * Returns ['success' => true/false, 'event_id' => 123, 'error' => 'message']
 */
function addEvent($sportId, $venueId, $eventDate, $eventTime, $homeTeamId, $awayTeamId, $description = '') {
    global $pdo;
    
    try {
        $pdo->beginTransaction();
        
        // Insert event
        $stmt = $pdo->prepare("
            INSERT INTO events (_sport_id, _venue_id, event_date, event_time, description, status)
            VALUES (?, ?, ?, ?, ?, 'scheduled')
        ");
        
        // Handle optional venue
        $venueIdValue = ($venueId === '' || $venueId === null) ? null : $venueId;
        
        $stmt->execute([
            $sportId,
            $venueIdValue,
            $eventDate,
            $eventTime,
            trim($description)
        ]);
        
        $eventId = $pdo->lastInsertId();
        
        // Insert home team
        $stmt = $pdo->prepare("
            INSERT INTO event_teams (_event_id, _team_id, is_home)
            VALUES (?, ?, TRUE)
        ");
        $stmt->execute([$eventId, $homeTeamId]);
        
        // Insert away team
        $stmt = $pdo->prepare("
            INSERT INTO event_teams (_event_id, _team_id, is_home)
            VALUES (?, ?, FALSE)
        ");
        $stmt->execute([$eventId, $awayTeamId]);
        
        $pdo->commit();
        
        return ['success' => true, 'event_id' => $eventId];
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Format date for display
 */
function formatDate($date) {
    if (empty($date)) return '';
    $timestamp = strtotime($date);
    return date(DATE_FORMAT, $timestamp);
}

/**
 * Format time for display
 */
function formatTime($time) {
    if (empty($time)) return '';
    $timestamp = strtotime($time);
    return date(TIME_FORMAT, $timestamp);
}

/**
 * Get day of week
 */
function getDayOfWeek($date) {
    $days = ['Sun.', 'Mon.', 'Tue.', 'Wed.', 'Thu.', 'Fri.', 'Sat.'];
    $timestamp = strtotime($date);
    return $days[date('w', $timestamp)];
}
?>