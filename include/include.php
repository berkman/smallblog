<?

// MYSQL
/*
$link = mysql_connect('connection', 'user', 'password');
$database = "bongit";

if (!$link) {
  die('Could not connect: ' . mysql_error());
}

@mysql_select_db($database) or die ("Unable to connect to the database.");
*/

// POSTGRES
$dbname =     $_ENV["POSTGRES_DBNAME"];
$host =       $_ENV["POSTGRES_HOST"];
$port =       $_ENV["POSTGRES_PORT"];
$user =       $_ENV["POSTGRES_USERNAME"];
$password =   $_ENV["POSTGRES_PASSWORD"];

$link = pg_connect("$dbname $host $port $user $password sslmode=require");

?>
