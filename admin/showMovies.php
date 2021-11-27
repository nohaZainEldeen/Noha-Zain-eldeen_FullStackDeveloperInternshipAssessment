<?php

// manage Admins page

session_start();
$pageTitle = 'Movies';
if (isset($_SESSION['Username'])) {

    include 'init.php';

    $do = isset($_GET['do']) ? $_GET['do'] : 'manage';

    if ($do == 'manage') {
        // Show all users
        $stmt = $con->prepare("SELECT * FROM movies ");
        $stmt->execute();
        $rows = $stmt->fetchAll();



?>
        <h1 class="text-center">Movies</h1>
        <div class="container">
            <div class="row row-cols-1">

                <?php

                foreach ($rows as $row) {
                    echo '<div class="card">';
                    echo '<div class="col mb-4">';
                    echo '<h6><a href="showMovies.php?do=Show&ID=' . $row['ID'] . '"class="list-group-item list-group-item-action list-group-item-light"">' . $row['Movie'] . '</a></h6>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">' . $row['Rating'] . '/10</h6>';
                    echo '<p class="card-text">' . $row['Description'] . '</p>';
                    echo '<a href="showMovies.php?do=Edit&ID=' . $row['ID'] . '" class="btn btn-success">Edit</a>
                            <a href="showMovies.php?do=Delete&ID=' . $row['ID'] . '" class="btn btn-danger " class="confirm">Delete</a>';
                    echo '</div>';
                    echo '</div>';
                }

                ?>


            </div>
            <a class="btn btn-primary" href="showMovies.php?do=Add"><i class="fa fa-plus"></i> Add New Movie </a>


        <?php } elseif ($do == 'Add') {  //Add movie Page 
        ?>

            <div class="container">
                <h1 class="text-center">Add New Movie</h1>
                <div class="container">
                    <form action="?do=Insert" method="POST">

                        <div class="form-group">
                            <label for="exampleInputEmail1">Movie's Name</label>
                            <input type="text" name="name" class="form-control" required="required">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Description</label>
                            <input type="text" name="description" class=" form-control" required="required" />

                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Rating</label>
                            <input type="text" name="rating" class="form-control" required="required">
                        </div>


                        <input type="submit" value="Add movie" class="btn btn-primary" />
                    </form>
                </div>
                <?php

            } elseif ($do == 'Insert') {
                // Insert movie into DB

                if ($_SERVER['REQUEST_METHOD'] == 'POST') {

                    echo '<h1 class="text-center">ADD New Movie</h1>';
                    echo "<div class='container'>";


                    $name = $_POST['name'];
                    $description = $_POST['description'];
                    $rating = $_POST['rating'];


                    // Validate the movie's form

                    $formErrors = array();

                    if (strlen($rating) < 1) {
                        $formErrors[] = 'Rate must be more than or equal <strong>1</strong>';
                    }

                    if (strlen($rating) > 11) {
                        $formErrors[] = 'Rate must be less than or equal <strong>10</strong>';
                    }
                    if (empty($name)) {
                        $formErrors[] = 'Name can not be <strong> empty </strong>';
                    }
                    if (empty($description)) {
                        $formErrors[] = ' Description can not be <strong> empty </strong> ';
                    }
                    if (empty($rating)) {
                        $formErrors[] = ' Rating can not be <strong> empty </strong> ';
                    }

                    foreach ($formErrors as $error) {
                        echo '<div class="alert alert-danger" >' . $error . '</div>';
                    }

                    // if no errors -> update db

                    if (empty($formErrors)) {

                        $stmt = $con->prepare("INSERT INTO 
													movies(Movie, Description, Rating)
												VALUES(:zmovie, :zdescription, :zrating) ");
                        $stmt->execute(array(

                            'zmovie'     => $name,
                            'zdescription'     => $description,
                            'zrating'     => $rating,


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
            } elseif ($do == 'Edit') { // Edit movie 

                $movieId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

                $stmt = $con->prepare("SELECT * FROM movies where ID = ? LIMIT 1");

                $stmt->execute(array($movieId));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($stmt->rowCount() > 0) { ?>
                    <div class="container">
                        <h1 class="text-center">Edit Movie</h1>
                        <div class="container">
                            <form action="?do=Update" method="POST">
                                <input type="hidden" name="movieId" value="<?php echo $movieId ?>" />
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Movie's Name</label>
                                    <input type="text" name="movieName" value="<?php echo $row['Movie'] ?>" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Description</label>
                                    <input type="text" name="description" value="<?php echo $row['Description'] ?>" class="form-control" required="required">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Rating</label>
                                    <input type="text" name="rating" value="<?php echo $row['Rating'] ?>" class="form-control" required="required">
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
            } elseif ($do == 'Show') { // Show movie 

                $movieId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

                $stmt = $con->prepare("SELECT * FROM movies where ID = ? LIMIT 1");

                $stmt->execute(array($movieId));
                $row = $stmt->fetch();
                $count = $stmt->rowCount();

                if ($stmt->rowCount() > 0) { ?>
                        <div class="container">
                            <h1 class="text-center">Show Movie</h1>
                            <div class="container">
                                <div action="?do=Show" method="POST" class="jumbotron">
                                    <h1 class="display-4"><?php echo $row['Movie'] ?></h1>
                                    <p class="lead"><?php echo $row['Rating'] ?>/10</p>
                                    <hr class="my-4">
                                    <p><?php echo $row['Description'] ?></p>
                                </div>
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

                        $id = $_POST['movieId'];
                        $name = $_POST['movieName'];
                        $description = $_POST['description'];
                        $rating = $_POST['rating'];



                        // Validate the form

                        $formErrors = array();

                        if (strlen($rating) < 1) {
                            $formErrors[] = 'Rate must be more than or equal <strong>1</strong>';
                        }

                        if (strlen($rating) > 11) {
                            $formErrors[] = 'Rate must be less than or equal <strong>10</strong>';
                        }

                        if (empty($name)) {
                            $formErrors[] = 'Name can not be <strong> empty </strong>';
                        }
                        if (empty($description)) {
                            $formErrors[] = ' Description can not be <strong> empty </strong> ';
                        }
                        if (empty($rating)) {
                            $formErrors[] = ' Rating can not be <strong> empty </strong> ';
                        }

                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger" >' . $error . '</div>';
                        }

                        // if no errors -> update db

                        if (empty($formErrors)) {

                            $stmt = $con->prepare("UPDATE movies SET Movie =?, Description = ?, Rating = ? WHERE ID =? ");
                            $stmt->execute(array($name, $description, $rating, $id));
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
                    // Delete movie 

                        ?>
                        <h1 class="text-center">Delete Movie</h1>
                        <div class="container">
                            <?php
                            $movieId = isset($_GET['ID']) && is_numeric($_GET['ID']) ? intval($_GET['ID']) : 0;

                            $stmt = $con->prepare("SELECT * FROM movies where ID = ? LIMIT 1");

                            $stmt->execute(array($movieId));
                            $count = $stmt->rowCount();

                            if ($stmt->rowCount() > 0) {
                                $stmt = $con->prepare("DELETE FROM movies WHERE ID = :zmovie");
                                $stmt->bindParam(":zmovie", $movieId);
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
