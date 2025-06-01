<?php
require_once '../../includes/authentication.php';
require_once '../../includes/database.php';

if (!isAdmin()) {
    header('HTTP/1.1 403 Forbidden');
    echo "Access denied. You are not authorized to view this page.";
    exit();
}

function fetchCount($conn, $table, $role = null) {
    if ($role) {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM $table WHERE role = ?");
        $stmt->bind_param("s", $role);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['total'];
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM $table");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        return $result['total'];
    }
}

$studentCount = fetchCount($conn, 'users', 'student');
$teacherCount = fetchCount($conn, 'users', 'teacher');
$parentCount = fetchCount($conn, 'users', 'parent');
$classCount = fetchCount($conn, 'classes');

include_once '../../includes/header.php';
?>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<div class="container py-5">
    <!-- Hero Section -->
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold">Welcome, <?= htmlspecialchars($_SESSION['user']['name']) ?></h1>
        <p class="lead text-muted">Manage and monitor all school operations from one place.</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-people-fill fs-1"></i>
                    <h5 class="card-title mt-2">Students</h5>
                    <p class="display-6"><?= $studentCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-person-badge-fill fs-1"></i>
                    <h5 class="card-title mt-2">Teachers</h5>
                    <p class="display-6"><?= $teacherCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-house-heart-fill fs-1"></i>
                    <h5 class="card-title mt-2">Parents</h5>
                    <p class="display-6"><?= $parentCount ?></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="bi bi-door-open-fill fs-1"></i>
                    <h5 class="card-title mt-2">Classes</h5>
                    <p class="display-6"><?= $classCount ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Cards -->
    <div class="row g-4">
        <div class="col-md-3">
            <a href="manage_users.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-person-lines-fill fs-1 text-primary"></i>
                        <h5 class="mt-3">Manage Users</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="manage_classes.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-easel-fill fs-1 text-success"></i>
                        <h5 class="mt-3">Manage Classes</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="announcements.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-megaphone-fill fs-1 text-warning"></i>
                        <h5 class="mt-3">Announcements</h5>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="reports.php" class="text-decoration-none">
                <div class="card h-100 shadow-sm text-center">
                    <div class="card-body">
                        <i class="bi bi-bar-chart-fill fs-1 text-danger"></i>
                        <h5 class="mt-3">Reports</h5>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="text-center mt-5">
        <a href="../../logout.php" class="btn btn-outline-danger btn-lg">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
