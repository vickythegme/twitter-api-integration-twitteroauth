<?php
/* Start session and load lib */
session_start();
require_once('twitteroauth/twitteroauth.php');
require_once('config.php');

/* If the oauth_token is old redirect to the connect page. */
if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
  $_SESSION['oauth_status'] = 'oldtoken';
  header('Location: ./clearsessions');
}
$access_token = $_SESSION['access_token'];
// get the oauth token from the db and create a new twitter oauth object
$user_id = $access_token['user_id'];
$sel_sql = "select * from vitwit_users where user_id = '$user_id'";
$query_res = mysqli_query($con,$sel_sql);
$fetch_query = mysqli_fetch_array($query_res);
$oauth_token=isset($fetch_query['oauth_token'])?$fetch_query['oauth_token']:$access_token['oauth_token'];
$oauth_secret=isset($fetch_query['oauth_secret'])?$fetch_query['oauth_secret']:$access_token['oauth_token_secret'];
/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET,$oauth_token, $oauth_secret);
/* If method is set change API call made. Test is called by default. */
$content = $connection -> get('account/verify_credentials');
//view the full content by dumping the json output
//var_dump($content);
?>
<p>Name : <?php echo $content -> name; ?></p>
<p>Screen Name : <?php echo $content -> screen_name; ?></p>
<p>Description : <?php echo $content -> description; ?></p>
<p>
<a href="logout.php"> Logout </a>
</p>
