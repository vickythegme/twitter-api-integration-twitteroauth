<?php
/**
 * @file
 * Take the user when they return from Twitter. Get access tokens.
 * Verify credentials and redirect to based on response from Twitter.
 */

/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions');
}

/* Create TwitteroAuth object with app key/secret and token key/secret from default phase */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);

/* Request access tokens from twitter */
$access_token = $connection->getAccessToken($_REQUEST['oauth_verifier']);

// query to save the long lasting oauth tokens to the db for future uses.
$screenname = $access_token['screen_name'];
$user_id = $access_token['user_id'];
$logdate = date('Y-m-d');
$oauth_token = $access_token['oauth_token'];
$oauth_secret = $access_token['oauth_token_secret'];
$ins_sql = "insert into vitwit_users (user_id, screenname, logdate, oauth_token, oauth_secret) values('$user_id', '$screenname', '$logdate', '$oauth_token', '$oauth_secret') ";
$upd_sql = "update vitwit_users set oauth_token = '$oauth_token', oauth_secret = '$oauth_secret', logdate = '$logdate', screenname = '$screenname' where user_id='$user_id'";
$fetch_query = "select * from vitwit_users where user_id = '$user_id'";
$fetch_sql = mysqli_query($con,$fetch_query);
if(mysqli_num_rows($fetch_sql) > 0) {
	mysqli_query($con,$upd_sql);	
}
else {
	mysqli_query($con,$ins_sql);
}
//db insertion query ends here
/* Save the access tokens. Normally these would be saved in a database for future use. */
$_SESSION['access_token'] = $access_token;

/* Remove no longer needed request tokens */
unset($_SESSION['oauth_token']);
unset($_SESSION['oauth_token_secret']);

/* If HTTP response is 200 continue otherwise send to connect page to retry */
if (200 == $connection->http_code) {
  /* The user has been verified and the access tokens can be saved for future use */
  $_SESSION['status'] = 'verified';
  header('Location: ./tweet.php');
} else {
  /* Save HTTP status for error dialog on connnect page.*/
  header('Location: ./clearsessions.php');
}
