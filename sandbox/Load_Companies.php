<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php $layout_context = "public"; ?>
<?php find_selected_page(true); ?>

<?php 

  // Set up JSON response
  $response = array();
  $response["status_code"] = "_UNKNOWN";
  $response["companies"] = "";

  if (mysqli_connect_errno()) 
  {
      $response["status_code"] = "_SER";
  } 

  else 
  {

  		$query = "SELECT * FROM companies;";

        // Run the query, check for sql error or empty response
        $all_companies = mysqli_query($connection, $query);

        if ( !$all_companies )
        {
        	$response["status_code"] = "_SQL";
            $response["sql_msg"] = mysqli_error($connection);
        }
        else if ( mysqli_num_rows($all_companies) == 0 ) 
        {
        	$response["status_code"] = "LC_01";
        }
        else
        {
        	
        	$response["status_code"] = "OK";

            // Add each company to the response            
            while($company = mysqli_fetch_assoc($all_companies))
            {
            	$id = $company['id'];
            	array_push($response, $company);
            }

        }

  }

/* Output pretty JSON */
$json = json_encode($response);
printf("%s", $json);

?>

