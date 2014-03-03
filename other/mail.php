<?

if (isset($_POST['send'])) {
	$to = $_POST['to'];
	$cc = $_POST['cc'];
	$from = $_POST['from'];
	$subj = $_POST['subject'];
	$body = $_POST['body'];

	mail ($to, $subj, $body, 'From: '.$from);
	
	$message = "Mail sent.";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Anonymous E-mailer</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<h1>Anonymous E-mailer by Mike Berkman</h1>
<?
if ($message != "") {
	echo '<p>'.$message.'</p>'."\n";
}
?>
<form action="" method="post" name="form">
<table>
  <tr>
    <td>To:</td>
	<td><input name="to" type="text" value="<? echo $to; ?>" /></td>
  </tr>
  <tr>
    <td>From:</td>
	<td><input name="from" type="text" maxlength="20" value="<? echo $from; ?>" /></td>
  </tr>
    <tr>
    <td>Subject:</td>
	<td><input name="subject" type="text" maxlength="20" value="<? echo $subj; ?>" /></td>
  </tr>
  <tr>
    <td valign="top">Body:</td>
	<td><textarea name="body" cols="60" rows="10"><? echo $body; ?></textarea></td>
  </tr>
  <tr>
    <td><input name="send" type="submit" value="Send" /></td>
	<td><input name="clear" type="reset" value="Clear" /></td>
  </tr>
</table>
</form>
</body>
</html>