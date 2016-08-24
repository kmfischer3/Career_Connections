<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/page_top.php");?>
<?php include("../includes/loremipsum.php");?>

<div class="content">

<?php 

  // Set up json object
  $response = array();
  $response["status_code"] = "UNKNOWN";
  $response["companies"] = array();

  if (mysqli_connect_errno()) {
      $response["status_code"] = "SERVER_ERROR";
  } 

  else {

        $all_companies = mysqli_query($connection, "SELECT * FROM companies;");

        if ( !$all_companies ){
        	$response["status_code"] = "SQL_ERROR";
            	$response["sql_msg"] = mysqli_error($connection);
        }
        else {
        		
		$response["status_code"] = "OK";

		// Add each company to the response            
		while($company = mysqli_fetch_assoc($all_companies)) {
		    	array_push($response["companies"], $company);
		}
        }
  }

//print json object for debugging
//printf("%s", json_encode($response));


// if the query returned results, display them.
// else display error message or "no results"
if ($response["companies"]){

	foreach ($response["companies"] as $value) {

		$id 	= $value['id'];
		$name 	= $value['name'];
		$booth 	= $value['booth']; 

		echo "
		<div class=\"media\">
	  		<div class=\"media-left\">
	      			<img class=\"media-object\" src=\"../includes/images/linux.png\" alt=\"...\">
	  		</div>
	  		<div class=\"media-body\">
				<a href=\"company_profile?id=".$id."\">
	    				<h4 class=\"media-heading\">".$name."</h4>
				</a>
	    			".$lorem_med."
	  		</div>
	  		<div class=\"media-right\">
				<button type=\"button\" class=\"btn btn-default btn-lg\" style=\"border:none;\">
				 	<span class=\"glyphicon glyphicon-heart-empty\" aria-hidden=\"true\"></span>
				</button>
	  		</div>
		</div>
		";
	}

} else {

	if ($response["status_code"] != "OK") {
		echo $response["status_code"];
	} else {
		//query was ok but returned no results
		echo "No Results.";
	}
}

?>

</div>

<?php include("../includes/button-add.html");?>
<?php include("../includes/page_bottom.php");?>

