<!--
Users quick link page
By Kulvinder Lotay and Artur Barbosa
-->
<!DOCTYPE html>
<html>
<?php
	# Connect to MySQL server and the database
	require( 'includes/connect_db.php' ) ;

	# Includes these helper functions
	require( 'includes/helpers.php' ) ;

	# Store current page in variable, call show_links and show_records functions using cur_page variable
	$cur_page = $_SERVER['PHP_SELF'];
	show_links($dbc, $cur_page);

	# Show the records
	show_page($cur_page, $dbc);

	# If user requests item (clicks quick link) make the appropriate GET request from quick links
	if ($_SERVER[ 'REQUEST_METHOD' ] == 'GET') {
		if(isset($_GET['id'])){
			show_user_record($dbc, $_GET['id']);
		}
	}

	# Otherwise, user submitted the form, so let's validate
	else if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
		if ($_POST['dialog'] == 'edituser') {

			$id = ($_POST['id']) ;

			$user_id = trim($_POST['user_id']);

			$fname = trim($_POST['firstname']);

			$lname = trim($_POST['lastname']);

			$email = trim($_POST['email']);

			$pass = trim($_POST['pass']);
			if($user_id <> 'admin'){
				$pass = crypt($pass, $email);
			}

			$updatequery = 'UPDATE users SET user_id="' . $user_id . '", first_name="' . $fname . '", last_name="' . $lname . '", email="' . $email . '", pass="' . $pass . '" WHERE id=' . $id;

			$results = mysqli_query ($dbc, $updatequery);
			check_results($results);

			if($results){
				 echo '<p><strong>Successfully updated.</strong></p></div></div>';
			}
		}

		else if ($_POST['dialog'] == 'deleteuser') {

			$id = trim($_POST['id']) ;

			$deletequery = 'DELETE FROM users WHERE id=' . $id;

			if($id <> 1){
				$results = mysqli_query ($dbc, $deletequery);
				check_results($results);

				if($results){
					 echo '<p><strong>Successfully deleted.</strong></p></div></div>';
				}
			}
			else{
					echo '<p><strong>Cannot delete admin !</strong></p></div></div>';
			}
		}
	}

	# Show the footer
	show_footer();

	# Close the connection
	mysqli_close( $dbc ) ;
?>
</html>
