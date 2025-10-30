<?php
// Main application entry point

// Configure session security settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_secure', 0);

session_start();

require_once __DIR__ . '/backend/config.php';
require_once __DIR__ . '/backend/database.php';
require_once __DIR__ . '/backend/validation.php';
require_once __DIR__ . '/backend/csrf.php';

// Get and validate page parameters
$page = $_GET['page'] ?? 'calendar';

// Sanitize and validate calendar parameters
$view = $_GET['view'] ?? 'upcoming';
$sportFilter = $_GET['sport'] ?? null;
$sortBy = $_GET['sort'] ?? 'date';

// Prepare validated view state for templates
$currentView = in_array($view, ['upcoming', 'past']) ? $view : 'upcoming';
$currentSort = in_array($sortBy, ['date', 'sport']) ? $sortBy : 'date';
$currentSport = validateId($sportFilter) ? $sportFilter : null;

require_once __DIR__ . '/templates/header.php';

// Route to the appropriate page
switch ($page) {
    case 'calendar':
        if ($view == 'past') {
            $events = getPastEvents($sportFilter, $sortBy);
        } else {
            $events = getUpcomingEvents($sportFilter, $sortBy);
        }

        foreach ($events as &$event) {
            $event['formatted_date'] = getDayOfWeek($event['event_date']) . ', ' . formatDate($event['event_date']);
            $event['formatted_time'] = formatTime($event['event_time']);
        }
        unset($event);

        $sports = getAllSports();
        require_once __DIR__ . '/templates/calendar.php';
        break;
        
    case 'sports':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];

            if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Invalid security token. Refresh and try again.';
            }

            // Get and sanitize form inputs
            $name = sanitizeText($_POST['name'] ?? '', 100);
            $description = sanitizeText($_POST['description'] ?? '', 500);

            // Validate
            if (empty($name)) {
                $errors[] = 'Sport name is required.';
            }

            if (empty($errors)) {
                $sportId = addSport($name, $description);
                if ($sportId) {
                    $success = true;
                } else {
                    $errors[] = 'Failed to add sport. It may already exist.';
                }
            }
        }

        $sports = getAllSports();
        $csrfToken = generateCsrfToken();

        require_once __DIR__ . '/templates/sports.php';
        break;

    case 'sport_teams':
        $sportId = $_GET['sport_id'] ?? null;

        if (!$sportId || !validateId($sportId)) {
            header('Location: index.php?page=sports');
            exit;
        }

        $allSports = getAllSports();
        $sport = null;
        foreach ($allSports as $s) {
            if ($s['sport_id'] == $sportId) {
                $sport = $s;
                break;
            }
        }

        if (!$sport) {
            header('Location: index.php?page=sports');
            exit;
        }

        require_once __DIR__ . '/templates/sport_teams.php';
        break;

    case 'event':
        $eventId = $_GET['event_id'] ?? null;

        if (!validateId($eventId)) {
            header('Location: index.php?page=calendar');
            exit;
        }

        $event = getEventById($eventId);

        if (!$event) {
            header('Location: index.php?page=calendar');
            exit;
        }

        $event['formatted_date'] = getDayOfWeek($event['event_date']) . ', ' . formatDate($event['event_date']);
        $event['formatted_time'] = formatTime($event['event_time']);

        require_once __DIR__ . '/templates/event.php';
        break;

    case 'add_event':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errors = [];
            
            if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
                $errors[] = 'Invalid security token. Refresh and try again.';
            }
            
            // Get and sanitize form inputs
            $sportId = $_POST['sport_id'] ?? '';
            $venueId = $_POST['venue_id'] ?? '';
            $homeTeamId = $_POST['home_team_id'] ?? '';
            $awayTeamId = $_POST['away_team_id'] ?? '';
            $eventDate = $_POST['event_date'] ?? '';
            $eventTime = $_POST['event_time'] ?? '';
            $description = sanitizeText($_POST['description'] ?? '', 500);

            // Handle NEW VENUE
            if ($venueId === 'new') {
                $newVenueName = sanitizeText($_POST['new_venue_name'] ?? '', 100);
                $newVenueCity = sanitizeText($_POST['new_venue_city'] ?? '', 100);
                $newVenueAddress = sanitizeText($_POST['new_venue_address'] ?? '', 255);
                $newVenueCapacity = filter_var($_POST['new_venue_capacity'] ?? null, FILTER_VALIDATE_INT);
                
                if (empty($newVenueName) || empty($newVenueCity)) {
                    $errors[] = 'Venue name and city are required when creating a new venue.';
                } 
                else {
                    $venueId = addVenue($newVenueName, $newVenueCity, $newVenueAddress, $newVenueCapacity);
                    if (!$venueId) {
                        $errors[] = 'Failed to add venue.';
                    }
                }
            }
            
            // Handle NEW HOME TEAM
            if ($homeTeamId === 'new') {
                $newHomeTeamName = sanitizeText($_POST['new_home_team_name'] ?? '', 100);
                $newHomeTeamCity = sanitizeText($_POST['new_home_team_city'] ?? '', 100);
                
                if (empty($newHomeTeamName)) {
                    $errors[] = 'Home team name is required when creating a new team.';
                } 
                elseif (empty($sportId) || !validateId($sportId)) {
                    $errors[] = 'Please select a valid sport before creating a new home team.';
                } 
                else {
                    $homeTeamId = addTeam($sportId, $newHomeTeamName, $newHomeTeamCity);
                    if (!$homeTeamId) {
                        $errors[] = 'Failed to add home team.';
                    }
                }
            }
            
            // Handle NEW AWAY TEAM
            if ($awayTeamId === 'new') {
                $newAwayTeamName = sanitizeText($_POST['new_away_team_name'] ?? '', 100);
                $newAwayTeamCity = sanitizeText($_POST['new_away_team_city'] ?? '', 100);
                
                if (empty($newAwayTeamName)) {
                    $errors[] = 'Away team name is required when creating a new team.';
                } 
                elseif (empty($sportId) || !validateId($sportId)) {
                    $errors[] = 'Please select a valid sport before creating a new away team.';
                } 
                else {
                    $awayTeamId = addTeam($sportId, $newAwayTeamName, $newAwayTeamCity);
                    if (!$awayTeamId) {
                        $errors[] = 'Failed to add away team.';
                    }
                }
            }
            
            // Validate all required fields
            if (empty($errors)) {
                if (!validateId($sportId)) {
                    $errors[] = 'Please select a valid sport.';
                }
                
                if (!validateId($homeTeamId)) {
                    $errors[] = 'Please select a valid home team.';
                }
                
                if (!validateId($awayTeamId)) {
                    $errors[] = 'Please select a valid away team.';
                }
                
                if ($homeTeamId === $awayTeamId) {
                    $errors[] = 'Home and away teams must be different.';
                }
                
                if (!validateDate($eventDate)) {
                    $errors[] = 'Invalid event date format.';
                } elseif (!isValidFutureDate($eventDate)) {
                    $errors[] = 'Event date must be today or in the future.';
                }
                
                if (!validateTime($eventTime)) {
                    $errors[] = 'Invalid event time format.';
                }
                
                if (!empty($venueId) && $venueId !== '' && !validateId($venueId)) {
                    $errors[] = 'Invalid venue selected.';
                }
            }
            
            if (empty($errors)) {
                // Set venue to null if empty
                $venueIdValue = (empty($venueId) || $venueId === '') ? null : $venueId;
                
                $result = addEvent($sportId, $venueIdValue, $eventDate, $eventTime, $homeTeamId, $awayTeamId, $description);
                
                if ($result['success']) {
                    $success = true;
                } else {
                    $errors[] = 'Failed to create event: ' . ($result['error'] ?? 'Unknown error');
                }
            }
        }
        
        $sports = getAllSports();
        $venues = getAllVenues();
        $teams = getAllTeams();
        $csrfToken = generateCsrfToken();
        
        require_once __DIR__ . '/templates/add_event.php';
        break;
        
    default:
        $events = getAllEvents();
        require_once __DIR__ . '/templates/calendar.php';
        break;
}

require_once __DIR__ . '/templates/footer.php';
?>
