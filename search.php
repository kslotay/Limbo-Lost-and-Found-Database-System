<!--
Search page
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

	show_page($cur_page, $dbc);

	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		$likedesc = trim($_POST['desc']);

		$location = $_POST['location'];

		$type = $_POST['type'];

		$query =
			'SELECT stuff.id, stuff.create_date, stuff.description, stuff.status, locations.name, stuff.image_url
			FROM stuff, locations
			WHERE stuff.location_id = locations.id
			AND stuff.status="' . $type . '"
			AND stuff.location_id="' . $location . '"	AND stuff.description LIKE "%' . $likedesc . '%"';

		$results = mysqli_query($dbc, $query);
		check_results($results);
		if ($results){
			show_records($results, 99);
			switch($type){
				case 'found':
					echo '<a href=lost.php><<< Go Back</a><br>';
					echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
					break;
				case 'lost':
					echo '<a href=found.php><<< Go Back</a><br>';
					echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
					break;
			}

			echo '</div></div></div></div>';
		}
	}



	# Show the footer
	show_footer();

	# Close the connection
	mysqli_close( $dbc ) ;
?>
</html>
