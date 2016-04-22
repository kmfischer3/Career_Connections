<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php 

  // Set up JSON response
  $response = array();
  $response["status_code"] = "_UNKNOWN";

  if (mysqli_connect_errno()) 
  {
      $response["status_code"] = "_SER";
  } 

  else 
  {

  	if ( !empty($_GET["day"]) )
  	{
  		
  		// Get the booth number and associated company for each booth depending on the specified day
  		$query = "";

  		if ( $_GET["day"] == "1" )
  		{
  			$query = "SELECT location, mon_company_id FROM booths;";
  		}
  		else 
  		{
  			$query = "SELECT location, tues_company_id FROM booths;";
  		}

        // Run the query, check for sql error or empty response
        $all_companies = mysqli_query($connection, $query);

        if ( !$all_companies )
        {
        	$response["status_code"] = "_SQL";
            $response["sql_msg"] = mysqli_error($connection);
        }
        else if ( mysqli_num_rows($all_companies) == 0 ) 
        {
        	$response["status_code"] = "LB_01";
        }
        else
        {
        	
        	$response["status_code"] = "OK";

            // Add each company to the response            
            while($company = mysqli_fetch_assoc($all_companies))
            {
            	//$id = $company['id'];
            	array_push($response, $company);
            }

        }
    } 
    else 
    {
    	$response["status_code"] = "LB_02";
    }
  }

/* Output pretty JSON */
$json = json_encode($response);
printf("%s", $json);

?>

