<?php
// Events functions

require_once __DIR__ . '/db.php';

/**
 * Get all events with teams, sport, and venue info
 * Uses efficient JOIN query (no loops!)
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
 * Get upcoming events only
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
 * Get past events with results
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