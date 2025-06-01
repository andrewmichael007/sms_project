 
<?php

$host = 'localhost';
$user = 'project';
$password = 'project';
$db = 'school_management';

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
