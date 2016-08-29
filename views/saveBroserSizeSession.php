<?php require_once("../includes/session.php"); ?>
<?php

	$_SESSION['width'] = 0;
	$_SESSION['height'] = 0;
	$_SESSION['x_scale_factor'] = 1;
	$_SESSION['y_scale_factor'] = 1;

    if (isset($_POST['width']) && isset($_POST['height'])) {

	$_SESSION['width'] = (is_numeric($_POST['width']) ? (int)$_POST['width'] : 0);
	$_SESSION['x_scale_factor'] = ($_SESSION['width']/100);

	$_SESSION['height'] = (is_numeric($_POST['height']) ? (int)$_POST['height'] : 0);
	$_SESSION['y_scale_factor'] = ($_SESSION['height']/100);

    }
?>
