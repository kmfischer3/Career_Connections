<?php require_once("../includes/session.php"); ?>

<?php

	$_SESSION['attributes'] = 0;
	$_SESSION['searchterm'] = null;

    if (isset($_GET['attributes'])) {

        $_SESSION['attributes'] = $_GET['attributes'];

        // Use the following code to print out the variables.
        echo 'Session: '.$_SESSION['attributes'];
        echo '<br>';
        echo 'GET: '.$_GET['attributes'];

    } else {

	$_SESSION['attributes'] = 0;

        // Use the following code to print out the variables.
        echo 'Session: '.$_SESSION['attributes'];
        echo '<br>';
        echo 'GET: no GET info for attributes';
    }

    if (isset($_GET['searchterm'])) {

        $_SESSION['searchterm'] = $_GET['searchterm'];

        // Use the following code to print out the variables.
        echo 'Session: '.$_SESSION['searchterm'];
        echo '<br>';
        echo 'GET: '.$_GET['searchterm'];

    } else {

	$_SESSION['searchterm'] = null;

        // Use the following code to print out the variables.
        echo 'Session: null';
        echo '<br>';
        echo 'GET: no GET info for searchterm';
    }

?> 
