<?php

	$_SESSION['width'] = 0;
	$_SESSION['height'] = 0;

    if (isset($_POST['width'])) {

        $_SESSION['width'] = $_POST['width'];

    } else {

	$_SESSION['width'] = 0;

    }

    if (isset($_POST['height'])) {

        $_SESSION['height'] = $_POST['height'];

    } else {

	$_SESSION['height'] = 0;

    }


?>
