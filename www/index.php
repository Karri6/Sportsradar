<?php
// Main application entry point

// Load configuration and functions
require_once __DIR__ . '/backend/config.php';
require_once __DIR__ . '/backend/events.php';

// Get the page parameter
$page = $_GET['page'] ?? 'calendar';
$view = $_GET['view'] ?? 'all';

// Load header
require_once __DIR__ . '/templates/header.php';

// Route to the appropriate page
switch ($page) {
    case 'calendar':
        // Fetch events based on view
        if ($view == 'upcoming') {
            $events = getUpcomingEvents();
        } elseif ($view == 'past') {
            $events = getPastEvents();
        } else {
            $events = getAllEvents();
        }
        
        // Load calendar template
        require_once __DIR__ . '/templates/calendar.php';
        break;
        
    case 'add_event':
        // Placeholder
        echo '<h1>Add Event</h1>';
        echo '<p class="alert alert-info">Add event form will be here soon!</p>';
        break;
        
    default:
        // Default to calendar
        $events = getAllEvents();
        require_once __DIR__ . '/templates/calendar.php';
        break;
}

// Load footer
require_once __DIR__ . '/templates/footer.php';
?>