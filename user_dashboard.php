<?php
session_start(); // Start session to access session variables

function get_user_issue_book_count() {
    $connection = mysqli_connect("localhost", "root", "", "lms");
    if (!$connection) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
    $user_issue_book_count = 0;
    $student_id = isset($_SESSION['id']) ? $_SESSION['id'] : 0; // Get student ID from session or set to 0 if not found
    
    // Prepare the SQL query with a parameterized statement
    $query = "SELECT COUNT(*) AS user_issue_book_count FROM issued_books WHERE student_id = ?";
    
    // Use prepared statement to bind parameters and execute
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "i", $student_id); // "i" indicates integer type for student_id
    mysqli_stmt_execute($stmt);
    
    // Bind result variables
    mysqli_stmt_bind_result($stmt, $user_issue_book_count);
    mysqli_stmt_fetch($stmt);
    
    // Close statement and connection
    mysqli_stmt_close($stmt);
    mysqli_close($connection);
    
    return $user_issue_book_count;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="bootstrap-4.4.1/css/bootstrap.min.css">
    <script type="text/javascript" src="bootstrap-4.4.1/js/jquery_latest.js"></script>
    <script type="text/javascript" src="bootstrap-4.4.1/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="user_dashboard.php">Library Management System (LMS)</a>
            </div>
            <?php if (isset($_SESSION['name']) && isset($_SESSION['email'])) : ?>
                <span style="color: white"><strong>Welcome: <?php echo $_SESSION['name']; ?></strong></span>
                <span style="color: white"><strong>Email: <?php echo $_SESSION['email']; ?></strong></span>
            <?php else : ?>
                <span style="color: white"><strong>Welcome: Guest</strong></span>
                <span style="color: white"><strong>Email: guest@example.com</strong></span>
            <?php endif; ?>
            <ul class="nav navbar-nav navbar-right">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-toggle="dropdown">My Profile </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="view_profile.php">View Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="edit_profile.php">Edit Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="change_password.php">Change Password</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <br>
    <span><marquee>This is library management system. Library opens at 8:00 AM and closes at 8:00 PM</marquee></span>
    <br><br>
    <div class="row">
        <div class="col-md-3" style="margin: 25px">
            <div class="card bg-light" style="width: 300px">
                <div class="card-header">Book Issued</div>
                <div class="card-body">
                    <p class="card-text">No of books issued: <?php echo get_user_issue_book_count(); ?></p>
                    <a class="btn btn-success" href="view_issued_book.php">View Issued Books</a>
                </div>
            </div>
        </div>
        <div class="col-md-3"></div>
        <div class="col-md-3"></div>
    </div>
</body>
</html>
