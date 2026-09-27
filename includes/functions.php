<?php


if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/** Escape output before printing it into HTML (prevents XSS). */
function h($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/** Trim + basic-clean a piece of user input. */
function clean($value) {
    return trim($value ?? '');
}

/** Is someone currently logged in? */
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

/** Send the visitor to login.php if they are not authenticated. Call at the top of protected pages. */
function require_login() {
    if (!is_logged_in()) {
        $_SESSION['flash_error'] = 'Please log in to continue.';
        header('Location: ' . base_url('auth/login.php'));
        exit;
    }
}

/**
 * Build a path relative to the project root, regardless of which
 * folder the current script lives in (root vs /auth).
 */
function base_url($path = '') {
    // Detect whether we're currently inside the /auth folder
    $inAuth = strpos($_SERVER['SCRIPT_NAME'], '/auth/') !== false;
    $prefix = $inAuth ? '../' : '';
    return $prefix . $path;
}

/** Store a one-time flash message to show after a redirect. */
function set_flash($type, $message) {
    $_SESSION['flash_' . $type] = $message;
}

/** Read + clear a flash message of a given type ('success' or 'error'). */
function get_flash($type) {
    $key = 'flash_' . $type;
    if (!empty($_SESSION[$key])) {
        $msg = $_SESSION[$key];
        unset($_SESSION[$key]);
        return $msg;
    }
    return null;
}
