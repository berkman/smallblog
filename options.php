<?

include("include/include.php");
$title = 'Options';

session_start();
$u = $_SESSION['username'];

include("include/header.php");
echo '<p class="title">Options</p>' . "\n";


if (isset($_POST['reset'])) {
	$user = $_GET['u'];

	$query = "SELECT email, nickname FROM public.user WHERE username='$user'";
	//$r = pg_query($query) or die ("Error with query.");
	//$row = pg_fetch_array($r);
	$r = pg_query($query) or die ("Error with query.");
	$row = pg_fetch_array($r);

	if ($row['nickname'] != "") {
		$u = $row['nickname'];
	}

	$p = "";
	for($i = 0; $i < 6; $i++) {
		$p = $p . rand(1,9);
	}

	$query2 = "UPDATE public.login SET password=SHA1($p) WHERE username='$user'";
	//$r2 = pg_query($query2) or die ("Error with query.");
	$r2 = pg_query($query2) or die ("Error with query.");

	$to = $row['email'];
	$subject = "Your password";
	$body = $user . ", The server has reset your password.  Your new password is: " .$p. "\n\nThanks,\nadmin";
	if (mail('berkman@bong-it.com', $subject, $body, 'From: admin@bong-it.com'))
	   echo '<p class="success">'.$user.' reset, message sent.</p>' . "\n";
	else
 		echo '<p class="error">Error sending reset email to user.</p>' . "\n";
}

if (isset($_POST['delete'])) {
	$user = $_GET['u'];

	$query = "DELETE FROM public.comments WHERE username='$user'";
	$c = pg_query($query) or die ("Error with comments table.");
	$query = "DELETE FROM public.login WHERE username='$user'";
	$l = pg_query($query) or die ("Error with login table.");
	$query = "DELETE FROM public.news WHERE username='$user'";
	$n = pg_query($query) or die ("Error with news table.");
	$query = "DELETE FROM public.pref WHERE username='$user'";
	$p = pg_query($query) or die ("Error with pref table.");
	$query = "DELETE FROM public.user WHERE username='$user'";
	$r = pg_query($query) or die ("Error with user table.");

	if ($c && $l && $n && $p && $r)
		echo '<p class="success">'.$user.' successfully deleted from database.</p>' . "\n";
	else
		echo '<p class="error">Error deleting user, contact the <a href="mailto:admin@bong-it.com">system admin</a>.</p>' . "\n";
}


if (isset($_POST['icon'])) {
	$d = $_POST['deleteIcon'];

	if ($d == 'y') {
		$query2 = "UPDATE public.user SET picture='' WHERE username='$u'";
		$r2 = pg_query($query2) or die ("uh oh");

		echo '<p class="success">Icon Deleted.</p>' . "\n";
	}
	else {
		if (is_uploaded_file ($_FILES['image']['tmp_name'])) {
			$i = $_FILES['image']['name'];

			$e = pathinfo($i);
			$e = $e[extension];

			if (move_uploaded_file ($_FILES['image']['tmp_name'],"./uploads/".$username.".".$e.""))	{
				$query2 = "UPDATE public.user SET picture='".$username.".".$e."' WHERE username='$u'";
				$r2 = pg_query($query2) or die ("uh oh");

				echo '<p class="success">Icon Uploaded.</p>' . "\n";
			}
			else {
				echo '<p class="error">Error uploading icon</p>' . "\n";
				$i = '';
			}

		}
		else
			$i = '';
	}
}

if (isset($_POST['pass'])) {
	$oldpass = $_POST['oldpass'];
	$oldpass2 = $_POST['oldpass2'];
	$newpass = $_POST['newpass'];
	$length = strlen($newpass);

	$query = "SELECT password FROM public.login WHERE username='$u'";
	$r = pg_query($query) or die ("Error with query.");

	$row = pg_fetch_array($r);

	if ($oldpass != $oldpass2)
		echo '<p class="error">Your old password could not be confirmed.</p>' . "\n";
	//else if ($row['password'] == sha1($oldpass)) {
	else if (true) {
		if ($length < 6)
			echo '<p class="error">New password must be at least 6 characters.</p>' . "\n";
		else {
			echo '<p class="success">Password successfully changed.</p>' . "\n";

			$query = "UPDATE public.login SET password=sha1('" .$newpass. "') WHERE username='$u'";
			$r = pg_query($query) or die ("Error with query.");
		}
	}
	else
		echo '<p class="error">Incorrect password.  Please try again.</p>' . "\n";
}



if (isset($_POST['user'])) {
	$f = $_POST['fullname'];
	$cf = $_POST['change_fullname'];
	$n = $_POST['nickname'];
	$cn = $_POST['change_nickname'];
	$e = $_POST['email'];
	$ce = $_POST['change_email'];
	$s = $_POST['screenname'];
	$cs = $_POST['change_screenname'];
	$w = $_POST['website'];
	$cw = $_POST['change_website'];

	if ($cf == 'y')	{
		$query = "UPDATE public.user SET fullname='$f' WHERE username='$u'";
		$r = pg_query($query) or die ("Error with query.");
		echo '<p class="success">Fullname changed.</p>' . "\n";
	}
	if ($cn == "y")	{
		$query = "UPDATE public.user SET nickname='$n' WHERE username='$u'";
		$r = pg_query($query) or die ("Error with query.");

		$query = "SELECT post_name FROM public.pref WHERE username='$u'";
		$r = pg_query($query);
		$pn = pg_fetch_row($r);

		if ($n == '' && $pn[0] == 'nickname') {
			$query = "UPDATE public.pref SET post_name='username' WHERE username='$u'";
			$r = pg_query($query) or die ("Error with query.");
		}

		echo '<p class="success">Nickname changed.</p>' . "\n";
		$_SESSION['nickname'] = $n;

	}
	if ($ce == "y")	{
		$query = "UPDATE public.user SET email='$e' WHERE username='$u'";
		$r = pg_query($query) or die ("Error with query.");
		echo '<p class="success">E-mail changed.</p>' . "\n";
	}
	if ($cs == "y")	{
		$query = "UPDATE public.user SET screenname='$s' WHERE username='$u'";
		$r = pg_query($query) or die ("Error with query.");
		echo '<p class="success">Screenname changed.</p>' . "\n";
	}
	if ($cw == "y") {
		$query = "UPDATE public.user SET website='$w' WHERE username='$u'";
		$r = pg_query($query) or die ("Error with query.");
		echo '<p class="success">Website changed.</p>' . "\n";
	}
}



if (isset($_POST['pref'])) {
	$pn = $_POST['postname'];

	$query = "SELECT nickname FROM public.user WHERE username='$u'";
	$nn = pg_query($query);
	$n = pg_fetch_row($nn);

	if (($pn == "nickname" && $n[0] != "") || $pn == "username") {
		$query = "UPDATE public.pref SET post_name='$pn' WHERE username='$u'";
		$r = pg_query($query) or die ("Error with query.");
		echo '<p class="success">Preferences changed.</p>' . "\n";
	}
	else {
		echo '<p class="error">You must have a nickname entered to display it.</p>' . "\n";
	}
}



if (isset($_POST['newmotd'])) {
	$motd = $_POST['motd'];

	if ($motd != '') {
		$query = "UPDATE public.options SET motd='$motd'";
		$r = @pg_query($query);

		echo '<p class="success">Message of the Day changed.</p>' . "\n";
	}
}




$query = "SELECT * FROM public.user WHERE username='$u'";
$r = pg_query($query) or die ("Error with query.");
$row = pg_fetch_array($r);

$query3 = "SELECT * FROM public.pref WHERE username='$u'";
$r3 = pg_query($query3) or die ("Error with query.");
$postn = pg_fetch_array($r3);

//$query9 = "SELECT motd FROM options";
//$r9 = pg_query($query9) or die ("Error with query.");
//$res = pg_fetch_array($r9);
//$motd = $res['motd'];


echo "<p>What's up ";
if($_SESSION['nickname'] != "")
	echo $_SESSION['nickname'];
else
	echo $u;

if($_SESSION['access'] == "admin")
	echo ', you have ' .$_SESSION['access']. ' privledges.</p>' . "\n";
else
	echo '.</p>' . "\n";


if ($_SESSION['access'] == "admin")
{
    echo '<table>' . "\n";
	echo '<tr><td colspan="3"><b>Admin Options:</b></td></tr>' . "\n";
	echo '<tr><td>[ <a href="newuser.php">Create User</a> ]</td><td colspan="2">&nbsp;</td></tr>' . "\n";

	$query2 = "SELECT username FROM login WHERE access!='admin' ORDER BY username";
	$r2 = pg_query($query2) or die ("Error with query.");
	$num_rows = pg_num_rows($r2);

	if ($num_rows > 1)
	{
		echo '<form action="' .basename($PHP_SELF). '" method="post" name="resetUser" onsubmit="return rUser();">' . "\n";
		echo '<tr>' . "\n";
		echo '  <td>[ Reset User ]</td>' . "\n";
		echo '  <td><select name="user">' . "\n";
		echo '    <option selected="selected" value=""></option>' . "\n";

		for ($i = 0; $i < $num_rows; $i++)
		{
			$row2 = pg_fetch_array($r2);
			if ($row2['username'] != $u)
				echo '    <option value="' .$row2['username']. '">' .$row2['username']. '</option>' . "\n";
		}

		echo '  </select></td>' . "\n";
		echo '  <td><input name="reset" type="submit" value="Reset"></td>' . "\n";
		echo '</tr>' . "\n";
		echo '</form>' . "\n";

		echo '<form action="' .basename($PHP_SELF). '" method="post" name="deleteUser" onsubmit="return dUser();">' . "\n";
		echo '<tr>' . "\n";
		echo '  <td>[ Delete User ]</td>' . "\n";
		echo '  <td><select name="user">' . "\n";
		echo '    <option selected="selected" value=""></option>' . "\n";

		$query2 = "SELECT username FROM login WHERE access!='admin' ORDER BY username";
		$r2 = pg_query($query2) or die ("Error with query.");

		for ($i = 0; $i < $num_rows; $i++)
		{
			$row2 = pg_fetch_array($r2);
			if ($row2['username'] != $u)
				echo '    <option value="' .$row2['username']. '">' .$row2['username']. '</option>' . "\n";
		}

		echo '  </select></td>' . "\n";
		echo '  <td><input name="delete" type="submit" value="Delete"></td>' . "\n";
		echo '</tr>' . "\n";
		echo '</form>' . "\n";
	}

	echo '<tr><td>[ <a href="modify.php">Modify News</a> ]</td><td colspan="2">&nbsp;</td></tr>' . "\n";

	echo '<tr><form action="' .basename($PHP_SELF). '" method="post" name="motd">' . "\n";
	echo '  <td>MOTD:</td>' . "\n";
	echo '  <td><input name="motd" type="text" value="' .$motd. '" maxlength="50"></td>' . "\n";
	echo '  <td><input name="newmotd" type="submit" value="Change"></td>' . "\n";
	echo '</form></tr>' . "\n";

	echo '</table>' . "\n";
	echo '<br />' . "\n";
}


$img = "SELECT picture FROM public.user WHERE username='$u'";
$imgr = pg_query($img);
$imgrow = pg_fetch_array($imgr);

?>
<form action="<? echo basename($PHP_SELF) ?>" method="post" name="icon" enctype="multipart/form-data">
<table>
  <tr><td><b>Post Icon:</b></td></tr>
<?
if ($imgrow[0] != "") {
	echo '<tr><td><img src="uploads/' .$imgrow['picture']. '" /></td></tr>' . "\n";
	echo '<tr><td>Delete Icon: <input name="deleteIcon" type="checkbox" value="y"></td></tr>' . "\n";
}
else {
	echo '<tr><td>You have no post icon uploaded.</td></tr>' . "\n";
}

echo '<tr><td><input type="file" name="image" /></td></tr>' . "\n";
echo '<tr><td><input name="icon" type="submit" value="Submit" /></td></tr>' . "\n";
?>
</table>
</form>
<form action="<? echo basename($PHP_SELF) ?>" method="post" name="pass">
<table>
  <tr>
    <td colspan="2"><b>Change Password:</b></td>
  </tr>
  <tr>
    <td>Old Password:</td>
    <td><input name="oldpass" type="password" maxlength="10"></td>
  </tr>
  <tr>
    <td>Confirm:</td>
    <td><input name="oldpass2" type="password" maxlength="10"></td>
  </tr>
  <tr>
    <td>New Password:</td>
    <td><input name="newpass" type="password" maxlength="10"></td>
  </tr>
  <tr>
    <td colspan="2"><input name="pass" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
<form action="<? echo basename($PHP_SELF) ?>" method="post" name="user">
<table>
  <tr>
    <td colspan="3"><b>User Info:</b></td>
  </tr>
  <tr>
    <td><b>Field</b></td>
    <td><b>Value</b></td>
	<td><b>Change?</b></td>
  </tr>
  <tr>
    <td>Full Name:</td>
    <td><input name="fullname" type="text" maxlength="40" value="<? echo $row['fullname'] ?>"></td>
	<td><input name="change_fullname" type="checkbox" value="y"></td>
  </tr>
  <tr>
    <td>Nickname:</td>
    <td><input name="nickname" type="text" maxlength="20" value="<? echo $row['nickname'] ?>"></td>
	<td><input name="change_nickname" type="checkbox" value="y"></td>
  </tr>
  <tr>
    <td>E-mail:</td>
    <td><input name="email" type="text" maxlength="50" value="<? echo $row['email'] ?>"></td>
	<td><input name="change_email" type="checkbox" value="y"></td>
  </tr>
  <tr>
    <td>Screenname:</td>
    <td><input name="screenname" type="text" maxlength="25" value="<? echo $row['screenname'] ?>"></td>
	<td><input name="change_screenname" type="checkbox" value="y"></td>
  </tr>
  <tr>
    <td>Website:</td>
    <td><input name="website" type="text" maxlength="100" value="<? echo $row['website'] ?>"></td>
	<td><input name="change_website" type="checkbox" value="y"></td>
  </tr>
  <tr>
    <td colspan="3"><input name="user" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
<form action="<? echo basename($PHP_SELF) ?>" method="post" name="pref">
<table>
  <tr>
    <td colspan="2"><b>User Prefs:</b></td>
  </tr>
  <tr>
    <td><b>Field</b></td>
    <td><b>Value</b></td>
  </tr>
  <tr>
    <td>Post Name:</td>
    <td><select name="postname">
	<?
		if ($postn['post_name'] == 'username') {
			echo '<option selected="selected" value="username">username</option>' . "\n";
			echo '      <option value="nickname">nickname</option>' . "\n";
		}
		else {
			echo '      <option selected="selected" value="nickname">nickname</option>' . "\n";
			echo '      <option value="username">username</option>' . "\n";
		}
	?>
    </select></td>
  </tr>
  <tr>
    <td colspan="2"><input name="pref" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
<p>[ <a href="/">Back</a> ]</p>
<?
include("include/footer.php");
?>
