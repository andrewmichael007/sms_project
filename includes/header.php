<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>School Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- ✅ Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- ✅ SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css">

    <!-- ✅ Custom CSS (optional) -->
    <link rel="stylesheet" href="/assets/css/styles.css">



    <style>
        body {
            background-color: #f4f6f9;
        }

        .card h5 {
            font-weight: 600;
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.3rem;
        }
    </style>
</head>
<body>

<!-- ✅ Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <i class="fas fa-school me-2"></i> School Management
        </a>
        <div class="d-flex">
            <?php if (isset($_SESSION['user'])): ?>
                <span class="text-white me-3">
                    <?= htmlspecialchars($_SESSION['user']['name']) ?> (<?= $_SESSION['user']['role'] ?>)
                </span>
                <a class="btn btn-outline-light btn-sm" href="/logout.php">Logout</a>
            <?php endif; ?>
        </div>
    </div>
</nav>
