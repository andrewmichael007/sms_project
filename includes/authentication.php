 
<?php
session_start();

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
