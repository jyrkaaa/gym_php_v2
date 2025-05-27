<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="./../files/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="./../files/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="./../files/favicon-16x16.png">
    <link rel="manifest" href="./../files/site.webmanifest">
    <style>
        .btn-outline-light {
            margin-left: 10px;
        }
    </style>
</head>
<body class="bg-light">

<nav class="navbar bg-dark border-bottom border-body py-3" data-bs-theme="dark">
    <form method="GET" class="d-inline">
        <button type="submit" name="" class="btn btn-outline-light">Go Back</button>
    </form>
</nav>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title text-center">Register</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                        </div>
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                        </div>
                        <div class="mb-3">
                            <label for="authKey" class="form-label">Authentication Key</label>
                            <input type="text" class="form-control" id="authKey" name="authKey" placeholder="Enter your authentication key" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" name="register">Register</button>
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
