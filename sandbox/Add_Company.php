<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php 

  // Set up JSON response per the API specs
  $response = array();
  //$response["status_code"] = "_UNKNOWN";

  if (mysqli_connect_errno()) 
  {
      $response["status_code"] = "_SER";
  } 

  else 
  {

        if ( !empty($_GET["name"]) && !empty($_GET["email"]) )
        {

            // REQUIRED fields
            $name = $_GET["name"];
            $email = $_GET["email"];
            //$booth = $_GET["booth"];

            // OPTIONAL fields
            $description = $_GET["description"];
            //$image = $_GET["image"];
            $website = $_GET["website"];

            $valid = true;

            // First check for duplicate booth in the db
            //$booth_count = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM companies WHERE booth={$booth};"));

            // if ( $booth_count != 0 )
            // {
            //     $valid = false;
            //     $response["status_code"] = "AC_03";
            //     $response["id"] = "-1";                    

            // }

            // Then check for duplicate email in the db
            $email_count = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM companies WHERE email='{$email}';"));

            if ( $email_count != 0 )
            {
                $valid = false;
                $response["status_code"] = "AC_02";
                $response["id"] = "-1";
            }

            // Request is valid if all required fields are !empty and email,booth are unique           
            if ($valid)
            {
                $insert  = "INSERT INTO companies ";
                $insert .= "(name, email, description, website) VALUES ('{$name}','{$email}','{$description}','{$website}');";

                // Perform query, check for error
                $result = mysqli_query($connection, $insert);
                if (!$result)
                {
                    $response["status_code"] = "_SQL";
                    $response["id"] = "-1";
                    $response["sql_msg"] = mysqli_error($connection);
                }
                else
                {
                    // Successful insert => return id of new company
                    $retID = mysqli_fetch_assoc(mysqli_query($connection, "SELECT LAST_INSERT_ID();"))['LAST_INSERT_ID()'];
                    $response["status_code"] = "OK";
                    $response["id"] = $retID;
                }
            }
        }
        else
        {
            $response["status_code"] = "AC_01";
            $response["id"] = "-1";
        }
}


/* Output pretty JSON */
$json = json_encode($response);
echo $json;

?>