<!--
Quick links page
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
		if(isset($_GET['id']) && isset($_GET['p'])){
			if($_GET['p'] == 3){
				show_record_admin($dbc, $_GET['id'], $_GET['p']);
			}
			else{
				show_record($dbc, $_GET['id'], $_GET['p']) ;
			}
		}
	}

	# Otherwise, user submitted the form, so let's validate
	else if ($_SERVER[ 'REQUEST_METHOD' ] == 'POST') {
			if($_POST['dialog'] == 'contact'){
				$errors = array();

				$from = trim($_POST['email']);

				$message = trim($_POST['msg']) ;

				$contactquery = 'SELECT owner, finder, status FROM stuff
												WHERE stuff.id = ' . $_POST['id'];

				$results = mysqli_query ($dbc, $contactquery);
				check_results($results);

				# Validate email and message
				if (!valid_name($email)){
					$errors[] = 'Email';
				}

				if (!valid_name($message)){
					$errors[] = 'Message';
				}

				if (!empty($errors)){
					foreach ( $errors as $field ) {
						echo '<span style="color: red; font-style: italic;"> - ' . $field . '</span>' ;
					}
				}
				else {

					# echo "<p>Successfully added $fname $lname into Dead Presidents.</p>" ;

					if($results){
						$row = mysqli_fetch_array($results, MYSQLI_ASSOC);
						switch ($row['status']){
							case 'found':
								send_email($row['finder'], $from, "You found my item!", $message);
								break;
							case 'lost':
								send_email($row['owner'], $from, "I found your item!", $message);
								break;
						}
					}
				}
		}

		else if ($_POST['dialog'] == 'edit') {

			$id = ($_POST['id']) ;

			$desc = trim($_POST['desc']);

			$location = trim($_POST['location']);

			$owner = trim($_POST['owner']);

			$finder = trim($_POST['finder']);

			$status = trim($_POST['status']);

			$updatequery = 'UPDATE stuff SET description="' . $desc . '", location_id="' . $location . '", owner="' . $owner . '", finder="' . $finder . '", status="' . $status . '", update_date="' . date('Y-m-d H:i:s') . '" WHERE id=' . $id;

			$results = mysqli_query ($dbc, $updatequery);
			check_results($results);

			if($results){
				echo '<p><strong>Successfully updated.</strong></p></div></div>';
			}
		}

		else if (($_POST['dialog'] == 'claimfound') || ($_POST['dialog'] == 'claimitem')) {

			$id = trim($_POST['id']) ;

			$email = trim($_POST['email']);

			if($_POST['dialog'] == 'claimfound'){
				$claimquery = 'UPDATE stuff SET status=\'claimed\' WHERE owner="' . $email . '" AND id= ' . $id;
			}
			else if($_POST['dialog'] == 'claimitem'){
				$claimquery = 'UPDATE stuff SET status=\'claimed\' WHERE finder="' . $email . '" AND id= ' . $id;
			}


			$results = mysqli_query ($dbc, $claimquery);
			check_results($results);

			if($results){
				echo '<p><strong>Successfully claimed!</strong></p></div></div>';
			}
			else {
				echo '<p><strong>You cannot claim this item!';
			}
		}

		else if ($_POST['dialog'] == 'delete') {

			$id = trim($_POST['id']) ;

			$deletequery = 'DELETE FROM stuff WHERE id=' . $id;

			$results = mysqli_query ($dbc, $deletequery);
			check_results($results);

			if($results){
				 echo '<p><strong>Successfully deleted.</strong></p></div></div>';
			}
		}
	}

	# Show the footer
	show_footer();

	# Close the connection
	mysqli_close( $dbc ) ;
?>
</html>
