<?

include("include/include.php");
$title = 'Log In';

session_start();

$t = "SELECT CURRENT_TIMESTAMP() FROM login";
//$tr = @mysql_query ($t);
//$trow = mysql_fetch_row ($tr);
$tr = pg_query($t);
$trow = pg_fetch_row($tr);

$_SESSION['login'] = $trow[0];

if (isset($_POST['submit'])) {
	if (empty($_POST['username'])) {
		$u = false;
		$message = $message . '<p class="error">You forgot you enter your username.</p>' . "\n";
	}
	else {
		$u = $_POST['username'];
	}

	if (empty($_POST['password'])) {
		$p = false;
		$message = $message . '<p class="error">You forgot you enter your password.</p>' . "\n";
	}
	else {
		$p = $_POST['password'];
	}


	if ($u && $p) {
		//$query = "SELECT username, access FROM login WHERE username='$u' AND password=sha1('$p')";
		$query = "SELECT username, access FROM login WHERE username='$u'";
		//$r = @mysql_query ($query);
		//$row = mysql_fetch_array ($r);
		$r = pg_query($query);
		$row = pg_fetch_array($r);

		if ($row) {
			$query2 = "SELECT nickname FROM user WHERE username='$u'";
			//$r2 = @mysql_query ($query2);
			//$row2 = mysql_fetch_array ($r2);
			$r2 = pg_query($query2);
			$row2 = pg_fetch_array($r2);

			$query3 = "UPDATE login SET loggedin='y' WHERE username='$u'";
			//$r3 = @mysql_query ($query3);
			$r3 = pg_query($query3);

			$_SESSION['username'] = $row['username'];
			$_SESSION['access'] = $row['access'];
			$_SESSION['nickname'] = $row2['nickname'];

			ob_end_clean();

			header ("Location: http://" . $_SERVER['HTTP_HOST'] .
				dirname($SERVER['PHP_SELF']) . "/");
			exit();
		}
		else {
			$message = $message . '<p class="error">Username or password incorrect.</p>' . "\n";
		}
	}
	else {
		$message = $message . '<p class="error">Please try again.</p>' . "\n";
	}
}


include("include/header.php");

echo $message;


echo '<p class="title">Login:</p>' ."\n";

?>
<form action="<? echo basename($PHP_SELF) ?>" method="post" name="form" id="form">
<table>
  <tr>
    <td>Username:</td>
    <td><input name="username" type="text" maxlength="10" size="10" value="<? if (isset($_POST['username'])) echo $_POST['username']; ?>" /></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input name="password" type="password" maxlength="10" size="10" value="<? if (isset($_POST['password'])) echo $_POST['password']; ?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><a href="forget.php">Forget Your Password?</a></td>
  </tr>
  <tr>
    <td colspan="2"><input name="submit" type="submit" value="Login" /></td>
  </tr>
</table>
</form>
<p>[ <a href="/">Back</a> ]</p>
<?
include("include/footer.php");
?>
