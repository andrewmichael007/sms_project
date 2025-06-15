<?php
include_once "../../includes/authentication.php";
include_once "../../includes/database.php";
include_once "../../includes/functions.php";
include_once "../../includes/header.php";
include_once "../../includes/roles.php";


// if user is not logged in or not an admin, redirect to dashboard
if (!isAdmin()) {
    header("Location: ../../login.php");
    exit("Access denied.");
}

// handling add announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_announcement'])) {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if ($title && $message) {
        createAnnouncement($conn, $title, $message);
    }
    header("Location: announcements.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    deleteAnnouncement($conn, $_GET['delete']);
    header("Location: announcements.php");
    exit;
}

// Fetch all announcements
$announcements = getAllAnnouncements($conn);
?>

<div class="container mt-5">
    <h2 class="mb-4">📣 Manage Announcements</h2>

    <!-- Form to Add New Announcement -->
    <form method="POST" class="card p-4 mb-5 shadow-sm">
        <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Announcement Title" required>
        </div>
        <div class="mb-3">
            <textarea name="message" class="form-control" placeholder="Announcement Message" rows="4" required></textarea>
        </div>
        <button type="submit" name="add_announcement" class="btn btn-primary">
            <i class="fas fa-bullhorn"></i> Post Announcement
        </button>
    </form>

    <!-- List of Announcements -->
    <div class="card shadow-sm">
        <div class="card-header bg-light">
            <strong>All Announcements</strong>
        </div>
        <ul class="list-group list-group-flush">
            <?php foreach ($announcements as $row): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($row['title']) ?></h5>
                            <p class="mb-1"><?= nl2br(htmlspecialchars($row['content'])) ?></p>
                            <small class="text-muted">Posted on <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                        </div>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this announcement?')">
                           <i class="fas fa-trash"></i>
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
