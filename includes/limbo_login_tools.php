<!-- Kulvinder Lotay and Artur Barbosa -->
<?php
# Includes these helper functions
require( 'includes/helpers.php' ) ;

# Secure Session
function sec_session_start() {
    $session_name = 'sec_session_id';   # Set a custom session name
    $secure = SECURE;
    # This stops JavaScript being able to access the session id.
    $httponly = true;
    # Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    # Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    # Sets the session name to the one set above.
    session_name($session_name);
    session_start();            # Start the PHP session
    session_regenerate_id(true);    # regenerated the session, delete the old one.
}

# Loads a specified or default URL.
function load( $page = 'admins.php', $pid = -1 )
{
  # Begin URL with protocol, domain, and current directory.
  $url = 'http://' . $_SERVER[ 'HTTP_HOST' ] . dirname( $_SERVER[ 'PHP_SELF' ] ) ;

  # Remove trailing slashes then append page name to URL and the president id.
  $url = rtrim( $url, '/\\' ) ;
  $url .= '/' . $page ;

  # Execute redirect then quit.
  sec_session_start( );
  $_SESSION['pid'] = $pid;
  header( "Location: $url" ) ;
  exit() ;
}

# Validates the print name.
# Returns -1 if validate fails, and >= 0 if it succeeds
# which is the primary key id.
function validate($user_id, $pass)
{
  global $dbc;

  if(empty($user_id)){
		return -2;
	}
  else if (empty($pass)){
    return -3;
  }

  if($user_id != 'admin'){
    $hash = crypt($pass, $email);
  }
  else {
    $hash = $pass;
  }

  # Make the query
  $query = "SELECT id, user_id, email, pass FROM users WHERE user_id='" . $user_id . "'" ;

  # Execute the query
  $results = mysqli_query( $dbc, $query ) ;
  check_results($results);

  # If we get no rows, the login failed
  if (mysqli_num_rows( $results ) == 0 ){
		return -1;
	}

  # We have at least one row, so get the first one and return it
  while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)){
    if($row['pass'] == $hash){
      $pid = $row ['id'];

      return intval($pid);
    }
    else {
      return -4;
    }
  }
}
?>
