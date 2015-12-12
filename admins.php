<!--
By Kulvinder Lotay and Artur Barbosa
-->
<!DOCTYPE html>
<html>
<?php
	# Connect to MySQL server and the database
	require( 'includes/connect_db.php' ) ;

	# Includes these helper functions
	require( 'includes/helpers.php' ) ;

	if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {

		$user_id = trim($_POST['user_id']);

		$fname = trim($_POST['firstname']);

		$lname = trim($_POST['lastname']);

		$email = trim($_POST['email']);

		$pass = trim($_POST['pass']);
		if($user_id <> 'admin'){
			$pass = crypt($pass, $email);
		}

		$insertquery = "INSERT INTO users (user_id, first_name, last_name, email, pass, reg_date)
		 								VALUES ('$user_id', '$fname', '$lname', '$email', '$pass', now())";

		$results = mysqli_query ($dbc, $insertquery);
		check_results($results);

		if($results){
			 echo '<p><strong>Successfully inserted new user.</strong></p></div></div>';
		}
	}

	# If user opened the page without submitting, initialize the fields

	# Otherwise, user submitted the form, so let's validate
	/*if () {
			$errors = array();

			$num = trim($_POST['num']) ;

			$fname = trim($_POST['fname']) ;

			$lname = trim($_POST['lname']) ;

		# Validate president number
		if (!valid_number($num)){
			$errors[] = 'number';
			#echo '<p style="color:red; font-size:16px;">Please provide a valid president number.</p>' ;
		}

		# Validate first name
		if (!valid_name($fname)){
		  $errors[] = 'first name';
				#echo '<p style="color:red; font-size:16px;">Please provide a first name.</p>' ;
		}

		# Validate last name
		if (!valid_name($lname)){
			$errors[] = 'last name';
				#echo '<p style="color:red; font-size:16px;">Please provide a last name.</p>' ;
		}

		if (!empty($errors)){
			echo '<span style="color: red">Error! Please enter the president</span>' ;
			foreach ( $errors as $field ) {
			echo '<span style="color: red; font-style: italic;"> - ' . $field . '</span>' ;
			}
		}
		else {
			$result = insert_record($dbc, $num, $fname, $lname) ;
			echo "<p>Successfully added $fname $lname into Dead Presidents.</p>" ;
		}
	}*/

	$cur_page = $_SERVER['PHP_SELF'];
	show_links($dbc, $cur_page);

	# Show the records
	show_page($cur_page, $dbc);

	# Display the footer
	show_footer();

	# Close the connection
	mysqli_close( $dbc ) ;

	# Show input form
	#show_form($num, $fname, $lname);
?>
</html>
