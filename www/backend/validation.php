<?php
// Input validation functions

/**
 * Validate and sanitize text input
 */
function sanitizeText($input, $maxLength = 255) {
    $input = trim($input);
    $input = strip_tags($input); // Remove HTML tags
    return substr($input, 0, $maxLength);
}

/**
 * Validate date format (YYYY-MM-DD)
 */
function validateDate($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

/**
 * Validate time format (HH:MM)
 */
function validateTime($time) {
    return preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $time);
}

/**
 * Validate integer ID
 */
function validateId($id) {
    return filter_var($id, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
}

/**
 * Check if date is not in the past
 */
function isValidFutureDate($date) {
    return strtotime($date) >= strtotime('today');
}
?>