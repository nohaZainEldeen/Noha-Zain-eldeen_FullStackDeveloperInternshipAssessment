<?php

// manage Admins page

session_start();
$noNavbar = '';
$pageTitle = 'signUp';
if (empty($_SESSION[''])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'Add';

    if ($do == 'Add') {  //Add Admin Page 
?>

        <div class="container">
            <h1 class="text-center">Sign Up As Admin</h1>
            <div class="container">
                <form action="?do=Insert" method="POST">

                    <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" name="username" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="password form-control" autocomplete="new-password" required="required" />
                        <i class="show-pass fa fa-eye "></i>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Repeat Your Password</label>
                        <input type="password" name="password2" class="password form-control" autocomplete="new-password" required="required" />

                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Email</label>
                        <input type="email" name="email" class="form-control" required="required">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Full Name</label>
                        <input type="text" name="fullName" class="form-control" required="required">
                    </div>

                    <input type="submit" value="Sign Up" class="btn btn-primary" />
                </form>
            </div>
            <?php

        } elseif ($do == 'Insert') {
            // Insert Admin into DB

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                echo '<h1 class="text-center">ADD New admin</h1>';
                echo "<div class='container'>";


                $user = $_POST['username'];
                $pass = $_POST['password'];
                $pass2 = $_POST['password2'];
                $email = $_POST['email'];
                $fullName = $_POST['fullName'];

                $hashPass = sha1($_POST['password']);


                // Validate the form

                $formErrors = array();

                if (strlen($user) < 4) {
                    $formErrors[] = 'Username can not be less than <strong>4 characters</strong>';
                }
                if ($pass !== $pass2) {
                    $formErrors[] = 'Password <strong> Wrong </strong>';
                }

                if (strlen($user) > 20) {
                    $formErrors[] = 'Username can not be more than <strong>20 characters</strong>';
                }

                if (empty($user)) {
                    $formErrors[] = 'Username can not be <strong> empty </strong>';
                }
                if (empty($pass)) {
                    $formErrors[] = ' Password can not be <strong> empty </strong> ';
                }
                if (empty($fullName)) {
                    $formErrors[] = ' Name can not be <strong> empty </strong> ';
                }
                if (empty($email)) {
                    $formErrors[] = ' Email can not be <strong> empty </strong> ';
                }

                foreach ($formErrors as $error) {
                    echo '<div class="alert alert-danger" >' . $error . '</div>';
                }

                // if no errors -> update db

                if (empty($formErrors)) {

                    $stmt = $con->prepare("INSERT INTO 
													admins(Username, Password, Email, FullName,GroupId)
												VALUES(:zuser, :zpass, :zmail, :zname,1) ");
                    $stmt->execute(array(

                        'zuser'     => $user,
                        'zpass'     => $hashPass,
                        'zmail'     => $email,
                        'zname'     => $fullName,


                    ));

                    echo "<div class= 'alert alert-success'>" . $stmt->rowCount() . '  Record Inserted' . " </div>";
                    echo '<a href="index.php" class="text-info"> Go To Login Page </a>';
                }
            } else {
            ?>
                <div class="container">
                    <?php
                    echo "<br/>";
                    $errorMsg = 'SORRY! SOMETHING WRONG';
                    redirectHome($errorMsg);
                    ?>
                </div>
    <?php
            }
            echo '</div>';
        }


        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }
