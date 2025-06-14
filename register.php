<?php
//start session
session_start();

require "includes/database.php";
require "includes/roles.php";
// require "includes/check_admin.php";
// require "includes/authentication.php"; // checks if logged in + admin-only access

// check if any admin exists first
// Allow registration if no admins exist yet

// This is to ensure that at least one admin can register
// If an admin exists, then only admins can register new users
// If no admin exists, anyone can register
// This is to prevent unauthorized access to the registration page
$ROLE_ADMIN = ROLE_ADMIN;

$stmt_check = $conn->prepare(" SELECT COUNT(*) FROM users WHERE role = ? ");
$stmt_check->bind_param("s", $ROLE_ADMIN);
$stmt_check->execute();
$result = $stmt_check->get_result();
$admin_count = $result->fetch_row()[0];

$_SESSION['admin_count'] = $admin_count; //storing in session for future use


// echo "Admin count: " . $admin_count;

// enforce admin-only if admin exists
// if ($admin_count > 0) {
//     if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== ROLE_ADMIN) {
//     header("Location: login.php");
//     exit;
//     }
// }

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    //if value role is missing, set the default role to student
    $role = $_POST['role'] ?? ROLE_STUDENT;

    //check if password match
    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    //elseif check if there's no role in the array
    } elseif (!in_array($role, [ROLE_ADMIN, ROLE_TEACHER, ROLE_STUDENT, ROLE_PARENT])) {
        $error = "Invalid role selected.";
    //else hash the password
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Check if email already exists
        $stmt = $conn->prepare(" SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $error = "Email already registered.";
        } else {
            $stmt = $conn->prepare(" INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $name, $email, $hashed_password, $role);
            if ($stmt->execute()) {
                $_SESSION['registration_success'] = "Registration Successfull.". $role;
            } else {
                $_SESSION['registration_error'] = "Registraion failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - School Management System </title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css   " rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4">
                <h3 class="text-center mb-3"> Register <?php echo "(Admins: ". $admin_count. ")"; ?> </h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="name">Full Name</label>
                        <input type="text" name="name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="mb-3">
                        <label for="email">Email address</label>
                        <input type="email" name="email" class="form-control" required placeholder="you@example.com">
                    </div>
                    <div class="mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>    
                    
                    <div class="mb-3">
                        <label for="role">Select Role</label>
                        <select name="role" class="form-control" required>

                            <!-- server side checking if admin doesn't exist, allow admin to pop up -->
                            <?php if ($admin_count == 0): ?>
                                <option value="<?= ROLE_ADMIN ?>">admin</option>
                            <?php endif; ?>

                            <option value="<?= ROLE_TEACHER ?>">teacher</option>
                            <option value="<?= ROLE_STUDENT ?>">student</option>
                            <option value="<?= ROLE_PARENT ?>">parent</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success w-30 d-block mx-auto">Register</button>
                </form>

                <div class="mt-3 text-center">
                    <small>Already registered? <a href="login.php">Login here</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>




