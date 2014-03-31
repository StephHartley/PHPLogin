<?php

require_once('class.UserSession.php');
require_once('class.Database.php');

$user = new UserSession(new Database());

if(!$user->isLoggedIn())
{
    $path = $_SERVER['HTTP_HOST'];
    $path .= rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
    $path .= '/UserSessionPage1.php';
    header('Location: http://' . $path);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User session assignment - Page 2</title>
</head>
<body>
<p><a href="UserSessionPage1.php">Go to page 1</a></p>
<ul>
    <li>Username:
        <?php echo $user->username() . ' (remembered from previous page)';
        ?>
    </li>
</ul>

</body>
</html>
