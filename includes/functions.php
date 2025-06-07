<?php
// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user']);
}

// Sanitize input
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

// Redirect helper
function redirect($url) {
    header("Location: $url");
    exit();
}

// Add a new user
function addUser($conn, $data) {
    $stmt = $conn->prepare("INSERT INTO users (name, email, role, password) VALUES (?, ?, ?, ?)");
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    $stmt->bind_param("ssss", $data['name'], $data['email'], $data['role'], $hashedPassword);
    return $stmt->execute();
}

// Get all users (with optional role filter)
function getUsers($conn, $role = null) {
    if ($role) {
        $stmt = $conn->prepare("SELECT * FROM users WHERE role = ?");
        $stmt->bind_param("s", $role);
    } else {
        $stmt = $conn->prepare("SELECT * FROM users");
    }
    $stmt->execute();
    return $stmt->get_result();
}

// Update a user
function updateUser($conn, $id, $data) {
    $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssi", $data['name'], $data['email'], $data['role'], $id);
    return $stmt->execute();
}

// Delete a user
function deleteUser($conn, $id) {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

// Get classes taught by a teacher
function getClassesByTeacher($conn, $teacher_id) {
    $stmt = $conn->prepare("SELECT id, name FROM classes WHERE teacher_id = ?");
    $stmt->bind_param("i", $teacher_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Get students in a class
function getStudentsByClass($conn, $class_id) {
    $stmt = $conn->prepare("SELECT id, name FROM users WHERE role = 'student' AND class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    return $stmt->get_result();
}

// Save attendance records
function recordAttendance($conn, $records) {
    $stmt = $conn->prepare("INSERT INTO attendance (student_id, class_id, date, status, teacher_id)
                            VALUES (?, ?, ?, ?, ?) 
                            ON DUPLICATE KEY UPDATE status = VALUES(status)");

    foreach ($records as $rec) {
        $stmt->bind_param("iissi", $rec['student_id'], $rec['class_id'], $rec['date'], $rec['status'], $rec['teacher_id']);
        $stmt->execute();
    }
    return true;
}

function getChildIdByParent($conn, $parent_id) {
    $stmt = $conn->prepare("SELECT child_id FROM users WHERE id = ?");
    $stmt->bind_param("i", $parent_id);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['child_id'] ?? null;
}

?>
