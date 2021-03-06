<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/page_top.php");?>

<style>
/*TODO move styling to css folder*/
svg {
    width: 100%;
    height: 100%;
    background-image: url("../includes/images/trade-show-map-example.png");
    background-repeat: no-repeat;
    background-size: 100% 100%;
}
rect {
    fill: yellow;
    fill-opacity: 0.5;
}
</style>

<?php include("../includes/button-clearfilters.php");?>

<svg id="mysvg">

<?php 

  $x_scale_factor = ( isset($_SESSION['x_scale_factor']) ) ? $_SESSION['x_scale_factor'] : 0;
  $y_scale_factor = ( isset($_SESSION['y_scale_factor']) ) ? $_SESSION['y_scale_factor'] : 0;

  // Set up json object
  $response = array();
  $response["status_code"] = "UNKNOWN";
  $response["companies"] = array();

  $attributes = ( isset($_SESSION['attributes']) ) ? $_SESSION['attributes'] : 0;
  $searchterm = ( isset($_SESSION['searchterm']) ) ? $_SESSION['searchterm'] : null;

  if (mysqli_connect_errno()) {
      $response["status_code"] = "SERVER_ERROR";
  } 

  else {
	
	
	$query = 
		"
		SELECT * FROM companies 
		WHERE attributes & $attributes = $attributes
		";
	

	if ( !empty($searchterm) ) {
		
		$query .= " AND name LIKE '%$searchterm%'";

	} 

	$query .= " ;";

        $all_companies = mysqli_query($connection, $query);

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
// else display map with no highlighting
if ($response["companies"]){

	foreach ($response["companies"] as $value) {

		$x_coord 	= $value['x']*$x_scale_factor;
		$y_coord 	= $value['y']*$y_scale_factor;

		$width 		= 8*$x_scale_factor;
		$height 	= 2*$y_scale_factor;

		echo "<rect x=\"$x_coord\" y=\"$y_coord\" width=\"$width\" height=\"$height\"/>";	
		
	}
}
// if the user is looking for a specific booth (button from company profile), highlight it in a different color
if ( isset($_GET["boothid"]) ){

	$id = $_GET["boothid"];
	$query2 = 
		"
		SELECT x,y FROM booths 
		WHERE id = $id;
		";
	
        $result = mysqli_query($connection, $query2);

        if ( !$result ){
        	$response["status_code"] = "SQL_ERROR";
            	$response["sql_msg"] = mysqli_error($connection);
		printf("%s", json_encode($response));
        }
        else {
        		
		$response["status_code"] = "OK";
		$booth = mysqli_fetch_assoc($result);

		$x_coord 	= $booth['x'];
		$y_coord 	= $booth['y'];

		echo "<rect x=\"$x_coord\" y=\"$y_coord\" width=\"36\" height=\"18\" style=\"fill:red;fill-opacity:0.5;\" />";			

        }
	
}


?>
</svg>

<script>
	$(document).ready(function()
	{
	    sendBrowserSize();
	});
</script>
<?php include("../includes/button-add.html");?>
<?php include("../includes/page_bottom.php");?>

