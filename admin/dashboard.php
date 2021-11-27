<?php
session_start();
if (isset($_SESSION['Username'])) {

    $pageTitle = 'Dashboard';

    include 'init.php';
?>
    <div class="carousel-inner">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../admin/layout/images/green background.png" class="d-block w-100">
                <div class="carousel-caption d-none d-md-block">
                    <h5>Welcome In Admin Dashboard</h5>
                    <p>Here You Can Edit, Add & Delete Movies.</p>
                </div>
                </img>
            </div>
        <?php

        include $tpl . 'footer.php';
    } else {
        header('Location: index.php');
        exit();
    }
