<?php
include_once '../../includes/database.php';
include_once '../../includes/authentication.php';
include_once '../../includes/header.php';

// Check Admin Session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../../login.php");
    exit;
}

// Handle Add User
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $password, $role);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_users.php");
    exit;
}

// Fetch Users
$roleFilter = $_GET['role'] ?? 'all';
if ($roleFilter === 'all') {
    $users = $conn->query("SELECT * FROM users ORDER BY role ASC");
} else {
    $stmt = $conn->prepare("SELECT * FROM users WHERE role = ?");
    $stmt->bind_param("s", $roleFilter);
    $stmt->execute();
    $users = $stmt->get_result();
}
?>

<div class="container mt-5">
    <h2>Manage Users</h2>

    <!-- Filter -->
    <div class="mb-3">
        <form method="GET" class="d-flex align-items-center gap-2">
            <label class="form-label mb-0">Filter by Role:</label>
            <select name="role" onchange="this.form.submit()" class="form-select w-auto">
                <option value="all" <?= $roleFilter === 'all' ? 'selected' : '' ?>>All</option>
                <option value="admin" <?= $roleFilter === 'admin' ? 'selected' : '' ?>>Admin</option>
                <option value="teacher" <?= $roleFilter === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                <option value="student" <?= $roleFilter === 'student' ? 'selected' : '' ?>>Student</option>
                <option value="parent" <?= $roleFilter === 'parent' ? 'selected' : '' ?>>Parent</option>
            </select>
        </form>
    </div>

    <!-- Add User -->
    <form method="POST" class="mb-4">
        <h4>Add New User</h4>
        <div class="row g-2">
            <div class="col-md-3">
                <input type="text" name="name" placeholder="Full Name" class="form-control" required>
            </div>
            <div class="col-md-3">
                <input type="email" name="email" placeholder="Email" class="form-control" required>
            </div>
            <div class="col-md-2">
                <input type="password" name="password" placeholder="Password" class="form-control" required>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select" required>
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="teacher">Teacher</option>
                    <option value="student">Student</option>
                    <option value="parent">Parent</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" name="add_user" class="btn btn-primary w-100">Add User</button>
            </div>
        </div>
    </form>

    <!-- Users Table -->
    <div class="card">
        <div class="card-header">User List</div>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $sn = 1; while ($user = $users->fetch_assoc()): ?>
                <tr>
                    <td><?= $sn++ ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><span class="badge bg-secondary"><?= ucfirst($user['role']) ?></span></td>
                    <td><?= date("d M Y", strtotime($user['created_at'] ?? 'now')) ?></td>
                    <td>
                        <a href="?delete=<?= $user['id'] ?>" onclick="return confirm('Delete user?')" class="btn btn-sm btn-danger">Delete</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
