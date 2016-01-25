<?

include("include/include.php");
$title = 'Log Out';

session_start();
$u = $_SESSION['username'];
$t = $_SESSION['login'];

include("include/header.php");

$query2 = "UPDATE login SET last_login='$t' WHERE username='$u'";
$r2 = pg_query($query2);

$query3 = "UPDATE login SET loggedin='n' WHERE username='$u'";
$r3 = pg_query($query3);

session_unset();

echo '<p class="success">You have successfully logged out.</p>' . "\n";

echo '<p>[ <a href="/">Back</a> ]</p>';

include("include/footer.php");
?>
