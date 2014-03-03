<?

include("include/include.php");
$title = 'Log Out';


session_start();
session_register("username");
session_register("login");
$u = $_SESSION['username'];
$t = $_SESSION['login'];


include("include/header.php");


$query2 = "UPDATE login SET last_login='$t' WHERE username='$u'";
$r2 = @mysql_query ($query2);
	
$query3 = "UPDATE login SET loggedin='n' WHERE username='$u'";
$r3 = @mysql_query ($query3);
	
session_unregister("username");
session_unregister("nickname");
session_unregister("access");
session_unregister("login");
	
echo '<p class="success">You have successfully logged out.</p>' . "\n";
	
echo '<p>[ <a href="/">Back</a> ]</p>';

include("include/footer.php");
?>