<?php

function show_menu($user)
{
echo '
<nav class="uk-navbar">
    <ul class="uk-navbar-nav">';
                if ($user==NULL or $user['loggedin']!=true)
                        echo '<li class="uk-active"><a href="login.php">Login</a></li>';
                else
                        echo '<li class="uk-active"><a href="logout.php">Logout</a></li>
                              <li class="uk-parent"><a href="welcome.php">Welcome</a></li>
                        ';
echo '        <li class="uk-parent"><a href="index.php">Home</a></li>
    </ul>
</nav>';
	echo $user;
	echo '<pre>'; print_r($user); echo '</pre>';
}

?>
