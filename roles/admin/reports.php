 
<?php
include_once '../../includes/database.php';
include_once '../../includes/authentication.php';
include_once '../../includes/header.php';

// Total students
$total_students = $conn->query("SELECT COUNT(*) AS total FROM students")->fetch_assoc()['total'];

// Total classes
$total_classes = $conn->query("SELECT COUNT(*) AS total FROM classes")->fetch_assoc()['total'];

// Students per class
$students_per_class = $conn->query
(" SELECT c.name as class_name, COUNT(s.id) as student_count
    FROM classes c
    LEFT JOIN students s ON c.id = s.class_id
    GROUP BY c.id "
);

// Teachers and their assigned classes
$teachers_classes = $conn->query
(" SELECT c.name as class_name, u.name as teacher_name
    FROM classes c
    LEFT JOIN teachers t ON c.teacher_id = t.id 
    LEFT JOIN users u ON t.user_id = u.id "
);
?>

<div class="container mt-5">
    <h2>Reports Dashboard</h2>

    <div class="row text-center mb-4">
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h4>Total Students</h4>
                <p class="display-6"><?= $total_students ?></p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 shadow-sm">
                <h4>Total Classes</h4>
                <p class="display-6"><?= $total_classes ?></p>
            </div>
        </div>
    </div>

    <!-- Students Per Class -->
    <div class="card mb-4">
        <div class="card-header">Students per Class</div>
        <div class="card-body p-0">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Student Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $students_per_class->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                            <td><?= $row['student_count'] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Teacher Assignments -->
    <div class="card">
        <div class="card-header">Teachers & Their Classes</div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                    <tr>
                        <th>Class Name</th>
                        <th>Assigned Teacher</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $teachers_classes->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['class_name']) ?></td>
                            <td><?= $row['teacher_name'] ?? 'Not Assigned' ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include_once '../../includes/footer.php'; ?>
