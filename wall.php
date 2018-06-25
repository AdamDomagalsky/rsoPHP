<?php
    // Include config file
    $host = gethostname();
    require_once "{$host}config.php";

    // Initialize the session
    require_once('functions.php');
    $user=session_check();

    if (!isset($user['id'])) {
        header("location: index.php");
        exit;
    }


    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $post = $_POST['posts'];
        $query = "INSERT INTO posts (content,user_id) VALUE ('$post','".$user['id']."')";
        $dbSlave = mysqli_connect(DB_SERVER_SLAVE, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $res = mysqli_query($dbSlave, $query);

        mysqli_close($dbSlave);
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
        body{ font: 14px sans-serif; text-align: left; }
    </style>
</head>
<body>
    
    <?PHP show_menu($user); ?>

    <form method="post" action="wall.php">
        <br><input type="text" name="posts" maxlength="255"></br>
        <br><input type="submit" name="send"value="SEND POST"></br>
    </form>



<div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($user['username']); ?></b>. Wall is in build process.</h1>
    </div>

<?PHP
    //empty arr of posts
    $posts = array();
    // if we dont have podst in redis - go and my sql query then set to redis
    if ($redisPosts = redis_get_json("posts") === NULL) {
        $query = "select content, created_at, user_id  from posts ORDER BY created_at DESC LIMIT 10";
        $dbSlave = mysqli_connect(DB_SERVER_SLAVE, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
        $res = mysqli_query($dbSlave, $query);
        $arrayTo = array();

        while($row = mysqli_fetch_array($res)) {
            $posts[] = $row; 
        }
        echo 'redis_set_json'."<br>";
        redis_set_json("posts", $posts, 10);

    } else { // we do have in redis so get it
        echo 'redis_get_json'."<br>";
        $posts = redis_get_json("posts");
    }
    
    // print post
    $i = 1;
    foreach ($posts as &$post) {
        echo "no. ".$i."   | ".$post['created_at'].' | UserID: '.$post['user_id'].' | content: '.$post['content']."<br><br>";
        $i++;
    }

    mysqli_close($dbSlave);
?>

    <p><a href="index.php" class="btn btn-success">Go Back to index.php</a></p>
    <p><a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a></p>
</body>
</html>
