<?php
$debug = true;

# Shows the header and navigation links
function show_links($dbc, $cur_page) {

	echo '<link rel="stylesheet" type="text/css" href="assets/css/main.css"/>
				<script src="assets/js/jquery.min.js"></script>
				<script src="assets/js/jquery.dropotron.min.js"></script>
				<script src="assets/js/skel.min.js"></script>
				<script src="assets/js/skel-viewport.min.js"></script>
				<script src="assets/js/util.js"></script>
				<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
				<script src="assets/js/main.js"></script>';

	# Store link parameters in variables
	$limbo = '<H1><A href="limbo_landing.php">LIMBO</A></H1>';
	$home = '<li><A HREF=limbo_landing.php>Home</A></li>';
	$lost = '<li><A HREF=lost.php>Lost Something</A>';
	$found = '<li><A HREF=found.php>Found Something</A>';
	$admin = '<li><A HREF=limbo_login.php>Admins</A></li>';
	$faq = '<li><A HREF=faq.php>FAQ</A></li>';

	# Set class="current" for the current page navigation link
	switch ($cur_page){
		case "/limbo_landing.php":
			$home = '<li class="current"><A HREF=limbo_landing.php>Home</A></li>';
			echo '<TITLE>Limbo - Home</TITLE>';
			break;
		case "/lost-1.php":
		case "/lost.php":
			$lost = '<li class="current"><A HREF=lost.php>Lost Something</A>';
			echo '<TITLE>Limbo - Lost Something</TITLE>';
			break;
		case "/found-1.php":
		case "/found.php":
			$found = '<li class="current"><A HREF=found.php>Found Something</A>';
			echo '<TITLE>Limbo - Found Something</TITLE>';
			break;
		case "/admins.php":
			$admin = '<li class="current"><A HREF=limbo_login.php>Admins</A></li>';
			echo '<TITLE>Limbo - Admins</TITLE>';
			break;
		case "/faq.php":
			$faq = '<li class="current"><A HREF=faq.php>FAQ</A></li>';
			echo '<TITLE>Limbo - FAQ</TITLE>';
			break;
	}

	# Display navigation links
	echo '<div id="page-wrapper">';
	echo '<div id="header-wrapper">';
	echo '<div id="header">';
	echo $limbo;
	echo '<nav id="nav">';
	echo '<ul>';
	echo $home;
	echo $lost;
	echo '<ul>';
	echo '<li><a href="lost.php">SEARCH For Lost Item</a></li>';
	echo '<li><a href="lost-1.php" method="post">REPORT Lost Item</a></li>';
	echo '</ul>';
	echo '</li>';
	echo $found;
	echo '<ul>';
	echo '<li><a href="found.php">SEARCH For Found Item</a></li>';
	echo '<li><a href="found-1.php">REPORT Found Item</a></li>';
	echo '</ul>';
	echo '</li>';
	echo $admin;
	echo $faq;
	echo '</ul>';
	echo '</nav>';
}

# Retreives page body depending on current page
function show_page($cur_page, $dbc) {
	switch ($cur_page) {
		case '/limbo_landing.php':
			# Store current page as number, to send in GET request for quick link item details, the current page will determine what content to filter and which 'go back' to display
			$p = 0;
			# Define query for the homepage records - return the 6 most recent items
			$query = 'SELECT stuff.id, stuff.create_date, stuff.description, stuff.status, stuff.image_url, locations.name
				FROM stuff, locations
				WHERE stuff.location_id = locations.id
				ORDER BY stuff.update_date DESC LIMIT 6';

			# Store the query results in $results
			$results = mysqli_query($dbc, $query);
			check_results($results);

			# Show results
			# But...wait until we know the query succeed before
			# rendering the table start.
			if( $results ) {
				# Display homepage banner
				echo '<section id="banner">
						<header>
							<h2>Welcome to Limbo</h2>
							<p>The Ultimate Lost & Found System</p>
						</header>
					</section>';
				# Diplay intro/info and get started/learn more buttons
				# Set up the modal dialog for Get Started, along with javascript to enable actions/button events
				echo '<section id="intro" class="container">';
				echo '<div class="row">
						<div class="4u 12u(mobile)">
							<section class="first">
								<i class="icon featured fa-cog"></i>
								<header>
									<h2>Find Your Lost Items</h2>
								</header>
								<p>Lost something? Let Limbo find your item for you.</p>
							</section>
						</div>
						<div class="4u 12u(mobile)">
							<section class="middle">
								<i class="icon featured alt fa-flash"></i>
								<header>
									<h2>Quick & Easy</h2>
								</header>
								<p>Limbo is a lightweight, user-friendly system, so that you can focus on finding or reporting items as quickly as possible.</p>
							</section>
						</div>
						<div class="4u 12u(mobile)">
							<section class="last">
								<i class="icon featured alt2 fa-star"></i>
								<header>
									<h2>Security & Privacy</h2>
								</header>
								<p>We incorporate several filters to keep your item details secure, so that you can have a peace of mind.</p>
							</section>
						</div>
					</div>
					<footer>
						<ul class="actions">
							<li><input class="button big" type="button" id="getstartedbtn" value="Get Started"></li>
							<li><a href="faq.php" class="button alt big">Learn More</a></li>
						</ul>
						<dialog style="width:25%; height:60%;" id="getstartedDialog">
							<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
							<section class="box">
								<section>
									<p><h3>Did you lose an Item?</h3>
									<a style="font-size:20px;" href="lost.php">Lost Items</a><br>
									<a style="font-size:20px;" href="lost-1.php">Report Lost Item</a><p>
									<br>
									<p><h3>Did you find an Item?</h3>
									<a style="font-size:20px;" href="found.php">Found Items</a><br>
									<a style="font-size:20px;" href="found-1.php">Report found Item</a><p>
						    </section>
						    <menu>
						      <button id="cancelDialogGS" type="reset">Cancel</button>
						    </menu>
								</section>
						  </form>
						</dialog>

						<script>
						  (function() {
						    var Item = document.getElementById(\'getstartedbtn\');
						    var favDialog = document.getElementById(\'getstartedDialog\');
								var cancelButton = document.getElementById(\'cancelDialogGS\');

						    // Update buttons opens a modal dialog
								Item.addEventListener(\'click\', function() {
						      favDialog.showModal();
						    });

						    // Form cancel button closes the dialog box
						    cancelButton.addEventListener(\'click\', function() {
						      favDialog.close();
						    });
							})();
						</script>
					</footer>
					</section>
					</div>
					</div>';

				# Display the main content
				echo '<div id="main-wrapper">';
				echo '<div class="container">';
				echo '<div class="row">
							<div class="12u">
							<!-- Recent Items -->
							<section>
								<header class="major">
									<h2>Recent Items</h2>
								</header>
							</div>
							</div>';

				# Keep track of the iterations, so that it can be determined when a new line is required (after 3 items in a row)
				$count = 0;
				echo '<div class="row">';
				while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
				{

					# If three items are already in a row, add new items to next row
					if (($count % 3 == 0) && ($count != 0)){
						echo '</div>';
						echo '<div class="row">';
					}

					# If there is no image url provided for the item use a default image
					if (empty($row['image_url'])){
						$image_url = 'images/pic01.jpg';
					}
					else {
						$image_url = $row['image_url'];
					}

					# Quick links to the item, clicking on the picture should also have the same result
					$imglink = '<a href="ql.php?id=' . $row['id'] . '&p=' . $p . '" class="image scaled"><img src="' . $image_url . '" alt="" /></a>';
					$alink = '<A HREF=ql.php?id=' . $row['id'] . '&p=' . $p . '>' . $row['description'] . '</A>';

					echo '<div class="4u 12u(mobile)">
							<section class="box">
								<p>' . $imglink . '</p>
								<header>
									<h3>' . $alink . '</h3>
								</header>
								<p>Date: ' . $row['create_date'] . '</p>
								<p>Status: ' . $row['status'] . '</p>
								<p>Location: ' . $row['name'] . '</p>
							</section>
						</div>';
					$count++;
				}

				# Close the row, container and main-wrapper
				echo '</div>
							</section>
							</div></div>';
			}
			break;

		# See comments for /admins.php for details, same applies here
		case '/found.php':
			$p = 1;

			$query =
				'SELECT stuff.id, stuff.create_date, stuff.description, stuff.status, locations.name, stuff.image_url
				FROM stuff, locations
				WHERE stuff.location_id = locations.id
				AND status = \'lost\'';

			$results = mysqli_query($dbc, $query);
			check_results($results);

			# Show results
			if( $results ) {
				# But...wait until we know the query succeed before
				# rendering the table start.
				echo '</div></div>';
				echo '<div id="main-wrapper">';
				echo '<div class="container">';
				echo '<div class="row">';
				echo '<div class="4u 12u(mobile)">';
				echo '<section class="box">';
				echo '<H2>Found Something?</H2>';
				echo '<p>If you found something, you\'re in luck! This is the place to report it. Search for the item below:</p>';
				# Show search box
				show_form($dbc, 'found_search');
				echo '</section>';
				echo '</div>';

				$count = 0;
				while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
				{

					if (($count % 2 == 0) && ($count != 0)){
						echo '</div>';
						echo '<div class="row">';
					}

					if (empty($row['image_url'])){
						$image_url = 'images/pic01.jpg';
					}
					else {
						$image_url = $row['image_url'];
					}

					$imglink = '<a href="ql.php?id=' . $row['id'] . '&p=' . $p . '" class="image scaled"><img src="' . $image_url . '" alt="" /></a>';
					$alink = '<A HREF=ql.php?id=' . $row['id'] . '&p=' . $p . '>' . $row['description'] . '</A>';

					echo '<div class="4u 12u(mobile)">
							<section class="box">
								<p>' . $imglink . '</p>
								<header>
									<h3>' . $alink . '</h3>
								</header>
								<p>Date: ' . $row['create_date'] . '</p>
								<p>Status: ' . $row['status'] . '</p>
								<p>Location: ' . $row['name'] . '</p>
							</section>
						</div>';
					$count++;
				}

				echo '</div>';
				echo '</div></div>';
				echo '</div>';
			}

			break;
		case '/found-1.php':
				report($dbc, $cur_page);
			return;
		case '/lost.php':
			$p = 2;

			$query =
				'SELECT stuff.id, stuff.create_date, stuff.description, stuff.status, locations.name, stuff.image_url
				FROM stuff, locations
				WHERE stuff.location_id = locations.id
				AND status = \'found\'';
				#AND ' . strtotime('stuff.update_date') . ' > ' . strtotime($date . '  -14 days' 3);

			$results = mysqli_query($dbc, $query);
			check_results($results);

			# Show results
			if ($results) {
				echo '</div></div>';
				echo '<div id="main-wrapper">';
				echo '<div class="container">';
				echo '<div class="row">';
				echo '<div class="4u 12u(mobile)">';
				echo '<section class="box">';
				echo '<H2>Lost Something?</H2>';
				echo '<p>If you lost something, you\'re in luck! This is the place to report it. Search for your item below:</p>';
				show_form($dbc, 'lost_search');
				echo '</section>';
				echo '</div>';

				$count = 0;
				while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
				{

					# If three items are already in a row, add new items to next row
					if (($count % 2 == 0) && ($count != 0)){
						echo '</div>';
						echo '<div class="row">';
					}

					if (empty($row['image_url'])){
						$image_url = 'images/pic01.jpg';
					}
					else {
						$image_url = $row['image_url'];
					}

					$imglink = '<a href="ql.php?id=' . $row['id'] . '&p=' . $p . '" class="image scaled"><img src="' . $image_url . '" alt="" /></a>';
					$alink = '<A HREF=ql.php?id=' . $row['id'] . '&p=' . $p . '>' . $row['description'] . '</A>';

					echo '<div class="4u 12u(mobile)">
							<section class="box">
								<p>' . $imglink . '</p>
								<header>
									<h3>' . $alink . '</h3>
								</header>
								<p>Date: ' . $row['create_date'] . '</p>
								<p>Status: ' . $row['status'] . '</p>
								<p>Location: ' . $row['name'] . '</p>
							</section>
						</div>';
					$count++;
				}
				#show_records($results, $p);
				echo '</div>';
				echo '</div></div>';
				echo '</div>';
			}

			break;
		# Lost item reporting page
		case '/lost-1.php':
			report($dbc, $cur_page);
			return;
		case '/ql.php':
			echo '<TITLE>Limbo - Quick Link</TITLE>';
			echo '</div></div>';
			echo '<div id="main-wrapper">';
			echo '<div class="container">';
			echo '<section>';
			return;
		# Users quick link for admins
		case '/ql_users.php':
			echo '<TITLE>Limbo - Users</TITLE>';
			echo '</div></div>';
			echo '<div id="main-wrapper">';
			echo '<div class="container">';
			echo '<section>';
			return;
		case '/admins.php':
			$p = 3;

			$query =
				'SELECT stuff.id, stuff.create_date, stuff.description, stuff.status, locations.name, stuff.image_url
				FROM stuff, locations
				WHERE stuff.location_id = locations.id';

			$results = mysqli_query($dbc, $query);
			check_results($results);

			echo '<TITLE>Limbo - Admins</TITLE>';
			echo '</div></div>';
			echo '<div id="main-wrapper">';
			echo '<div class="container">';
			echo '<div class="12u">';
			echo '<section>';
			echo '<p>Click on an Item or User to View/Edit details</p>';
			echo '<header class="major">
							<h2>Items</h2>
						</header>
						</section>';

			if($results){
				show_records($results, $p);
			}

			$query =
				'SELECT id, user_id, first_name, last_name, email, pass, reg_date
				FROM users';

			$results = mysqli_query($dbc, $query);
			check_results($results);

			echo '<section>';
			echo '<header class="major">
							<h2>Users</h2>
						</header>
						</section>';

			if($results){
				show_user_records($results);
			}

			echo '</div></div>';
			echo '</div></div>';

			break;
		case '/faq.php':
			echo '<section id="banner">
					<header>
						<h2>FAQ</h2>
						<p>Welcome to the FAQ section!</p>
					</header>
				</section>';

			echo '</div></div>';
			echo '<div id="main-wrapper">';
			echo '<div class="container">';
			echo '<div class="12u">
						<!-- Recent Items -->
						<section>
							<header class="major">
								<h2>Frequently Asked Questions</h2>
							</header>
						</div>
						<div class="row">
						<div class="6u 12u(mobile)">
						<section class="box">
						<header>
							<h2>How do I report a lost or found item?</h2>
						</header>
						<p>At the home page, you will see a get started button. This will guide you through a series of steps to either report a lost item or claim a found item.</p>
						</section>
						</div>
						<div class="6u 12u(mobile)">
						<section class="box">
						<header>
							<h2>How will I know when someone has found my item?</h2>
						</header>
						<p>When an item has been found, you will receive an e-mail with instructions on claiming your item.</p>
						</div>
						</div>
						</div></div>';
			return;
		case '/search.php':
			echo '</div>
						<div class="main-wrapper">
						<div class="container">
						<div class="12u">
			';
			return;
	}

	# Free up the results in memory
	mysqli_free_result( $results ) ;
}

# Diplays the supplied results in a table, also taking into account the current page
function show_records($results, $p){
	echo '<section class="box">';
	echo '<TABLE class="default">';
	echo '<TR>';
	echo '<TH>Date</TH>';
	echo '<TH>Description</TH>';
	echo '<TH>Status</TH>';
	echo '<TH>Location</TH>';
	echo '</TR>';

	while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
	{
		$alink = '<A HREF=ql.php?id=' . $row['id'] . '&p=' . $p . '>' . $row['description'] . '</A>';
		echo '<TR>' ;
		echo '<TD>' . $row['create_date'] . '</TD>' ;
		echo '<TD>' . $alink . '</TD>' ;
		echo '<TD>' . $row['status'] . '</TD>' ;
		echo '<TD>' . $row['name'] . '</TD>' ;
	}

	# End the table
	echo '</TABLE>';
	echo '</section>';
}

# Displays user records from the supplied results
function show_user_records($results){
	echo '<section class="box">';
	echo '<TABLE class="default">';
	echo '<TR>';
	echo '<TH>User ID</TH>';
	echo '<TH>First Name</TH>';
	echo '<TH>Last Name</TH>';
	echo '<TH>Email</TH>';
	echo '<TH>Password</TH>';
	echo '<TH>Registration Date</TH>';
	echo '</TR>';

	while ( $row = mysqli_fetch_array( $results , MYSQLI_ASSOC ) )
	{
		$ulink = '<A HREF=ql_users.php?id=' . $row['id'] . '>' . $row['first_name'] . '</A>';
		echo '<TR>' ;
		echo '<TD>' . $row['user_id'] . '</TD>' ;
		echo '<TD>' . $ulink . '</TD>' ;
		echo '<TD>' . $row['last_name'] . '</TD>' ;
		echo '<TD>' . $row['email'] . '</TD>' ;
		echo '<TD>' . $row['pass'] . '</TD>' ;
		echo '<TD>' . $row['reg_date'] . '</TD>' ;
	}

	# End the table
	echo '</TABLE>';
	echo '</section>';
	echo '<input type="button" id="addbtn" value="Add New User"/>';

	# Set up dialog from for adding new user
	echo '<!-- Simple pop-up dialog box, containing a form -->
		<dialog id="userDialog">
			<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
			<section class="box">
				<section>
				<p><label>User Name:</label>
				<input required type="text" name="user_id" /></p>
				<p><label>First Name:</label>
				<input type="text" name="firstname" /></p>
				<p><label>Last Name:</label>
				<input type="text" name="lastname" /></p>
				<p><label>Email:</label>
				<input type="text" name="email" /></p>
				<p><label>Password:</label>
				<input required type="password" name="pass" /></p>
				</section>
				<menu>
					<button id="cancelDialog" type="reset">Cancel</button>
					<button type="submit">Confirm</button>
				</menu>
			</section>
			</form>
		</dialog>

		<script>
			(function() {
				var contactForItem = document.getElementById(\'addbtn\');
				var favDialog = document.getElementById(\'userDialog\');
				var cancelButton = document.getElementById(\'cancelDialog\');

				// Update buttons opens a modal dialog
				contactForItem.addEventListener(\'click\', function() {
					favDialog.showModal();
				});

				// Form cancel button closes the dialog box
				cancelButton.addEventListener(\'click\', function() {
					favDialog.close();
				});
			})();
		</script>';
}

# Displays the page footer
function show_footer(){
	echo '<!-- Footer -->
				<div id="footer-wrapper">
					<section id="footer" class="container">
						<div class="row">
							<div class="8u 12u(mobile)">
								<section>
									<header>
										<h2>Whats this all about?</h2>
									</header>
									<a href="#" class="image featured"><img src="http://www.marist.edu/summerinstitutes/images/poughkeepsie1.jpg" alt="" /></a>
									<footer>
										<a href="faq.php" class="button">Find out more</a>
									</footer>
								</section>
							</div>
							<div class="4u 12u(mobile)">
								<section>
									<header>
										<h2>Contact Us</h2>
									</header>
									<ul class="social">
										<li><a class="icon fa-facebook" href="https://www.facebook.com/marist/"><span class="label">Facebook</span></a></li>
										<li><a class="icon fa-twitter" href="https://twitter.com/marist"><span class="label">Twitter</span></a></li>
										<li><a class="icon fa-linkedin" href="https://www.linkedin.com/in/kslotay"><span class="label">LinkedIn</span></a></li>
									</ul>
									<ul class="contact">
										<li>
											<h3>Address</h3>
											<p>
												Marist College<br />
												33999 North Road<br />
												Poughkeepsie, NY 12601
											</p>
										</li>
										<li>
											<h3>E-Mail</h3>
											<p><a href="mailto:kulvinder.lotay1@marist.edu">kulvinder.lotay1@marist.edu</a></p>
											<p><a href="mailto:artur.barbosa1@marist.edu">artur.barbosa1@marist.edu</a></p>
										</li>
										<li>
											<h3>Phone</h3>
											<p>(845) 575-3000</p>
										</li>
									</ul>
								</section>
							</div>
						</div>
						<div class="row">
							<div class="12u">

								<!-- Copyright -->
									<div id="copyright">
										<ul class="links">
											<li><strong>&copy; Limbo</strong>. All rights reserved.</li><li><strong>Creators</strong>: Kulvinder Lotay and Artur Barbosa</li>
										</ul>
									</div>

							</div>
						</div>
					</section>
				</div>
			</div>';
}

# Echos options for a select box, returning the selected location as the selected option
function location_list($dbc, $selected_location){
	# Return all location names and IDs
	$query = 'SELECT locations.id, locations.name
		FROM locations
		ORDER BY locations.name';

	$results = mysqli_query($dbc, $query);
	check_results($results);

	# Each option has a value of the location id, but dispays the location name for user-friendliness
	if($results){
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
			if($row['name'] == $selected_location){
				echo '<option selected="selected" value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
			else{
				echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
			}
		}
	}
}

# Report form
function report($dbc, $cur_page){

	$query = 'SELECT locations.id, locations.name
		FROM locations
		ORDER BY locations.name';

	$results = mysqli_query($dbc, $query);
	check_results($results);

	switch($cur_page){
		case '/found-1.php':
			$email = "Finder email address: *";
			$emailn = "finder";
			$heading = "Report Found Item";
			break;
		case '/lost-1.php';
			$email = "Owner email address: *";
			$emailn = "owner";
			$heading = "Report Lost Item";
			break;
	}

	if($results){
		echo '<!-- Simple pop-up dialog box, containing a form -->
			</div></div>
			<div id="main-wrapper">
				<div class="container">
				<div class="12u">
				<header class="major">
					<h2>' . $heading . '</h2>
				</header>
				<form action="' . $cur_page . '" method="post">
					<section class="box">
						<section>
							<p><label>Please describe the item: *</label>
							<input name="desc" type="text" required class="image scaled"/>
							<div class="row">
							<div class="4u 12u(mobile)">
							<p><label>Location: *</label>
							<select name="location" id="where">';
							while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
								echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
							}
							echo '</select></p>
							</div>
							<div class="4u 12u(mobile)">
							<p><label>' . $email . '</label>
							<input name=' . $emailn . ' type="text" required /></p>
							</div>
							<div class="4u 12u(mobile)">
							<p><label>Image (Paste URL)</label>
							<input name="img_url" type="text"/></p>
							</div>
							</div>
						</section>
						<menu>
							<button type="submit">Submit</button>
						</menu>
						</section>
					</form>
					<br>
					<p><a href=limbo_landing.php><<< Go Back to Home</a></p>
					</div></div></div>';
		}
	}

# Retrieves a particular record (quick links)
function show_record_admin($dbc, $id, $cur_page) {
	# Return all item details for admin
	$query = 'SELECT stuff.id, stuff.description, locations.name, stuff.create_date, stuff.owner, stuff.finder, stuff.status, stuff.image_url
	 	FROM stuff, locations
		WHERE stuff.location_id = locations.id
		AND stuff.id = ' . $id;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
		$row = mysqli_fetch_array( $results , MYSQLI_ASSOC );

		echo '<h2> Item Details </h2>';
		echo '<p>View item details below:</p>';
		echo '<div class="row">';
		echo '<div class="4u 12u(mobile)">';
		if (empty($row['image_url'])){
			$image_url = 'images/pic01.jpg';
		}
		else {
			$image_url = $row['image_url'];
		}
		echo '<section class="box">';
		echo '<img src="' . $image_url . '" alt="" style="max-width:100%; max-height:100%;"/>';
		echo '</section>';
		echo '</div>';
		echo '<div class="8u 12u(mobile)">';
		echo '<section class="box">';
		echo '<TABLE class="default">';
		echo '<TR>';
		echo '<TH>ID</TH>';
		echo '<TH>Description</TH>';
		echo '<TH>Location</TH>';
		echo '<TH>Date</TH>';
		echo '<TH>Owner</TH>';
		echo '<TH>Finder</TH>';
		echo '<TH>Status</TH>';
		echo '</TR>';

  		# For each row result, generate a table row

		echo '<TR>' ;
		echo '<TD>' . $row['id'] . '</TD>' ;
		echo '<TD>' . $row['description'] . '</TD>' ;
		echo '<TD>' . $row['name'] . '</TD>' ;
		echo '<TD>' . $row['create_date'] . '</TD>' ;
		echo '<TD>' . $row['owner'] . '</TD>' ;
		echo '<TD>' . $row['finder'] . '</TD>' ;
		echo '<TD>' . $row['status'] . '</TD>' ;
		echo '</TR>' ;

		# End the table
		echo '</TABLE>';
		echo '</section>';
		switch ($row['status']){
			case 'lost':
				echo '<menu><input type="button" id="contact" value="Contact Owner"/>';
				break;
			case 'found':
				echo '<menu><input type="button" id="contact" value="Contact finder"/>';
				break;
		}
		echo '
			<input type="button" id="editbtn" value="Edit Item"/>
			<input type="button" id="deletebtn" value="Remove Item"/></menu>
			';
		echo '</div></div>';

		echo '<!-- Simple pop-up dialog box, containing a form -->
			<dialog id="contactDialog">
			  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="hidden" name="dialog" value="contact">
				<section class="box">
					<section>
						<p><label>Please enter your email address: *</label>
						<input required type="text" name="email"/></p>
			      <p><label>Please input your message: *</label>
			      <textarea required name="msg" class="image scaled" cols=75></textarea>
						<input type="hidden" name="id" value="' . $id . '"></p>
			    </section>
			    <menu>
			      <button id="cancelDialog" type="reset">Cancel</button>
			      <button type="submit">Confirm</button>
			    </menu>
				</section>
			  </form>
			</dialog>

			<script>
			  (function() {
			    var contactForItem = document.getElementById(\'contact\');
			    var favDialog = document.getElementById(\'contactDialog\');
					var cancelButton = document.getElementById(\'cancelDialog\');

			    // Update buttons opens a modal dialog
					contactForItem.addEventListener(\'click\', function() {
			      favDialog.showModal();
			    });

			    // Form cancel button closes the dialog box
			    cancelButton.addEventListener(\'click\', function() {
			      favDialog.close();
			    });
				})();
			</script>';

			echo '<!-- Simple pop-up dialog box, containing a form -->
				<dialog id="editItem">
				  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
					<input type="hidden" name="dialog" value="edit">
					<section class="box">
						<section>
							<input type="hidden" name="id" value="' . $id . '">
				      <p><label>Item Description:</label>
							<input required type="text" name="desc" value="' . $row['description'] . '"/></p>
							<p><label>Location:</label>
							<select name="location">';
							location_list($dbc, $row['location']);
							echo '</select></p>
							<p><label>Owner:</label>
							<input type="text" name="owner" value="' . $row['owner'] . '"/></p>
							<p><label>Finder:</label>
							<input type="text" name="finder" value="' . $row['finder'] . '"/></p>
							<p><label>Status:</label>
							<input required type="text" name="status" value="' . $row['status'] . '"/></p>
				    </section>
				    <menu>
				      <button id="cancelDialogEdit" type="reset">Cancel</button>
				      <button type="submit">Confirm</button>
				    </menu>
						</section>
				  </form>
				</dialog>

				<script>
				  (function() {
				    var Item = document.getElementById(\'editbtn\');
				    var favDialog = document.getElementById(\'editItem\');
						var cancelButton = document.getElementById(\'cancelDialogEdit\');

				    // Update buttons opens a modal dialog
						Item.addEventListener(\'click\', function() {
				      favDialog.showModal();
				    });

				    // Form cancel button closes the dialog box
				    cancelButton.addEventListener(\'click\', function() {
				      favDialog.close();
				    });
					})();
				</script>';

		echo '<!-- Simple pop-up dialog box, containing a form -->
			<dialog id="deleteItem">
			  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="hidden" name="dialog" value="delete">
				<section class="box">
					<section>
			      <p><label>Are you sure you want to delete this item?</label>
						<input type="hidden" name="id" value="' . $id . '"></p>
			    </section>
			    <menu>
			      <button id="cancelDialogDelete" type="reset">Cancel</button>
			      <button type="submit">Confirm</button>
			    </menu>
					</section>
			  </form>
			</dialog>

			<script>
			  (function() {
			    var Item = document.getElementById(\'deletebtn\');
			    var favDialog = document.getElementById(\'deleteItem\');
					var cancelButton = document.getElementById(\'cancelDialogDelete\');

			    // Update buttons opens a modal dialog
					Item.addEventListener(\'click\', function() {
			      favDialog.showModal();
			    });

			    // Form cancel button closes the dialog box
			    cancelButton.addEventListener(\'click\', function() {
			      favDialog.close();
			    });
				})();
			</script>';

		echo '<br>';
		echo '<a href=admins.php><<< Go Back</a><br>';
		echo '<a href=limbo_landing.php><<< Go Back to Home</a>';

		echo '</div></div>';

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

function show_record($dbc, $id, $cur_page) {
	# Create a query to get the name and price sorted by price
	$query = 'SELECT stuff.description, locations.name, stuff.update_date, stuff.owner, stuff.finder, stuff.status, stuff.image_url
		FROM stuff, locations
		WHERE stuff.location_id = locations.id
		AND stuff.id = ' . $id;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
		$row = mysqli_fetch_array( $results , MYSQLI_ASSOC );

		echo '<h2> Item Details </h2>';
		echo '<p>View item details below:</p>';
		echo '<div class="row">';
		echo '<div class="4u 12u(mobile)">';
		if (empty($row['image_url'])){
			$image_url = 'images/pic01.jpg';
		}
		else {
			$image_url = $row['image_url'];
		}
		echo '<section class="box">';
		echo '<img src="' . $image_url . '" alt="" style="max-width:100%; max-height:100%;"/>';
		echo '</section>';
		echo '</div>';
		echo '<div class="8u 12u(mobile)">';
		echo '<section class="box">';
		echo '<TABLE class="default">';
		echo '<TR>';
		echo '<TH>Description</TH>';
		echo '<TH>Location</TH>';
		echo '<TH>Date</TH>';
		echo '<TH>Status</TH>';
		echo '</TR>';

  		# For each row result, generate a table row


		echo '<TR>' ;
		echo '<TD>' . $row['description'] . '</TD>' ;
		echo '<TD>' . $row['name'] . '</TD>' ;
		echo '<TD>' . $row['update_date'] . '</TD>' ;
		echo '<TD>' . $row['status'] . '</TD>' ;
		echo '</TR>' ;

		# End the table
		echo '</TABLE>';
		echo '</section>';
		switch ($row['status']){
			case 'lost':
				echo '<menu><input type="button" id="contact" value="Contact Owner"/>
							<input type="button" id="claimbtn" value="Claim Found"/></menu>';
				break;
			case 'found':
				echo '<menu><input type="button" id="contact" value="Contact finder"/>
							<input type="button" id="claimbtn" value="Claim Item"/></menu>';
				break;
		}
		echo '</div></div>';

		echo '<!-- Simple pop-up dialog box, containing a form -->
			<dialog id="contactDialog">
			  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="hidden" name="dialog" value="contact"/>
				<section class="box">
					<section>
						<p><label>Please enter your email address: *</label>
						<input required type="text" name="email"/></p>
			      <p><label>Please input your message: *</label>
			      <textarea required name="msg" class="image scaled" cols=75></textarea>
						<input type="hidden" name="id" value="' . $id . '"></p>
			    </section>
			    <menu>
			      <button id="cancelDialog" type="reset">Cancel</button>
			      <button type="submit">Confirm</button>
			    </menu>
					</section>
			  </form>
			</dialog>

			<script>
			  (function() {
			    var contactForItem = document.getElementById(\'contact\');
			    var favDialog = document.getElementById(\'contactDialog\');
					var cancelButton = document.getElementById(\'cancelDialog\');

			    // Update buttons opens a modal dialog
					contactForItem.addEventListener(\'click\', function() {
			      favDialog.showModal();
			    });

			    // Form cancel button closes the dialog box
			    cancelButton.addEventListener(\'click\', function() {
			      favDialog.close();
			    });
				})();
			</script>';

		if($row['status'] == 'lost'){
			$claim = 'claimfound';
			$claimfrom = 'owner';
			echo '<!-- Simple pop-up dialog box, containing a form -->
				<dialog id="claimDialog">
				  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
					<input type="hidden" name="dialog" value="' . $claim . '"/>
					<section class="box">
						<section>
							<p><label>Please enter the ' . $claimfrom . ' email address (you can get in touch with them via the contact button): *</label>
							<input required type="text" name="email"/></p>
							<input type="hidden" name="id" value="' . $id . '">
				    </section>
				    <menu>
				      <button id="cancelDialogClaim" type="reset">Cancel</button>
				      <button type="submit">Confirm</button>
				    </menu>
						</section>
				  </form>
				</dialog>

				<script>
				  (function() {
				    var Item = document.getElementById(\'claimbtn\');
				    var favDialog = document.getElementById(\'claimDialog\');
						var cancelButton = document.getElementById(\'cancelDialogClaim\');

				    // Update buttons opens a modal dialog
						Item.addEventListener(\'click\', function() {
				      favDialog.showModal();
				    });

				    // Form cancel button closes the dialog box
				    cancelButton.addEventListener(\'click\', function() {
				      favDialog.close();
				    });
					})();
				</script>';
		}
		else if($row['status'] == 'found'){
			$claim = 'claimitem';
			$claimfrom = 'finder';
			echo '<!-- Simple pop-up dialog box, containing a form -->
				<dialog id="claimDialog">
				  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
					<input type="hidden" name="dialog" value="' . $claim . '"/>
					<section class="box">
						<section>
							<p><label>Please enter the ' . $claimfrom . ' email address (you can get in touch with them via the contact button): *</label>
							<input required type="text" name="email"/></p>
							<input type="hidden" name="id" value="' . $id . '">
				    </section>
				    <menu>
				      <button id="cancelDialogClaim" type="reset">Cancel</button>
				      <button type="submit">Confirm</button>
				    </menu>
						</section>
				  </form>
				</dialog>

				<script>
				  (function() {
				    var Item = document.getElementById(\'claimbtn\');
				    var favDialog = document.getElementById(\'claimDialog\');
						var cancelButton = document.getElementById(\'cancelDialogClaim\');

				    // Update buttons opens a modal dialog
						Item.addEventListener(\'click\', function() {
				      favDialog.showModal();
				    });

				    // Form cancel button closes the dialog box
				    cancelButton.addEventListener(\'click\', function() {
				      favDialog.close();
				    });
					})();
				</script>';
		}

		echo '<br>';
		switch ($cur_page){
			case 0:
				echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
				break;
			case 1:
				echo '<a href=found.php><<< Go Back</a><br>';
				echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
				break;
			case 2:
				echo '<a href=lost.php><<< Go Back</a><br>';
				echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
				break;
			default:
				echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
		}

		echo '</div></div>';

		# echo '<a href=limbo_landing.php><<< Go Back to Home</a>';

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

function show_user_record($dbc, $id) {
	# Create a query to get the name and price sorted by price
	$query =
		'SELECT id, user_id, first_name, last_name, email, pass, reg_date
		FROM users
		WHERE id = ' . $id;

	# Execute the query
	$results = mysqli_query( $dbc , $query ) ;
	check_results($results) ;

	# Show results
	if( $results )
	{
  		# But...wait until we know the query succeed before
  		# rendering the table start.
		$row = mysqli_fetch_array( $results , MYSQLI_ASSOC );

		echo '<h2> User Details </h2>';
		echo '<p>View user details below:</p>';
		echo '<div class="row">';
		echo '<div class="12u 12u(mobile)">';
		echo '<section class="box">';
		echo '<TABLE class="default">';
		echo '<TR>';
		echo '<TH>ID</TH>';
		echo '<TH>User Name</TH>';
		echo '<TH>First Name</TH>';
		echo '<TH>Last Name</TH>';
		echo '<TH>Email</TH>';
		echo '<TH>Password</TH>';
		echo '<TH>Registration Date</TH>';
		echo '</TR>';

  		# For each row result, generate a table row


		echo '<TR>' ;
		echo '<TD>' . $row['id'] . '</TD>' ;
		echo '<TD>' . $row['user_id'] . '</TD>' ;
		echo '<TD>' . $row['first_name'] . '</TD>' ;
		echo '<TD>' . $row['last_name'] . '</TD>' ;
		echo '<TD>' . $row['email'] . '</TD>' ;
		echo '<TD>' . $row['pass'] . '</TD>' ;
		echo '<TD>' . $row['reg_date'] . '</TD>' ;
		echo '</TR>' ;

		# End the table
		echo '</TABLE>';
		echo '</section>';
		echo '</div></div><br>';

		echo '<menu><input type="button" id="editbtn" value="Edit User"/>
		<input type="button" id="deletebtn" value="Remove User"/></menu>';

		echo '<!-- Simple pop-up dialog box, containing a form -->
			<dialog id="editDialog">
				<form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="hidden" name="dialog" value="edituser">
				<section class="box">
					<section>
						<input type="hidden" name="id" value="' . $row['id'] . '">
						<p><label>User Name:</label>
						<input required type="text" name="user_id" value="' . $row['user_id'] . '"/></p>
						<p><label>First Name:</label>
						<input type="text" name="firstname" value="' . $row['first_name'] . '"/></p>
						<p><label>Last Name:</label>
						<input type="text" name="lastname" value="' . $row['last_name'] . '"/></p>
						<p><label>Email:</label>
						<input type="text" name="email" value="' . $row['email'] . '"/></p>
						<p><label>Password:</label>
						<input required type="text" name="pass" value="' . $row['pass'] . '"/></p>
					</section>
					<menu>
						<button id="cancelDialogEdit" type="reset">Cancel</button><br>
						<button type="submit">Confirm</button>
					</menu></p>
					</section>
				</form>
			</dialog>

			<script>
				(function() {
					var Item = document.getElementById(\'editbtn\');
					var favDialog = document.getElementById(\'editDialog\');
					var cancelButton = document.getElementById(\'cancelDialogEdit\');

					// Update buttons opens a modal dialog
					Item.addEventListener(\'click\', function() {
						favDialog.showModal();
					});

					// Form cancel button closes the dialog box
					cancelButton.addEventListener(\'click\', function() {
						favDialog.close();
					});
				})();
			</script>';

		echo '<!-- Simple pop-up dialog box, containing a form -->
			<dialog id="deleteDialog">
			  <form action="' . $_SERVER['PHP_SELF'] . '" method="post">
				<input type="hidden" name="dialog" value="deleteuser">
				<section class="box">
					<section>
			      <p><label>Are you sure you want to delete this user?</label>
						<input type="hidden" name="id" value="' . $id . '"></p>
			    </section>
			    <menu>
			      <button id="cancelDialogDelete" type="reset">Cancel</button>
			      <button type="submit">Confirm</button>
			    </menu>
					</section>
			  </form>
			</dialog>

			<script>
			  (function() {
			    var Item = document.getElementById(\'deletebtn\');
			    var favDialog = document.getElementById(\'deleteDialog\');
					var cancelButton = document.getElementById(\'cancelDialogDelete\');

			    // Update buttons opens a modal dialog
					Item.addEventListener(\'click\', function() {
			      favDialog.showModal();
			    });

			    // Form cancel button closes the dialog box
			    cancelButton.addEventListener(\'click\', function() {
			      favDialog.close();
			    });
				})();
			</script>';

		echo '<br>';
		echo '<a href=admins.php><<< Go Back</a><br>';
		echo '<a href=limbo_landing.php><<< Go Back to Home</a>';
		echo '</div></div>';

  		# Free up the results in memory
  		mysqli_free_result( $results ) ;
	}
}

# Inserts a record into the prints table
function insert_record($dbc, $desc, $location, $owner, $finder, $status, $image_url) {
	if (empty($image_url)){
		$image_url = 'images/pic01.jpg';
	}

	$query = 'INSERT INTO stuff(location_id, description, create_date, update_date, owner, finder, status, image_url) VALUES (' . $location . ' , "' . $desc . '" , now(), now(), "' . $owner . '", "' . $finder . '", "' . $status . '", "' . $image_url . '")' ;
	show_query($query);

	$results = mysqli_query($dbc,$query) ;
	check_results($results) ;

	return $results ;
}

function send_email($to, $from, $subject, $message){
	$headers = 'From: ' . $from . "\r\n" .
				'Cc: limbo@marist.edu' . "\r\n" .
        'Reply-To: limbo@marist.edu' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $message, $headers);

	echo "Your message has been sent.";
}

# Shows the query as a debugging aid
function show_query($query) {
	global $debug;

	if($debug)
	echo "<p>Query = $query</p>" ;
}

# Checks the query results as a debugging aid
function check_results($results) {
	global $dbc;

	if($results != true)
    echo '<p>SQL ERROR = ' . mysqli_error( $dbc ) . '</p>'  ;
}

# Number validation, check for empty input, non-numeric input and decimal and negative numbers
function valid_number($num) {
	if(empty($num) || !is_numeric($num)) {
		return false;
	}
	else {
		$num = intval($num);
		if ($num <= 0){
			return false;
		}
		return true;
	}
}

# Name validation, check if name is empty
function valid_name($name) {
	if(empty($name)){
		return false;
	}
	else {
		return true;
	}
}

# Show form
function show_form($dbc, $type){
	$query = 'SELECT locations.id, locations.name
		FROM locations
		ORDER BY locations.name';

	$results = mysqli_query($dbc, $query);
	check_results($results);
	if($results){
		switch ($type){
			case 'found_search':
				echo '<form action="search.php" method="POST">';
				echo '<input type="hidden" name="type" value="lost" placeholder="Describe your item here" />';
				echo '<p>Description: <input type="text" name="desc" placeholder="Describe your item here" /></p>' ;
				echo '<p>Location:';
				echo '<select name="location" id="where">';
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
					echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
				}
				echo '</select></p>';
				echo '<p><input value="Search" type="submit"></p>' ;
				break;
			case 'lost_search':
				echo '<form action="search.php" method="POST">';
				echo '<input type="hidden" name="type" value="found" placeholder="Describe your item here" />';
				echo '<p>Description: <input type="text" name="desc" placeholder="Describe your item here" /></p>' ;
				echo '<p>Location:';
				echo '<select name="location" id="where">';
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
					echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
				}
				echo '</select></p>';
				echo '<p><input value="Search" type="submit"></p>' ;
				break;
		}
	}
}
?>
