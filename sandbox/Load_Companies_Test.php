

<?php 

  // // Set up JSON response
  // $response = array();
  // $response["status_code"] = "_UNKNOWN";
  // $response["companies"] = "";

  // if (mysqli_connect_errno()) 
  // {
  //     $response["status_code"] = "_SER";
  // } 

  // else 
  // {

  //    $query = "SELECT * FROM companies;";

  //       // Run the query, check for sql error or empty response
  //       $all_companies = mysqli_query($connection, $query);

  //       if ( !$all_companies )
  //       {
  //        $response["status_code"] = "_SQL";
  //           $response["sql_msg"] = mysqli_error($connection);
  //       }
  //       else if ( mysqli_num_rows($all_companies) == 0 ) 
  //       {
  //        $response["status_code"] = "LC_01";
  //       }
  //       else
  //       {
          
  //        $response["status_code"] = "OK";

  //           // Add each company to the response            
  //           while($company = mysqli_fetch_assoc($all_companies))
  //           {
  //            $id = $company['id'];
  //            array_push($response, $company);
  //           }

  //       }

  // }

/* Output pretty JSON */
//$json = json_encode($response);
$response = array();
$response["status"] = "OK";
$obj1 = array();
$obj1["bio"] = "Barot has just finished his final year at The Royal Academy of Painting and Sculpture, where he excelled in glass etching paintings and portraiture. Hailed as one of the most diverse artists of his generation, Barot is equally as skilled with watercolors as he is with oils, and is just as well-balanced in different subject areas. Barot's collection entitled 'The Un-Collection' will adorn the walls of Gilbert Hall, depicting his range of skills and sensibilities - all of them, uniquely Barot, yet undeniably different";
$obj1["shortname"] = "Barot_Bellingham";
$obj1["name"] = "Barot Bellingham";
$obj1["reknown"] = "Royal Academy of Painting and Sculpture";

$obj2 = array();
$obj2["bio"] = "The Artist to Watch in 2012 by the London Review, Johnathan has already sold one of the highest priced-commissions paid to an art student, ever on record. The piece, entitled Gratitude Resort, a work in oil and mixed media, was sold for $750,000 and Jonathan donated all the proceeds to Art for Peace, an organization that provides college art scholarships for creative children in developing nations";
$obj2["shortname"] = "Jonathan_Ferrar";
$obj2["name"] = "Jonathan Ferrar";
$obj2["reknown"] = "Artist to Watch in 2012";

$response["artists"] = array();
array_push($response["artists"], $obj1);
array_push($response["artists"], $obj2);

/* Output pretty JSON */
 $json = json_encode($response);
 printf("%s", $json);




// /* Output pretty JSON */
// $json = json_encode($response, JSON_PRETTY_PRINT);
// printf("<pre>%s</pre>", $json);


?>

