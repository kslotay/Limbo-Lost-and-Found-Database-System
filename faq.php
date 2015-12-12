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

	# Show the footer
	show_footer();

	# Close the connection
	mysqli_close( $dbc ) ;
?>
</html>
