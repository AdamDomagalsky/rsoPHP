<?php
    // Initialize the session
    require_once('functions.php');
    $user=session_check();
    if (!isset($user['id'])) {
        header("location: index.php");
        exit;
    }
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wall</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    
    <?PHP show_menu($user); ?>
<div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($user['username']); ?></b>. Wall is in build process.</h1>
    </div>
    <p><a href="index.php" class="btn btn-success">Go Back to index.php</a></p>
    <p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>


    
</body>
</html>
