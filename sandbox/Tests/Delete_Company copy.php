<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php $layout_context = "public"; ?>
<?php find_selected_page(true); ?>

<?php 

  /* Set up JSON response */
  $response = array();
  $response["status_code"] = "_UNKNOWN";

  if (mysqli_connect_errno()) 
  {
      $response["status_code"] = "_SER";
  } 

  else 
  {

        if( !empty($_GET['id']) )
        {

           $id = $_GET['id'];

           // Check that the given id is valid in table
           if ( mysqli_num_rows(mysqli_query($connection, "SELECT * FROM companies WHERE id={$id}")) == 0 )
           {
                $response["status_code"] = "DC_02";
           }
           else
           {

                // Attempt to delete the company, check for sql error
                $delete_query = "DELETE from companies WHERE id={$id}";
                $result = mysqli_query($connection, $delete_query);

                if (!$result) 
                {
                    $response["status_code"] = "_SQL";
                    $response["sql_msg"] = mysqli_error($connection);
                } 
                else 
                {
                    $response["status_code"] = "OK";                  
                }
           }
        }
        else
        {
                $response["status_code"] = "DC_01";

        }
  }

/* Output pretty JSON */
$json = json_encode($response, JSON_PRETTY_PRINT);
printf("<pre>%s</pre>", $json);

?>

<?php include("../includes/layouts/footer.php"); ?>
