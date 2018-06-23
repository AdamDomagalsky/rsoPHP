<?PHP
        //we dont need here check
        require_once('functions.php');
	$user=session_check();
?>
<html>
<head>
        <title>Home</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/uikit/2.26.3/css/uikit.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
        <style type="text/css">
           body{ font: 14px sans-serif; text-align: center; }
        </style>
</head>
<body>
        <?PHP show_menu($user); ?>
        <div class="page-header">
                Hello <?PHP echo $user['username']; ?>! This is my website.
        </div>
</body>
</html>
