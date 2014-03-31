<?php
require_once('class.UserSession.php');
require_once('class.Database.php');


$user = new UserSession(new Database());
if($_POST)
{
    if($_POST['submit'] == 'login')
    {
        $user->logIn($_POST['name'], $_POST['password']);
    }
    else
    {
        $user->logOut();
    }
}
$username = '';
$status = 'You are not logged in';
$authorisation = 'No authorisation';
if($user->isLoggedIn())
{
 $username = $user->username();
 $status = 'You are logged in';
 $authorisation = $user->authorisation();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User session assignment</title>
</head>
<body>

<p>Welcome <?php echo $username; ?></p>
<p><a href="UserSessionPage2.php">Go to page 2</a></p>
<ul>
    <li>Status: <?php echo $status; ?></li>
    <li>User Level: <?php echo $authorisation; ?></li>
</ul>

<form action="UserSessionPage1.php" method="POST">
    <?php
    if(!$user->isLoggedIn())
    {
        ?>
        <fieldset>
            <legend>Log in</legend>
            <label for="name">Name: </label>
            <input type="text" name="name" id="name">
            <label for="password">Password: </label>
            <input type="password" name="password" id="password">
            <button type="submit" name="submit" value="login">
                Login
            </button>
        </fieldset>
    <?php
    }
    else
    {
        ?>
        <p>
            <button type="submit" name="submit" value="logout">
                Logout
            </button>
        </p>
    <?php
    }
    ?>
</form>

</body>
</html>