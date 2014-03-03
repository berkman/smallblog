<? 

include("include/include.php");
$title = 'Create User';


include("include/header.php");

if (isset($_POST['submit'])) {
	$u = $_POST['username'];
	$p = $_POST['password'];
	$a = $_POST['access'];
	$e = $_POST['email'];
	
	$query = "INSERT INTO login (username, password, access, signup) VALUES ('$u', sha1('$p'), '$a', CURRENT_TIMESTAMP())";
	$r = mysql_query($query) or die ("Error with query.");
	
	$query = "INSERT INTO user (username, email) VALUES ('$u', '$e')";
	$r = mysql_query($query) or die ("Error with query.");
	
	$query = "INSERT INTO pref (username, post_name) VALUES ('$u', 'username')";
	$r = mysql_query($query) or die ("Error with query.");

	echo '<p class="success">User successfully created.</p>' . "\n";
}


echo '<p class="title">Create User:</p>' . "\n";

?>
<form action="newuser.php" method="post">
<table>
  <tr>
    <td>Username:</td>
    <td><input name="username" type="text" maxlength="10"></td>
  </tr>
  <tr>
    <td>Password:</td>
    <td><input name="password" type="password" maxlength="10"></td>
  </tr>
  <tr>
    <td>E-mail:</td>
    <td><input name="email" type="text" maxlength="30"></td>
  </tr>
  <tr>
    <td>Access:</td>
    <td>
      <select name="access">
        <option selected="selected" value="normal">normal</option>
        <option value="admin">admin</option>
      </select>
    </td>
  </tr>
  <tr>
    <td colspan="2"><input name="submit" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
<p>[ <a href="/options.php">Back</a> ]</p>
<?
include("include/footer.php");
?>