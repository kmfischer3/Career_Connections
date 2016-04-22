<?php require_once("../../includes/session.php"); ?>
<?php require_once("../../includes/db_connection.php"); ?>
<?php require_once("../../includes/functions.php"); ?>

<?php 

if (mysqli_connect_errno()) {
    
	print_fail("SQL connect error","!",__FILE__,"Main",__LINE__);

} else {

	// Run test suite. Assert that each test starts with an empty DOC

	test_Load_Booths();					// fails if status_code is not "OK" for loading non-empty table

	test_Load_Booths_On_Empty();		// fails if status_code is not "LB_01" for loading empty table
   
}


/* --- FUNCTIONS: Tests --- */

// TEST #1
// arguments: none
// returns: void
// description: tests that status_code is "OK" for loading booths when table is not empty
function test_Load_Booths() {

	// First, setup so the companies and booths tables are populated 
	setUp();	

	$response = file_get_contents("http://localhost/~kristina/sandbox/Load_Booths.php?day=1");
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
		case "LB_01":
			print_fail("expected status_code = OK but got LB_01","!!!",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "LB_02":
			print_fail("expected status_code = OK but got LB_02","!!!",__FILE__,__FUNCTION__,__LINE__);
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
// description: tests that status_code is "LB_01" for loading booths when table is empty
function test_Load_Booths_on_empty() {

	// setup the booths table for this test
	setUp_2();	

	$response = file_get_contents("http://localhost/~kristina/sandbox/Load_Booths.php?day=1");
	$data = json_decode($response, true);

	//Since the table is empty, the correct status code is LC_01
	switch ( $data["status_code"] ) {
		case "OK":
			print_fail("expected status_code = LB_01 but got OK", ">>",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "_SER":
			print_fail("Server error, try again? ","",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "_SQL":
			print_fail("SQL error: ".$data["sql_msg"],"",__FILE__,__FUNCTION__,__LINE__);
			break;
		case "LB_01":
			print_success("", __FUNCTION__);
			break;
		default:
			print_fail("unknown: expected status_code = LB_01", "!!!!!", __FILE__,__FUNCTION__,__LINE__);
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

		$create_query = "create table booths (  id int(6) auto_increment not null, location int(6) not null, mon_company_id int(6) not null, tues_company_id int(6) not null, foreign key (mon_company_id) references companies(id) on delete cascade, foreign key (tues_company_id) references companies(id) on delete cascade, primary key (id) );";
		$result_cq = mysqli_query($connection, $create_query);

		if ( !$result_cq ) {
			print_fail("SQL returned false after creating booths table", "!!", __FILE__, __FUNCTION__, __LINE__);

		} else {

			for ($i = 100; $i < 104; $i++) {

				$dummy_query = "INSERT INTO companies (id, name, email) VALUES ({$i},'Company {$i}', 'me@company{$i}.com');";
				$result_dq = mysqli_query($connection, $dummy_query);

				if ( !$result_dq ) {
					print_fail("SQL returned false inserting dummy data into companies table", "!!", __FILE__, __FUNCTION__, __LINE__);
				}

			}	

			$insert_query_1 = "INSERT INTO booths (location, mon_company_id, tues_company_id) values (10, 100, 101);";
			$result_1 = mysqli_query($connection, $insert_query_1);
			if (!$result_1) echo "u done fuxxed up";
			$insert_query_2 = "INSERT INTO booths (location, mon_company_id, tues_company_id) values (20, 102, 102);";
			$result_2 = mysqli_query($connection, $insert_query_2);
			if (!$result_2) echo "u done fuxxed up";
			$insert_query_3 = "INSERT INTO booths (location, mon_company_id, tues_company_id) values (30, 103, 100);";
			$result_3 = mysqli_query($connection, $insert_query_1);
			if (!$result_3) echo "u done fuxxed up";

			assert_not_empty(__LINE__);
			print_success("", __FUNCTION__);
		}
	}
}

// Set up 2:	
// Create an empty booths table
function setUp_2() {

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

		$create_query = "create table booths (  id int(6) auto_increment not null, location int(6) not null, mon_company_id int(6) not null, tues_company_id int(6) not null, foreign key (mon_company_id) references companies(id) on delete cascade, foreign key (tues_company_id) references companies(id) on delete cascade, primary key (id) );";
		$result_cq = mysqli_query($connection, $create_query);

		if ( !$result_cq ) {
			print_fail("SQL returned false after creating booths table", "!!", __FILE__, __FUNCTION__, __LINE__);

		} else {
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

		$tear_down_query = "DROP TABLE booths;";
		$result = mysqli_query($connection, $tear_down_query);

		if ( !$result ) {
			print_fail("SQL returned false dropping booths table", "!!", __FILE__, __FUNCTION__, __LINE__);

		} else {


			$tear_down_companies = "TRUNCATE TABLE companies;";
			$result_3 = mysqli_query($connection, $tear_down_companies);
			if ( !$result_3 ) {
				print_fail("SQL returned false clearing companies table", "!!", __FILE__, __FUNCTION__, __LINE__);

			} else {
				//assert_empty(__LINE__);
				print_success("", __FUNCTION__);				
			}

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
	$assert_query = "SELECT * FROM booths;";
	//$num_rows = mysqli_fetch_assoc(mysqli_query($connection, $assert_query));

	if ( !$assert_query ) {
		$is_empty = true;
	}
	
	assert($is_empty, "assert_empty() called at line {$line}");

}

// Asserts that the companies table is currently not empty
// $line argument is the line number of the call to assert_not_empty
function assert_not_empty($line) {

	$is_empty = false;

	// Count the rows in the companies table and assert that there are zero
	$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
	$assert_query = "SELECT * FROM booths;";
	//$num_rows = mysqli_fetch_assoc(mysqli_query($connection, $assert_query));

	if ( !$assert_query ) {
		$is_empty = true;
	}
	
	assert(!$is_empty, "assert_not_empty() called at line {$line}");

}

?>

