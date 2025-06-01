<?php
// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Check if user is admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}

// Check if user is teacher
function isTeacher() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'teacher';
}

// Check if user is student
function isStudent() {
    return isLoggedIn() && $_SESSION['user']['role'] === 'student';
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit();
}
?>
