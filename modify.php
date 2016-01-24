<?

include("include/include.php");
$title = 'Modify News';

session_start();

$a = $_SESSION['access'];
$u = $_SESSION['username'];

if ($u == "") {
	header ("Location: http://" . $_SERVER['HTTP_HOST'] .
				dirname($SERVER['PHP_SELF']) . "/");
	exit();
}




if ($a != "admin")
	$query = "SELECT newsID, username, subject, body, DATE_FORMAT(posted, '%W, %M %d, %Y') FROM news WHERE username='$u' ORDER BY newsID";
else
	$query = "SELECT newsID, username, subject, body, DATE_FORMAT(posted, '%W, %M %d, %Y') FROM news ORDER BY newsID";


$r = @mysql_query($query) or die ("Error with query.");
$num_rows = mysql_num_rows($r);




include("include/header.php");




if (isset($_POST['submit'])) {
	$r2 = @mysql_query($query) or die ("Error with query.");
	$num_rows2 = mysql_num_rows($r2);

	for ($i = 0; $i < $num_rows2; $i++)	{

		$row2 = mysql_fetch_array($r2);

		$check = $row2['newsID'] . "-keeprem";
		if ($_POST[$check] == "r") {
			$query3 = "DELETE FROM news WHERE newsID='".$row2['newsID']."'";
			$r3 = mysql_query($query3) or die ("Error with delete.");
			echo '<p class="success">Deleted from News.</p>' . "\n";

			$query3 = "SELECT newsID FROM comments WHERE newsID='".$row2['newsID']."'";
			$r3 = mysql_query($query3) or die ("Error with delete.");
			$row3 = mysql_fetch_array($r3);

			if ($row2) {
				$query3 = "DELETE FROM comments WHERE newsID='".$row2['newsID']."'";
				$r3 = mysql_query($query3) or die ("Error with delete.");
				echo '<p class="success">Deleted from Comments.</p>' . "\n";
			}
		}
		else if($_POST[$check] == "k") {
		    $n = $row2['newsID'] . "-newsID";
			$n = $_POST[$n];

			// Update modified subject
			$s = $row2['newsID'] . '-sub';
			$ns = $_POST[$s];
			if ($ns != $row2['subject']) {
				$query3 = "UPDATE news SET subject='$ns' WHERE newsID='$n'";
				$r3 = mysql_query($query3) or die ("Error with query.");
			}

			// Update modified body
			$b = $row2['newsID'] . '-body';
			$nb = $_POST[$b];
			if (stripslashes($nb) != $row2['body']) {
				$query3 = "UPDATE news SET body='$nb' WHERE newsID='$n'";
				$r3 = mysql_query($query3) or die ("Error with query.");
			}

			if ( (stripslashes($nb) != $row2['body']) || ($ns != $row2['subject']) ) {
				echo '<p class="success">News #" .$n. " changed.</p>' . "\n";
			}
		}
	}
}


$r = @mysql_query($query) or die ("Error with query.");
$num_rows = mysql_num_rows($r);




echo '<p class="title">Modify News</p>' . "\n";

?>
<form action="<? echo basename($PHP_SELF) ?>" method="post">
<?

for ($i = 0; $i < $num_rows; $i++) {
	$row = mysql_fetch_array($r);
	$date = $row[4];

	echo '<table>' . "\n";
	echo '  <tr>' . "\n";
	echo '    <td>Subject:</td>' . "\n";
	echo '    <td><input name="'.$row['newsID'].'-sub" type="text" maxlength="50" value="' .$row['subject']. '"></td>' . "\n";
	echo '  </tr>' . "\n";
	echo '  <tr><td colspan="2">Posted ' . $row[5] . ' by ' . $row['username'] . '</td></tr>' . "\n";
	echo '  <tr>' . "\n";
	echo '    <td colspan="2"><textarea name="'.$row['newsID'].'-body" cols="50" rows="10">' .$row['body']. '</textarea></td>' . "\n";
	echo '  </tr>' . "\n";

	echo '  <tr>' . "\n";
	echo '    <td>Keep <input name="' .$row['newsID']. '-keeprem" type="radio" value="k" checked="checked" /></td>' . "\n";
	echo '    <td>Remove <input name="' .$row['newsID']. '-keeprem" type="radio" value="r" /></td>' . "\n";
	echo '  </tr>' . "\n";
	echo '</table>' . "\n";
	echo '<input name="'.$row['newsID'].'-newsID" type="hidden" value="' .$row['newsID']. '">' . "\n";
	echo "<br /><br />\n";
}

?>
  <input name="submit" type="submit" value="Finish" />
</form>
<p>[ <a href="options.php">Back</a> ]</p>
<?
include("include/footer.php");
?>
