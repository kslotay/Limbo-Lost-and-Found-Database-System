<!--
By Kulvinder Lotay and Artur Barbosa
-->
<!DOCTYPE html>
<html>
<?php
	# Connect to MySQL server and the database
	require( 'includes/connect_db.php' );

	# Includes helper.php functions
	require( 'includes/helpers.php' );

	# Store current page value in $cur_page variable
	$cur_page = $_SERVER['PHP_SELF'];

	# Display navigation links
	show_links($dbc, $cur_page);

	# Display homepage with recent items
	show_page($cur_page, $dbc);

	# Display the footer
	show_footer();

	# Close the connection
	mysqli_close($dbc);
?>
</html>
