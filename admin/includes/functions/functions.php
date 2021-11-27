<?php

/*Title func that echo the page title */

function getTitle()
{

    global $pageTitle;

    if (isset($pageTitle)) {

        echo $pageTitle;
    } else {

        echo 'Movies';
    }
}

/* Redirect function */
function redirectHome($errorMsg, $seconds = 3)
{
    echo "<div class ='alert alert-danger'>$errorMsg</div>";
    echo "<div class ='alert alert-info'>You will be redirected to Home page after $seconds Seconds. </div>";
    header("refresh:$seconds;url=index.php");
    exit();
}
