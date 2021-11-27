<?php

include 'connect.php';

//Routes

$tpl = 'includes/templates/'; // Template Directory
$css = 'layout/css/'; // Css Directory
$js = 'layout/js/'; // Js Directory
$func = 'includes/functions/';  // Functions Directory

//Include the important files
include $func . 'functions.php';
include $tpl . 'header.php';

// Include navbar on all pages expect the one with $noNavbar variable
if (!isset($noNavbar)) {
    include $tpl . 'navbar.php';
}
