<?php
require_once "../../includes/authentication.php";
require_once "../../includes/database.php";
require_once "../../includes/functions.php";

//check if user is not a teacher
if (!isTeacher()) {
    exit("Access Denied");
}

$teacher_id = $_SESSION['user_id'];
$classes = getClassesByTeacher($conn, $teacher_id);
$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_id'])) {
    $class_id = $_POST['class_id'];
    $students = getStudentsByClass($conn, $class_id);
}

if (isset($_POST['submit_attendance'])) {
    $records = [];
    foreach ($_POST['attendance'] as $student_id => $status) {
        $records[] = [
            'student_id' => $student_id,
            'class_id' => $_POST['class_id'],
            'date' => $_POST['date'],
            'status' => $status,
            'teacher_id' => $teacher_id
        ];
    }

    recordAttendance($conn, $records);
    echo "<script>alert('Attendance recorded successfully!');window.location='attendance.php';</script>";
}

include '../includes/header.php';
?>

<div class="container mt-4">
    <h2>Mark Attendance</h2>

    <form method="post" class="mb-3">
        <label>Select Class:</label>
        <select name="class_id" onchange="this.form.submit()" class="form-select w-auto d-inline">
            <option value="">-- Choose --</option>
            <?php while ($row = $classes->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>" <?= ($_POST['class_id'] ?? '') == $row['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

    <?php if (!empty($students) && $_POST['class_id']): ?>
        <form method="post">
            <input type="hidden" name="class_id" value="<?= $_POST['class_id'] ?>">
            <input type="hidden" name="date" value="<?= date('Y-m-d') ?>">

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1; while ($student = $students->fetch_assoc()): ?>
                        <tr>
                            <td><?= $i++ ?></td>
                            <td><?= htmlspecialchars($student['name']) ?></td>
                            <td>
                                <select name="attendance[<?= $student['id'] ?>]" class="form-select">
                                    <option value="present">Present</option>
                                    <option value="absent">Absent</option>
                                </select>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <button type="submit" name="submit_attendance" class="btn btn-success">Submit Attendance</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>

