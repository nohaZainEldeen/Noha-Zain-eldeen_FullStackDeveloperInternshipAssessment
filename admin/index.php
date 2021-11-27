<?php
session_start();
$noNavbar = '';
$pageTitle = 'Login';
if (isset($_SESSION['Username'])) {
    header('Location: dashboard.php');
}
include 'init.php';

// check if user coming from http post request

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['admin'];
    $password = $_POST['pass'];
    $hashedPass = sha1($password);

    // check if the user exist in DB

    $stmt = $con->prepare("SELECT 
                                ID, Username, Password 
                            FROM 
                                admins 
                            where 
                                Username = ? 
                            AND 
                                Password = ? 
                            AND 
                                GroupID = 1
                            LIMIT 1");
    $stmt->execute(array($username, $hashedPass));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();

    // if count > 0 this mean the DB contain record about username

    if ($count > 0) {
        $_SESSION['Username'] = $username; //register session name
        $_SESSION['ID'] = $row['ID'];
        header('Location: dashboard.php'); // redirect to dashboard page
        exit();
    }
}
?>
<form class="login" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST">
    <h4 class="text-center">Admin Login</h4>
    <input class="form-control" type="text" name="admin" placeholder="Username" autocomplete="off">
    <input class="form-control" type="password" name="pass" placeholder="Password" autocomplete="new-password">
    <input class="btn btn-primary btn-block" type="submit" value="Login">
    <a href="signUp.php" class="text-info"> Sign Up </a>


</form>

<?php

include $tpl . 'footer.php';

?>