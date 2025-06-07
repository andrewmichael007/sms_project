
<!-- sessions are used to track user logins and functions on the website -->
<?php
//start the session
session_start();

//checking if user is logged in and directing them to their seesion

//if user is not set, go to header else...
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit();
}

function isAdmin() {
    return $_SESSION['user']['role'] === 'admin';
}

function isTeacher() {
    return $_SESSION['user']['role'] === 'teacher';
}

function isStudent() {
    return $_SESSION['user']['role'] === 'student';
}

function isParent() {
    return $_SESSION['user']['role'] === 'parent';
}
?>
