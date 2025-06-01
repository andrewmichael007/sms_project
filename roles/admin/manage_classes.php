<?php
include_once '../../includes/database.php';
include_once '../../includes/authentication.php';
include_once '../../includes/header.php';

// ======== EDIT HANDLER (GET) ========
$edit_mode = false;
$edit_class = null;

if (isset($_GET['edit'])) {
    $edit_mode = true;
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $edit_class = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

// ======== UPDATE HANDLER (POST) ========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_class'])) {
    $class_id = $_POST['class_id'];
    $class_name = trim($_POST['class_name']);
    $class_teacher_id = $_POST['class_teacher_id'] ?? null;

    $stmt = $conn->prepare("UPDATE classes SET class_name = ?, class_teacher_id = ? WHERE id = ?");
    $stmt->bind_param("sii", $class_name, $class_teacher_id, $class_id);
    $stmt->execute();
    $stmt->close();

    header("Location: manage_classes.php");
    exit;
}

// ======== ADD CLASS HANDLER ========
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_class'])) {
    $class_name = trim($_POST['class_name']);
    $class_teacher_id = $_POST['class_teacher_id'] ?? null;

    if ($class_name) {
        $stmt = $conn->prepare("INSERT INTO classes (class_name, class_teacher_id) VALUES (?, ?)");
        $stmt->bind_param("si", $class_name, $class_teacher_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: manage_classes.php");
    exit;
}

// ======== DELETE CLASS HANDLER ========
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_classes.php");
    exit;
}

// ======== FETCH DATA ========
$classes = $conn->query
( " SELECT c.*, u.name as teacher_name 
    FROM classes c 
    LEFT JOIN teachers t ON c.teacher_id = t.id 
    LEFT JOIN users u ON t.user_id = u.id " 
);

$teachers = $conn->query
(" SELECT t.id, u.name 
    FROM teachers t 
    JOIN users u 
    ON t.user_id = u.id"
);

?>

<div class="container mt-5">
    <h2>Manage Classes</h2>

    <!-- CLASS FORM -->
    <form method="POST" class="mb-4">
        <div class="row g-2">
            <input type="hidden" name="class_id" value="<?= $edit_mode ? $edit_class['id'] : '' ?>">

            <div class="col-md-4">
                <input type="text" name="class_name" class="form-control" placeholder="Class Name" required
                    value="<?= $edit_mode ? htmlspecialchars($edit_class['class_name']) : '' ?>">
            </div>

            <div class="col-md-4">
                <select name="class_teacher_id" class="form-select">
                    <option value="">Assign Teacher (optional)</option>
                    <?php
                    $teachers->data_seek(0); // Reset pointer to start
                    while ($t = $teachers->fetch_assoc()):
                        $selected = ($edit_mode && $edit_class['class_teacher_id'] == $t['id']) ? 'selected' : '';
                    ?>
                        <option value="<?= $t['id'] ?>" <?= $selected ?>><?= htmlspecialchars($t['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="col-md-4">
                <?php if ($edit_mode): ?>
                    <button type="submit" name="update_class" class="btn btn-warning">Update Class</button>
                    <a href="manage_classes.php" class="btn btn-secondary">Cancel</a>
                <?php else: ?>
                    <button type="submit" name="add_class" class="btn btn-primary">Add Class</button>
                <?php endif; ?>
            </div>
        </div>
    </form>

    <!-- CLASS LIST TABLE -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Class Name</th>
                <th>Class Teacher</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['class_name']) ?></td>
                    <td><?= $row['teacher_name'] ?? 'Not Assigned' ?></td>
                    <td>
                        <a href="?edit=<?= $row['id'] ?>" class="btn btn-sm btn-info">Edit</a>
                        <a href="?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this class?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include_once '../../includes/footer.php'; ?>
