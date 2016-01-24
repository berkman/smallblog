<?php

include("include/include.php");


// Register the session variables.
//session_start();
//session_register("username");
//session_register("nickname");

//$u = $_SESSION['username'];
//$n = $_SESSION['nickname'];
//$motd = '';


// Get the news from the database.
//$query = "SELECT newsID, subject, username, body, DATE_FORMAT(posted, '%W, %M %d, %Y'), comment_fl, image FROM news ORDER BY newsID DESC";
//$r = mysql_query($query) or die ("Error getting news.");
//$num_rows = mysql_num_rows($r);


// Determine how many news items to display on the main page.
//$query2 = "SELECT show_num FROM options";
//$r2 = mysql_query($query2) or die ("Error getting number of news items.");
//$row = mysql_fetch_array($r2);
//$show_num = $row[0];

//if ($num_rows < $show_num) {
//	$show = $num_rows;
//	$archive_link = false;
//}
//else {
//	$show = $show_num;
//	$archive_link = true;
//}

/*
// Determine who else is logged into the website.
$query4 = "SELECT username FROM login WHERE loggedin='y'";
$r4 = mysql_query($query4) or die ("Error with query4.");
$row4 = mysql_fetch_array($r4);
$logged = mysql_num_rows($r4);
 */


// Print the page header.
include ('include/header.php');


/*
// Check for user's birthday.
$bday = "SELECT username, nickname FROM user WHERE DATE_FORMAT(dob, '%b %e') = DATE_FORMAT(NOW(), '%b %e')";
$bresult = @mysql_query($bday);
$todaybday = mysql_fetch_array($bresult);

if ($todaybday) {
	$motd = 'Happy Birthday ' .$todaybday['nickname']. '!';
}


// Message of the day.
if (!$todaybday) {
	$query5 = "SELECT motd FROM options";
	$mess = @mysql_query($query5);
	$temp = mysql_fetch_array($mess);

	if ($temp) {
		$motd = $temp['motd'];
	}
}


if ($motd != '') {
	echo '<p class="motd">[ '.$motd.' ]</p>';
}


// Logged in user.
if ($u != "") {
	// Retrieve the date from the last users login.
	$query3 = "SELECT DATE_FORMAT(last_login, '%W, %M %d, %Y at %h:%i %p') FROM login WHERE username='$u'";
	$r3 = @mysql_query ($query3);
	$row3 = mysql_fetch_array($r3);


	// Greet the user.
	if ($n != "")
		echo "<p>Welcome Back, " .$n. ".</p>\n";
	else
		echo "<p>Welcome Back, " .$u. ".</p>\n";
	if ($row3[0] == "")
		echo "<p>This is your first login.</p>\n";
	else
		echo "<p>You last logged in: [ " .$row3[0]. " ].<br />\n";


	// Display the other logged in users.
	if ($logged == 1)
		echo "<p>You're the only user logged in.</p>\n";
	else {
		echo "Logged in users:";
		for ($i = 0; $i < $logged; $i++) {
			if ($row4[0] != $u)
				echo " [ " .$row4[0]. " ] ";
			$row4 = mysql_fetch_array($r4);
		}
		echo "</p>\n";
	}


	// Display the menu.
	echo '<p>' . "\n";
	echo '  [ <a href="post.php">Post</a> ]<br />' . "\n";
	echo '  [ <a href="options.php">Options</a> ]<br />' . "\n";
	echo '  [ <a href="logout.php">Log Out</a> ]' . "\n";
	echo '</p>' . "\n";
}


// No User logged in.
else {
	echo "<p>Welcome, Visitor.</p>\n";
	echo '<p>[ <a href="login.php">Log In</a> ]</p>' . "\n";
}


// Display the news.
for ($i = 0; $i < $show; $i++) {
	$row = mysql_fetch_array($r);
	$date = $row[4];


	// Get the users nickname and e-mail.
	$query3 = "SELECT nickname, email, picture FROM user WHERE username='" . $row['username'] . "'";
	$r3 = mysql_query($query3, $link) or die ("Error with query.");
	$e = mysql_fetch_array($r3);


	// Determine the users preference for their post name.
	$query3 = "SELECT post_name FROM pref WHERE username='" . $row['username'] . "'";
	$r3 = mysql_query($query3);
	$p = mysql_fetch_array($r3);


	echo '<table>' . "\n";


	// Print the user's icon, if they have one.
	if($e['picture'] != '')
		echo '  <tr><td class="subj"><img src="uploads/' .$e['picture']. '" alt="'.$row['username'].'\'s icon" border="1" /> ' . $row['subject'] . '</td></tr>' . "\n";
	else
		echo '  <tr><td class="subj">' . $row['subject'] . '</td></tr>' . "\n";


	// Print the information line for the post based on user's preference.
  	if ($p[0] == 'username') {
		if ($e[1] != "") {
			echo '  <tr><td>Posted ' .$date. ' by <a href="mailto:' .$e[1]. '">' .$row['username']. '</a></td></tr>' . "\n";
		}
		else {
			echo '  <tr><td>Posted ' .$date. ' by ' .$row['username']. '</td></tr>' . "\n";
		}
	}
	else {
		if ($e[1] != "") {
			echo '  <tr><td>Posted ' .$date. ' by <a href="mailto:' .$e[1]. '">' .$e[0]. '</a></td></tr>' . "\n";
		}
		else {
			echo '  <tr><td>Posted ' .$date. ' by ' .$e[0]. '</td></tr>' . "\n";
		}
	}

	// Print the post's body.
	echo '  <tr><td>' . "\n";
	echo '    ' . $row['body'] . "\n";
	echo '  </td></tr>' . "\n";


	// Print the post's image, if there is one.
	if ($row['image'] != "") {
		echo '  <tr><td>&nbsp;</td></tr>' . "\n";
		echo '  <tr><td><img src="uploads/' .$row['image']. '" /></td></tr>' . "\n";
		echo '  <tr><td>&nbsp;</td></tr>' . "\n";
	}


	// Print the comments link, if there are comments.
	if ($row['comment_fl'] == 'y') {
		$query_cmnt = "SELECT cmntID FROM comments WHERE newsID='".$row['newsID']."'";
		$cmnt_rslt = mysql_query($query_cmnt) or die ("Error with query.");
		$num_cmnts = mysql_num_rows($cmnt_rslt);
		echo '  <tr><td colspan="3"><a href="comments.php?newsID=' . $row['newsID'] . '">Comments ('.$num_cmnts.')</a></td></tr>' . "\n";
	}

	echo '</table>' . "\n";
	echo '<br />' . "\n";
}


// Print the archives link, if needed.
if ($archive_link)
	echo '<p>[ <a href="archive.php?s=' . $show_num . '">Archive</a> ]</p>' . "\n";

 */
?>
<table cellpadding="4" cellspacing="4">
  <tr>
    <td><img src="images/valid_xhtml.gif" /></td>
		<td><img src="images/php-power-white.gif" /></td>
		<td><img src="images/mysql.png" /></td>
	</tr>
</table>
<?php

// Print the page footer.
include ('include/footer.php');

?>
