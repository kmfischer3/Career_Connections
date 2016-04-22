<?php require_once("../../includes/session.php"); ?>
<?php require_once("../../includes/db_connection.php"); ?>
<?php require_once("../../includes/functions.php"); ?>

<?php $layout_context = "public"; ?>
<?php find_selected_page(true); ?>

<?php 

if (mysqli_connect_errno()) {
    
	print_fail("SQL connect error","!",__FILE__,"Main",__LINE__);

} else {

	// Run test suite. Assert that each test starts with an empty DOC


	test_Load_Companies();				// fails if status_code is not "OK" for loading non-empty table

	assert_empty(__LINE__);
	test_Load_Companies_On_Empty();		// fails if status_code is not "LC_01" for loading empty table

	assert_empty(__LINE__);

	//commenting this out for now because it's not working (php warnings included in file_contents so it's not able to read as JSON)
	//test_Add_Unique_Company();			// fails if status_code is not "OK" after adding new, unique company
   
}


/* --- FUNCTIONS: Tests --- */

// TEST #1
// arguments: none
// returns: void
// description: tests that status_code is "OK" for loading companies when companies table is not empty
function test_Load_Companies() {

	// First, setup so the companies table is populated with three unique entries
	setUp();	

	$response = file_get_contents("http://localhost/~kristina/sandbox/Load_Companies.php");
	$data = json_decode($response, true);

	//Since setUp() is called immediately before this, the correct status code is OK
	switch ( $data["status_code"] ) {
		case "OK":
			print_success("", __FUNCTION__);
			break;
		case "_SER":
			print_fail("Server error, try again? ","",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "_SQL":
			print_fail("SQL error: ".$data["sql_msg"],"",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "LC_01":
			print_fail("expected status_code = OK but got LC_01","!!!",__FILE__,__FUNCTION__,__LINE__);
			break;
		default:
			print_fail("unknown: expected status_code = OK", "!!!!!", __FILE__,__FUNCTION__,__LINE__);
	}

	// Clear the table so the result of this test does not mess up future tests
	tearDown();

}

// TEST #2
// arguments: none
// returns: void
// description: tests that status_code is "LC_01" for loading companies when companies table is empty
function test_Load_Companies_on_empty() {

	// Do NOT setup the companies table for this test
	//setUp();	

	$response = file_get_contents("http://localhost/~kristina/sandbox/Load_Companies.php");
	$data = json_decode($response, true);

	//Since the table is empty, the correct status code is LC_01
	switch ( $data["status_code"] ) {
		case "OK":
			print_fail("expected status_code = LC_01 but got OK", ">>",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "_SER":
			print_fail("Server error, try again? ","",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "_SQL":
			print_fail("SQL error: ".$data["sql_msg"],"",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "LC_01":
			print_success("", __FUNCTION__);
			break;
		default:
			print_fail("unknown: expected status_code = LC_01", "!!!!!", __FILE__,__FUNCTION__,__LINE__);
	}

	// Clear the table so the result of this test does not mess up future tests
	tearDown();

}

// TEST #3
// arguments: none
// returns: void
// description: tests that status_code is "OK" for adding a new, unique company that has content for all required fields
function test_Add_Unique_Company() {

	//call setup so the companies table is populated with three unique entries that are different from the one about to be added
	setUp();	

	$response = file_get_contents("http://localhost/~kristina/sandbox/Add_Company.php?name=NewCompany&email=me@newcompany.org");
	var_dump($response);
	$data = json_decode($response, true);
	var_dump($data);

	//Since setUp() is called immediately before this, the correct status code is OK
	switch ( $data["status_code"] ) {
		case "OK":
			print_success("", __FUNCTION__);
			break;
		case "_SER":
			print_fail("Server error, try again? ","",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "_SQL":
			print_fail("SQL error: ".$data["sql_msg"],"",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "AC_01":
			print_fail("expected status_code = OK but got AC_01","!!!",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "AC_02":
			print_fail("expected status_code = OK but got AC_02","!!!",__FILE__,__FUNCTION__,__LINE__);
			break;
		// case "AC_03":
		// 	print_fail("expected status_code = OK but got AC_03","!!!",__FILE__,__FUNCTION__,__LINE__);
		// 	break;
		default:
			print_fail("unknown: expected status_code = OK but got ".$data["status_code"], "!!!!!", __FILE__,__FUNCTION__,__LINE__);
	}

	// Clear the table so the result of this test does not mess up future tests
	tearDown();

}



// TEST #1
// arguments:
// returns:
// description:
function test3() {

}


/* --- FUNCTIONS: setUp and tearDown --- */

// Set up:	
// Empty the table and fill with dummy data
function setUp() {

	// Database connection
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

	// Test if connection succeeded
	if(mysqli_connect_errno()) {

		print_fail("SQL connect error","!",__FILE__,__FUNCTION__,__LINE__);

		die("Database connection failed: " . 
	        mysqli_connect_error() . 
	        " (" . mysqli_connect_errno() . ")"
	    );

	} else {

		$tear_down_query = "TRUNCATE table companies;";
		$result_td = mysqli_query($connection, $tear_down_query);

		if ( !$result_td ) {
			print_fail("SQL returned false after clearing companies table", "!!", __FILE__, __FUNCTION__, __LINE__);

		} else {

			for ($i = 1; $i < 4; $i++) {

				$dummy_query = "INSERT INTO companies (name, email, description, website) VALUES ('Company {$i}', 'meme@company{$i}.com', 'description about Company {$i}', 'www.company{$i}.com');";
				$result_dq = mysqli_query($connection, $dummy_query);

				if ( !$result_dq ) {
					print_fail("SQL returned false inserting dummy data into companies table", "!!", __FILE__, __FUNCTION__, __LINE__);
				}

			}	

			assert_not_empty(__LINE__);
			print_success("", __FUNCTION__);
		}
	}
}

// Tear Down:
// Make sure you end up with an empty table
function tearDown() {

	// Database connection
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

	// Test if connection succeeded
	if(mysqli_connect_errno()) {

		print_fail("SQL connect error","!",__FILE__,__FUNCTION__,__LINE__);

		die("Database connection failed: " . 
	        mysqli_connect_error() . 
	        " (" . mysqli_connect_errno() . ")"
	    );

	} else {

		$tear_down_query = "TRUNCATE table companies;";
		$result = mysqli_query($connection, $tear_down_query);

		if ( !$result ) {
			print_fail("SQL returned false clearing companies table", "!!", __FILE__, __FUNCTION__, __LINE__);

		} else {
			assert_empty(__LINE__);
			print_success("", __FUNCTION__);
		}			
	
	}
		
}


/* --- FUNCTIONS: miscellaneous --- */

// Test fail with rank/identifier
function print_fail($err_msg, $rank, $file, $function, $line) {

	printf("<font color='red'><br /> %s FAILED: %s <br /> Line %s in %s <br />Error Message: %s <br /></font>", $rank, $function, $line, $file, $err_msg);
}

// Test success with rank/identifier
function print_success($rank, $function) {

	printf("<font color='green'><br /> %s %s passed <br /></font>", $rank, $function);
}

// Asserts that the companies table is currently empty
// $line argument is the line number of the call to assert_empty
function assert_empty($line) {

	$is_empty = false;

	// Count the rows in the companies table and assert that there are zero
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$assert_query = "SELECT COUNT(*) AS total FROM companies;";
	$num_rows = mysqli_fetch_assoc(mysqli_query($connection, $assert_query));

	if ( $num_rows['total'] == 0) {
		$is_empty = true;
	}
	
	assert($is_empty, "assert_empty() called at line {$line}");

}

// Asserts that the companies table is currently not empty
// $line argument is the line number of the call to assert_not_empty
function assert_not_empty($line) {

	$is_empty = true;

	// Count the rows in the companies table and assert that there is at least one row
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$assert_query = "SELECT COUNT(*) AS total FROM companies;";
	$num_rows = mysqli_fetch_assoc(mysqli_query($connection, $assert_query));

	if ( $num_rows['total'] >= 1) {
		$is_empty = false;
	}
	
	assert(!$is_empty, "assert_not_empty() called at line {$line}");

}

?>

