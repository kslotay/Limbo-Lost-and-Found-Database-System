<!--
Lost Something Page
By Kulvinder Lotay and Artur Barbosa
-->
<!DOCTYPE html>
<html>
<?php
	# Connect to MySQL server and the database
	require( 'includes/connect_db.php' ) ;

	# Includes these helper functions
	require( 'includes/helpers.php' ) ;

	# If user requests item (clicks quick link) make the appropriate GET request from quick links
	if ($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
		if(isset($_GET['id']) && isset($_GET['p'])){
			header('Location: /ql.php?id=' . $_GET['id'] . '&p=' . $_GET['p']);
		}
	}

	# Otherwise, user submitted the form, so let's validate
	/*else if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
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

	# Store current page in variable, call show_links and show_records functions using cur_page variable
	$cur_page = $_SERVER['PHP_SELF'];
	show_links($dbc, $cur_page);

	# Display lost page with recenlty found items
	show_page($cur_page, $dbc);

	# Display the footer
	show_footer();

	# Close the connection
	mysqli_close( $dbc ) ;
?>
</html>
