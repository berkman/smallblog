<? 

include("include/include.php");
$title = 'Forgotten Password';


include("include/header.php");


$message = "";

if (isset($_POST['reset']))
{
	$user = $_POST['username'];
	$email = $_POST['email'];

	$query = "SELECT email, nickname FROM user WHERE username='$user'";
	$r = pg_query($query) or die ("Error with query.");
	$row = pg_fetch_array($r);

	if ($email == $row['email']) {
		if ($row['nickname'] != "")
			$u = $row['nickname'];

		$p = "";
		for($i = 0; $i < 6; $i++)
			$p = $p . rand(1,9);

		$query2 = "UPDATE login SET password=SHA1($p) WHERE username='$user'";
		$r2 = pg_query($query2) or die ("Error with query.");

		$to = $row['email'];
		$subject = "Your bong-it.com password";
		$body = $user . ", you're a dumbass.  The server has reset your password.  Your new password is: " .$p. "\n\nThanks,\nbong-it.com admin";
		if (mail('berkman@bong-it.com', $subject, $body, 'From: admin@bong-it.com'))
		   echo '<p class="success">'.$user.', your password has been reset.  You will receive an e-mail containing your new password.</p>' . "\n";
		else
			echo '<p class="error">Error sending reset email to user.</p>' . "\n";
	}
	else {
		echo '<p class="error">Invalid username or email.</p>' ."\n";
	}
}


echo '<p class="title">Forgotten Password:</p>' . "\n";

?>

<form action="<? echo basename($PHP_SELF) ?>" method="post" name="form" id="form">
<table>
  <tr>
    <td>Username:</td>
    <td><input name="username" type="text" maxlength="10" size="10" value="<? if (isset($_POST['username'])) echo $_POST['username']; ?>" /></td>
  </tr>
  <tr>
    <td>E-mail:</td>
    <td><input name="email" type="email" maxlength="30" size="10" value="<? if (isset($_POST['email'])) echo $_POST['password']; ?>" /></td>
  </tr>
  <tr>
    <td colspan="2"><input name="reset" type="submit" value="Reset" /></td>
  </tr>
</table>
</form>
<p>[ <a href="/">Back</a> ]</p>
<?
include("include/footer.php");
?>
