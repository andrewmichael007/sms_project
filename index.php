 <?php
session_start();

if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    header("Location: roles/{$role}/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Welcome - School Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5 text-center">
    <h1 class="mb-4">Welcome to the School Management System</h1>
    <a href="login.php" class="btn btn-primary me-2">Login</a>
    <a href="register.php" class="btn btn-success">Register</a>
</div>

</body>
</html>

