 <?php
include_once '../../includes/database.php';
include_once '../../includes/authentication.php';
include_once '../../includes/header.php';

// Handle Add Announcement
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_announcement'])) {
    $title = trim($_POST['title']);
    $message = trim($_POST['message']);

    if ($title && $message) {
        $stmt = $conn->prepare("INSERT INTO announcements (title, message) VALUES (?, ?)");
        $stmt->bind_param("ss", $title, $message);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: announcements.php");
    exit;
}

// Handle Delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: announcements.php");
    exit;
}

// Fetch announcements
$announcements = $conn->query("SELECT * FROM announcements ORDER BY created_at DESC");
?>

<div class="container mt-5">
    <h2>Manage Announcements</h2>

    <!-- Add New Announcement -->
    <form method="POST" class="mb-4">
        <div class="mb-3">
            <input type="text" name="title" class="form-control" placeholder="Announcement Title" required>
        </div>
        <div class="mb-3">
            <textarea name="message" class="form-control" placeholder="Announcement Message" rows="4" required></textarea>
        </div>
        <button type="submit" name="add_announcement" class="btn btn-success">Post Announcement</button>
    </form>

    <!-- Display Announcements -->
    <div class="card">
        <div class="card-header">All Announcements</div>
        <ul class="list-group list-group-flush">
            <?php while ($row = $announcements->fetch_assoc()): ?>
                <li class="list-group-item">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h5><?= htmlspecialchars($row['title']) ?></h5>
                            <p><?= nl2br(htmlspecialchars($row['message'])) ?></p>
                            <small class="text-muted">Posted on: <?= date("F j, Y, g:i a", strtotime($row['created_at'])) ?></small>
                        </div>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-outline-danger"
                           onclick="return confirm('Delete this announcement?')">Delete</a>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>

