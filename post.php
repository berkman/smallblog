<?
include("include/include.php");
$title = 'Post News';

session_start();
$u = $_SESSION['username'];

include("include/header.php");

$s = $_POST['subject'];
$b = $_POST['body'];
$c = $_POST['comment_fl'];

/*
if (isset($_POST['submit'])) {
	if (is_uploaded_file ($_FILES['image']['tmp_name'])) {
		if (move_uploaded_file ($_FILES['image']['tmp_name'],"./news/{$_FILES['image']['name']}"))
			echo '<p class="success">Success.</p>';
		else {
			echo '<p class="error">Error</p>';
			$i = '';
		}
		$i = $_FILES['image']['name'];
	}
	else {
		$i = '';
	}

	if ($s != '' && $b != '') {
		$query = "INSERT INTO news (username, subject, body, comment_fl, posted, image) VALUES ('$u', '$s', '$b', '$c', CURRENT_TIMESTAMP(), '$i')";
		$r = mysql_query($query);

		echo '<p class="success">News Posted.</p>';
	}
	else
		echo '<p class="error">Please enter a subject and a body.</p>' . "\n";
}
*/

echo '<p class="title">'.$title.'</p>' . "\n";

?>
<form action="<? echo basename($PHP_SELF) ?>" method="post" name="form" enctype="multipart/form-data">
<table>
  <tr>
    <td>Subject:</td>
    <td><input name="subject" type="text" maxlength="50"></td>
  </tr>
  <tr>
    <td valign="top">Body:</td>
    <td><textarea name="body" cols="50" rows="10"></textarea></td>
  </tr>
  <tr>
    <td>Comments:</td>
    <td>
	  <select name="comment_fl">
          <option selected="selected" value="n">No</option>
          <option value="y">Yes</option>
      </select>
	</td>
  </tr>
  <tr>
    <td>Image:</td>
    <td><input type="file" name="image" /></td>
  </tr>
  <tr>
    <td colspan="2"><input name="submit" type="submit" value="Submit"></td>
  </tr>
</table>
</form>
<p>[ <a href="/">Back</a> ]</p>
<?
include("include/footer.php");
?>
