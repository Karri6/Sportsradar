<?php
// Database Manager

require_once __DIR__ . '/db.php';

/**
 * Get all sports
 */
function getAllSports() {
    global $databaseConnection;
    $query = $databaseConnection->query("SELECT sport_id, name, description FROM sports ORDER BY name");
    return $query->fetchAll();
}

/**
 * Add new sport
 */
function addSport($name, $description = '') {
    global $databaseConnection;
    try {
        $insertSport = $databaseConnection->prepare("INSERT INTO sports (name, description) VALUES (?, ?)");
        $insertSport->execute([trim($name), trim($description)]);
        return $databaseConnection->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get all venues
 */
function getAllVenues() {
    global $databaseConnection;
    $query = $databaseConnection->query("SELECT venue_id, name, city, address, capacity FROM venues ORDER BY name");
    return $query->fetchAll();
}

/**
 * Add new venue
 */
function addVenue($name, $city, $address = '', $capacity = null) {
    global $databaseConnection;
    try {
        $insertVenue = $databaseConnection->prepare("
            INSERT INTO venues (name, city, address, capacity)
            VALUES (?, ?, ?, ?)
        ");
        $insertVenue->execute([trim($name), trim($city), trim($address), $capacity]);
        return $databaseConnection->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get all teams (optionally filtered by sport)
 */
function getAllTeams($sportId = null) {
    global $databaseConnection;

    if ($sportId) {
        $teamsBySport = $databaseConnection->prepare("
            SELECT t.team_id, t.name, t.city, t._sport_id, s.name as sport_name
            FROM teams t
            JOIN sports s ON t._sport_id = s.sport_id
            WHERE t._sport_id = ?
            ORDER BY t.name
        ");
        $teamsBySport->execute([$sportId]);
        return $teamsBySport->fetchAll();
    } else {
        $allTeamsQuery = $databaseConnection->query("
            SELECT t.team_id, t.name, t.city, t._sport_id, s.name as sport_name
            FROM teams t
            JOIN sports s ON t._sport_id = s.sport_id
            ORDER BY t.name
        ");
        return $allTeamsQuery->fetchAll();
    }
}

/**
 * Add new team
 */
function addTeam($sportId, $name, $city = '') {
    global $databaseConnection;
    try {
        $insertTeam = $databaseConnection->prepare("
            INSERT INTO teams (_sport_id, name, city)
            VALUES (?, ?, ?)
        ");
        $insertTeam->execute([$sportId, trim($name), trim($city)]);
        return $databaseConnection->lastInsertId();
    } catch (PDOException $e) {
        return false;
    }
}

/**
 * Get all events
 */
function getAllEvents() {
    global $databaseConnection;

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

    $allEventsQuery = $databaseConnection->query($sql);
    return $allEventsQuery->fetchAll();
}

/**
 * Get upcoming events
 */
function getUpcomingEvents($sportFilter = null, $sortBy = 'date') {
    global $databaseConnection;

    $sql = "
        SELECT
            e.event_id,
            e.event_date,
            e.event_time,
            e.description,
            e.status,
            s.name AS sport_name,
            s.sport_id,
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
        WHERE e.event_date >= CURDATE()
    ";

    // Add sport filter if provided
    if ($sportFilter) {
        $sql .= " AND e._sport_id = :sportFilter";
    }

    // Add sorting
    if ($sortBy === 'sport') {
        $sql .= " ORDER BY s.name ASC, e.event_date ASC, e.event_time ASC";
    } else {
        $sql .= " ORDER BY e.event_date ASC, e.event_time ASC";
    }

    if ($sportFilter) {
        $upcomingEventsQuery = $databaseConnection->prepare($sql);
        $upcomingEventsQuery->execute(['sportFilter' => $sportFilter]);
        return $upcomingEventsQuery->fetchAll();
    } else {
        $upcomingEventsQuery = $databaseConnection->query($sql);
        return $upcomingEventsQuery->fetchAll();
    }
}

/**
 * Get past events
 */
function getPastEvents($sportFilter = null, $sortBy = 'date') {
    global $databaseConnection;

    $sql = "
        SELECT
            e.event_id,
            e.event_date,
            e.event_time,
            e.description,
            e.status,
            s.name AS sport_name,
            s.sport_id,
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
        WHERE e.event_date < CURDATE()
    ";

    // Add sport filter if provided
    if ($sportFilter) {
        $sql .= " AND e._sport_id = :sportFilter";
    }

    // Add sorting
    if ($sortBy === 'sport') {
        $sql .= " ORDER BY s.name ASC, e.event_date DESC, e.event_time DESC";
    } else {
        $sql .= " ORDER BY e.event_date DESC, e.event_time DESC";
    }

    if ($sportFilter) {
        $pastEventsQuery = $databaseConnection->prepare($sql);
        $pastEventsQuery->execute(['sportFilter' => $sportFilter]);
        return $pastEventsQuery->fetchAll();
    } else {
        $pastEventsQuery = $databaseConnection->query($sql);
        return $pastEventsQuery->fetchAll();
    }
}

/**
 * Add new event with teams
 * Returns ['success' => true/false, 'event_id' => 123, 'error' => 'message']
 */
function addEvent($sportId, $venueId, $eventDate, $eventTime, $homeTeamId, $awayTeamId, $description = '') {
    global $databaseConnection;

    try {
        $databaseConnection->beginTransaction();

        // Insert event
        $insertEvent = $databaseConnection->prepare("
            INSERT INTO events (_sport_id, _venue_id, event_date, event_time, description, status)
            VALUES (?, ?, ?, ?, ?, 'scheduled')
        ");

        // Handle optional venue
        $venueIdValue = ($venueId === '' || $venueId === null) ? null : $venueId;

        $insertEvent->execute([
            $sportId,
            $venueIdValue,
            $eventDate,
            $eventTime,
            trim($description)
        ]);

        $eventId = $databaseConnection->lastInsertId();

        // Insert home team
        $insertHomeTeam = $databaseConnection->prepare("
            INSERT INTO event_teams (_event_id, _team_id, is_home)
            VALUES (?, ?, TRUE)
        ");
        $insertHomeTeam->execute([$eventId, $homeTeamId]);

        // Insert away team
        $insertAwayTeam = $databaseConnection->prepare("
            INSERT INTO event_teams (_event_id, _team_id, is_home)
            VALUES (?, ?, FALSE)
        ");
        $insertAwayTeam->execute([$eventId, $awayTeamId]);

        $databaseConnection->commit();

        return ['success' => true, 'event_id' => $eventId];

    } catch (PDOException $e) {
        $databaseConnection->rollBack();
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

/**
 * Get a single event by ID
 */
function getEventById($eventId) {
    global $databaseConnection;

    $sql = "
        SELECT
            e.event_id,
            e.event_date,
            e.event_time,
            e.description,
            e.status,
            s.name AS sport_name,
            s.sport_id,
            v.venue_id,
            v.name AS venue_name,
            v.city AS venue_city,
            v.address AS venue_address,
            v.capacity AS venue_capacity,
            t_home.team_id AS home_team_id,
            t_home.name AS home_team,
            t_home.city AS home_team_city,
            t_away.team_id AS away_team_id,
            t_away.name AS away_team,
            t_away.city AS away_team_city,
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
        WHERE e.event_id = ?
    ";

    $eventQuery = $databaseConnection->prepare($sql);
    $eventQuery->execute([$eventId]);
    return $eventQuery->fetch();
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