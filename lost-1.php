<!--
Report Lost Item Page
By Kulvinder Lotay and Artur Barbosa
-->
<!DOCTYPE html>
<html>
<?php
	# Connect to MySQL server and the database
	require( 'includes/connect_db.php' ) ;

	# Includes these helper functions
	require( 'includes/helpers.php' ) ;

	# Otherwise, user submitted the form, so let's validate
	if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
			$errors = array();

			$desc = trim($_POST['desc']) ;

			$location = ($_POST['location']) ;

			$owner = trim($_POST['owner']);

			$finder = '';

			$image_url = trim($_POST['img_url']);

		# Validate description
		if (!valid_name($desc)){
			$errors[] = 'description';
		}

		# Validate owner/finder email address
		if (!valid_name($owner)){
			$errors[] = 'owner email address';
				#echo '<p style="color:red; font-size:16px;">Please provide a first name.</p>' ;
		}

		if (!empty($errors)){
			echo '<span style="color: red">Error! Please enter</span>' ;
			foreach ( $errors as $field ) {
			echo '<span style="color: red; font-style: italic;"> - ' . $field . '</span>' ;
			}
		}
		else {
			$result = insert_record($dbc, $desc, $location, $owner, $finder,'lost', $image_url) ;
			echo "<p>Successfully added $desc into Stuff.</p>" ;
		}
	}

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
