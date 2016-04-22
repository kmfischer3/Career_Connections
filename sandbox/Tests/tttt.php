<?php require_once("../../includes/session.php"); ?>
<?php require_once("../../includes/db_connection.php"); ?>
<?php require_once("../../includes/functions.php"); ?>

<?php $layout_context = "public"; ?>
<?php find_selected_page(true); ?>

<?php 

if (mysqli_connect_errno()) {
      $response["status_code"] = "_SER";
} else {

    $b = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM companies"));
    printf("<font color = 'red'>SQL query in %s -> %s -> %s</font>", __FILE__, __FUNCTION__, __LINE__);
}


?>