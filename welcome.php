<?php
    // Initialize the session
    session_start();
    require_once('session.php');
    $logged = check_session();
    require_once('menu.php');
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/js/uikit.min.js">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b>. Welcome to our site.</h1>
    </div>
    <?PHP show_menu($logged); ?>
    <p><a href="index.php" class="btn btn-success">Go Back to index.php</a></p>
    <p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>
</body>
</html>
