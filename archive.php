<?

include("include/include.php");
$title = 'Archive';

include("include/header.php");



// Determine how many news items to display on the main page.
$query2 = "SELECT show_num FROM options";
$r2 = pg_query($query2);
$row = pg_fetch_array($r2);
$show_main = $row[0];



$display = 5;

// Determine how many pages there are.
if (isset($_GET['np']))
	$num_pages = $_GET['np'];
else
{
	$query = "SELECT newsID, subject, username, body, DATE_FORMAT(posted, '%W, %M %d, %Y'), comment_fl, image FROM news ORDER BY newsID DESC";
	$query_result = pg_query ($query);
	$num_records = @pg_num_rows ($query_result);

	if ($num_records > $display)
		$num_pages = ceil ($num_records/$display);
	else
		$num_pages = 1;
}

// Determine where in the database to start returning results.
if (isset($_GET['s']))
	$start = $_GET['s'];
else
	$start = 0;  // = 0


$query = "SELECT newsID, subject, username, body, DATE_FORMAT(posted, '%W, %M %d, %Y'), comment_fl, image FROM news ORDER BY newsID DESC LIMIT $start, $display";
$result = @pg_query ($query);
$num = pg_num_rows ($result);

if ($num > 0)
{
	echo '<p class="title">Archive</p>' . "\n";

	if ($num_pages > 1)
	{
		echo '<p>[ ';
		$current_page = ($start / $display) + 1;

		if ($current_page != 1)
			echo '<a href="archive.php?s=' .($start - $display). '&np=' .$num_pages. '">Previous</a> | ';

		for ($i = 1; $i <= $num_pages; $i++)
		{
			if ($i != 1)
				$line = ' | ';
			else
				$line = ' ';

			if ($i != $current_page)
				echo $line . '<a href="archive.php?s=' .(($display * ($i - 1))). '&np='. $num_pages. '">' .$i. '</a>';
			else
				echo $line . $i . ' ';
		}

		if ($current_page != $num_pages)
			echo ' | <a href="archive.php?s=' .($start + $display). '&np=' .$num_pages. '">Next</a>';

		echo ' ]</p>' . "\n";
	}


	while ($row = pg_fetch_array($result))
	{
		$date = $row[4];


		// Get the users nickname and e-mail.
		$query3 = "SELECT nickname, email, picture FROM user WHERE username='" . $row['username'] . "'";
		$r3 = pg_query($query3, $link) or die ("Error with query.");
		$e = pg_fetch_array($r3);



		// Determine the users preference for their post name.
		$query3 = "SELECT post_name FROM pref WHERE username='" . $row['username'] . "'";
		$r3 = pg_query($query3);
		$p = pg_fetch_array($r3);


		echo '<table>' . "\n";


		// Print the user's icon, if they have one.
		if($e['picture'] != '')
			echo '  <tr><td class="subj"><img src="uploads/' .$e['picture']. '" /> ' . $row['subject'] . '</td></tr>' . "\n";
		else
			echo '  <tr><td class="subj">' . $row['subject'] . '</td></tr>' . "\n";


		// Print the information line for the post based on user's preference.
		if ($p[0] == 'username')
			if ($e[1] != "")
				echo '  <tr><td>Posted ' .$date. ' by <a href="mailto:' .$e[1]. '">' .$row['username']. '</a></td></tr>' . "\n";
			else
				echo '  <tr><td>Posted ' .$date. ' by ' .$row['username']. '</td></tr>' . "\n";
		else
			if ($e[1] != "")
				echo '  <tr><td>Posted ' .$date. ' by <a href="mailto:' .$e[1]. '">' .$e[0]. '</a></td></tr>' . "\n";
			else
				echo '  <tr><td>Posted ' .$date. ' by ' .$e[0]. '</td></tr>' . "\n";


		// Print the post's body.
		echo '  <tr><td>' . "\n";
		echo '    ' . $row['body'] . "\n";
		echo '  </td></tr>' . "\n";


		// Print the post's image, if there is one.
		if ($row['image'] != "")
		{
			echo '  <tr><td>&nbsp;</td></tr>' . "\n";
			echo '  <tr><td><img src="uploads/' .$row['image']. '" /></td></tr>' . "\n";
			echo '  <tr><td>&nbsp;</td></tr>' . "\n";
		}


		// Print the comments link, if there are comments.
		if ($row['comment_fl'] == 'y')
		{
			echo '  <tr><td colspan="3"><a href="comments.php?newsID=' . $row['newsID'] . '">comments</a></td></tr>' . "\n";
		}

		echo '</table>' . "\n";
		echo '<br />' . "\n";
	}


	pg_free_result ($result);

}
echo '<p>[ <a href="/">Back</a> ]</p>' . "\n";

include("include/footer.php");

?>
