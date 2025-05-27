<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="./../files/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./../files/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./../files/favicon-16x16.png">
    <link rel="manifest" href="./../files/site.webmanifest">
</head>
<body class="bg-light">
<!-- Navigation bar -->
<nav class="navbar bg-dark border-bottom border-body py-3" data-bs-theme="dark">
    <form method="get">
    <div class="container-fluid">
        <a class="navbar-brand">Login</a>
        <button class="btn btn-outline-light" type="submit" name="go_reg">Register</button>
    </div>
    </form>
</nav>
<!-- Main container -->
<?php if ($timeout_message): ?>
    <div class="alert alert-warning" role="alert">
        <?= htmlspecialchars($timeout_message) ?>
    </div>
<?php endif; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Welcome Back</h3>
                    <form method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-dark w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS (optional, for interactive components) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

