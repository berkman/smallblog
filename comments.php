<? 

include("include/include.php");
$title = 'Comments';


session_start();
session_register("username");


$logged = false;


$n = $_GET['newsID'];
if ($n == "") {
	$n = $_POST['n'];
}

$u = $_SESSION['username'];

if ($n != "" && $u != "") {
	$logged = true;
}

include("include/header.php");
echo '<p class="title">'.$title.'</p>' . "\n";


$query = "SELECT subject, username, body, DATE_FORMAT(posted, '%W, %M %d, %Y') FROM news WHERE newsID='$n'";
$r = mysql_query($query);
$row = mysql_fetch_array($r);


if (isset($_POST['post'])) {
	$s = $_POST['subject'];
	$c = $_POST['comment'];


	$query = "INSERT INTO comments (newsID, username, subject, comment, cmnt_stmp) VALUES ('$n', '$u', '$s', '$c', NOW())";
	$r = mysql_query($query);
	
	echo '<p class="success">Comment posted.</p>' . "<br />";
}

  
$date = $row[4];


// Get the users nickname and e-mail.
$query3 = "SELECT nickname, email, picture FROM user WHERE username='" . $row['username'] . "'";
$r3 = mysql_query($query3) or die ("Error with query.");
$e = mysql_fetch_array($r3);
  
  
// Determine the users preference for their post name.
$query3 = "SELECT post_name FROM pref WHERE username='" . $row['username'] . "'";
$r3 = mysql_query($query3);
$p = mysql_fetch_array($r3);
    
  
echo '<table>' . "\n";
 
  
// Print the user's icon, if they have one.
if($e['picture'] != '') {
	echo '  <tr><td class="subj"><img src="uploads/' .$e['picture']. '" alt="'.$row['username'].'\'s icon" /> ' . $row['subject'] . '</td></tr>' . "\n";
}
else {
	echo '  <tr><td class="subj">' . $row['subject'] . '</td></tr>' . "\n";
}
	 
  
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

echo '</table>' . "\n";
echo '<br />' . "\n";



echo '<hr noshade="noshade" width="50%" />' . "\n";

// Comments
$query2 = "SELECT cmntID, subject, username, comment, DATE_FORMAT(cmnt_stmp, '%W, %M %d, %Y') FROM comments WHERE newsID='$n'";
$r2 = mysql_query($query2);
$num_rows2 = mysql_num_rows($r2);


/*if ($num_rows2 > 0) {
	echo '<p class="title">'.$title.'</p>' . "\n";
}*/
  
for ($i = 0; $i < $num_rows2; $i++) {
	$row2 = mysql_fetch_array($r2);
	$date = $row2[4];
  
	echo '<table>' . "\n";
	echo '  <tr>' . "\n";
	echo '    <td class="subj">' . $row2['subject'] . '</td>' . "\n";
	echo '  </tr>' . "\n";
  
	echo '  <tr>' . "\n";
	echo '    <td>Comment posted ' . $date . ' by ' . $row2['username'] . '</td>' . "\n";
	echo '  </tr>' . "\n";
  
	echo '  <tr>' . "\n";
	echo '    <td>' . "\n";
	echo '      ' . $row2['comment'] . "\n";
	echo '    </td>' . "\n";
	echo '  </tr>' . "\n";
	echo '</table>' . "\n";
	echo '<br />' . "\n";
}

if ($logged) {
	echo '<p><b>Post Comment:</b></p>' . "\n";
	echo '<form action="'.basename($PHP_SELF).'" method="post" name="form">' . "\n";
	echo '<table>' . "\n";
	echo '  <tr>' . "\n";
	echo '    <td>Subject:</td>' . "\n";
	echo '    <td><input name="subject" type="text" maxlength="50"></td>' . "\n";
	echo '  </tr>' . "\n";
	echo '  <tr>' . "\n";
	echo '    <td valign="top">Body:</td>' . "\n";
	echo '    <td><textarea name="comment" cols="50" rows="10"></textarea></td>' . "\n";
	echo '  </tr>' . "\n";
	echo '  <tr>' . "\n";
	echo '    <td colspan="2"><input name="post" type="submit" value="Post"></td>' . "\n";
	echo '  </tr>' . "\n";
	echo '</table>' . "\n";
	echo '  <input name="n" type="hidden" value="'.$n.'">' . "\n";
	echo '</form>' . "\n";
}


echo '<p>[ <a href="/">Back</a> ]</p>' . "\n";

include("include/footer.php");
?>