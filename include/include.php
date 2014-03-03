<?

$link = mysql_connect('connection', 'user', 'password');
$database = "bongit";

if (!$link) {
   die('Could not connect: ' . mysql_error());
}

@mysql_select_db($database) or die ("Unable to connect to the database.");

?>
