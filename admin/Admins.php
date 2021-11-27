<?php

// manage Admins page

session_start();
$pageTitle = 'Admins';
if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    if ($do == 'manage') {
        // Show all users
        $stmt = $con->prepare("SELECT * FROM admins WHERE GroupID != 1");
        $stmt->execute();
        $rows = $stmt->fetchAll();



?>
        <h1 class="text-center">Manage Members</h1>
        <div class="container">
            <div class="table-responsive">
                <table class="main-table text-center table table-bordered">
                    <tr>
                        <td>#ID</td>
                        <td>Username</td>
                        <td>Email</td>
                        <td>Full Name</td>
                        <td>Control</td>
                    </tr>
                    <?php

                    foreach ($rows as $row) {
                        echo '<tr>';
                        echo '<td>' . $row['ID'] . '</td>';
                        echo '<td>' . $row['Username'] . '</td>';
                        echo '<td>' . $row['Email'] . '</td>';
                        echo '<td>' . $row['FullName'] . '</td>';
                        echo '<td> <a href="Admins.php?do=Edit&ID=' . $row['ID'] . '" class="btn btn-success">Edit</a>
                              <a href="Admins.php?do=Delete&ID=' . $row['ID'] . '" class="btn btn-danger confirm">Delete</a></td>';
                        echo '</tr>';
                    }

                    ?>

                </table>
            </div>
            <a class="btn btn-primary" href="Admins.php?do=Add"><i class="fa fa-plus"></i> Add New Member </a>


        <?php } elseif ($do == 'Add') {  //Add member Page 
        ?>

            <div class="container">
                <h1 class="text-center">Add New Member</h1>
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
                            <label for="exampleInputPassword1">Email</label>
                            <input type="email" name="email" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Full Name</label>
                            <input type="text" name="fullName" class="form-control" required="required">
                        </div>

                        <input type="submit" value="Add member" class="btn btn-primary" />
                    </form>
                </div>
                <?php

            } elseif ($do == 'Insert') {
                // Insert member into DB

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo '<h1 class="text-center">ADD New Member</h1>';
                    echo "<div class='container'>";


                    $user = $_POST['username'];
                    $pass = $_POST['password'];
                    $email = $_POST['email'];
                    $fullName = $_POST['fullName'];

                    $hashPass = sha1($_POST['password']);


                    // Validate the form

                    $formErrors = array();

                    if (strlen($user) < 4) {
                        $formErrors[] = 'Username can not be less than <strong>4 characters</strong>';
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
													admins(Username, Password, Email, FullName)
												VALUES(:zuser, :zpass, :zmail, :zname) ");
                        $stmt->execute(array(

                            'zuser'     => $user,
                            'zpass'     => $hashPass,
                            'zmail'     => $email,
                            'zname'     => $fullName,


                        ));

                        echo "<div class= 'alert alert-success'>" . $stmt->rowCount() . '  Record Inserted' . " </div>";
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
            } elseif ($do == 'Edit') { // Edit page 

                $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

                $stmt = $con->prepare("SELECT * FROM admins where ID = ? LIMIT 1");

                $stmt->execute(array($userId));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($stmt->rowCount() > 0) { ?>
                    <div class="container">
                        <h1 class="text-center">Edit Profile</h1>
                        <div class="container">
                            <form action="?do=Update" method="POST">
                                <input type="hidden" name="userId" value="<?php echo $userId ?>" />
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Username</label>
                                    <input type="text" name="username" value="<?php echo $row['Username'] ?>" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Password</label>
                                    <input type="hidden" name="oldpassword" value="<?php echo $row['Password'] ?>" />
                                    <input type="password" name="newpassword" class="form-control" autocomplete="new-password" placeholder="Leave Blank If You Don't Want To Change" />
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Email</label>
                                    <input type="email" name="email" value="<?php echo $row['Email'] ?>" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Full Name</label>
                                    <input type="text" name="fullName" value="<?php echo $row['FullName'] ?>" class="form-control" required="required">
                                </div>

                                <input type="submit" value="Save" class="btn btn-primary" Submit />
                            </form>
                        </div>

                    <?php
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
            } elseif ($do == 'Update') {
                echo '<h1 class="text-center">Update Profile</h1>';
                echo "<div class='container'>";
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $id = $_POST['userId'];
                    $user = $_POST['username'];
                    $email = $_POST['email'];
                    $fullName = $_POST['fullName'];

                    $pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

                    // Validate the form

                    $formErrors = array();

                    if (strlen($user) < 4) {
                        $formErrors[] = 'Username can not be less than <strong>4 characters</strong>';
                    }

                    if (strlen($user) > 20) {
                        $formErrors[] = 'Username can not be more than <strong>20 characters</strong>';
                    }

                    if (empty($user)) {
                        $formErrors[] = 'Username can not be <strong> empty </strong>';
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

                        $stmt = $con->prepare("UPDATE admins SET Username =?, Email = ?, FullName = ?, Password =? WHERE ID =? ");
                        $stmt->execute(array($user, $email, $fullName, $pass, $id));
                        echo "<div class= 'alert alert-success'>" . $stmt->rowCount() . '  Record Updated' . " </div>";
                    }
                } else {
                    ?>
                        <div class="container">
                            <?php
                            $errorMsg = 'SORRY! SOMETHING WRONG';
                            redirectHome($errorMsg);
                            ?>
                        </div>
                    <?php
                }
                echo '</div>';
            } elseif ($do == 'Delete') {
                // Delete member page

                    ?>
                    <h1 class="text-center">Delete Member</h1>
                    <div class="container">
                        <?php
                        $userId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

                        $stmt = $con->prepare("SELECT * FROM admins where ID = ? LIMIT 1");

                        $stmt->execute(array($userId));
                        $count = $stmt->rowCount();

                        if ($stmt->rowCount() > 0) {
                            $stmt = $con->prepare("DELETE FROM admins WHERE ID = :zuser");
                            $stmt->bindParam(":zuser", $userId);
                            $stmt->execute();

                            echo "<div class= 'alert alert-success'>" . $stmt->rowCount() . '  Record Deleted' . " </div>";
                            echo "</div>";
                        } else {
                        ?>
                            <div class="container">
                                <?php
                                $errorMsg = 'This ID does not exist';
                                redirectHome($errorMsg, 5);
                                ?>
                            </div>
                <?php
                        }
                    }


                    include $tpl . 'footer.php';
                } else {
                    header('Location: index.php');
                    exit();
                }
