<html>
<head>
</head>
<body>
<?php
require 'db.php';
try{
$dbconn = new PDO("pgsql:host=localhost;port=5432;dbname=bicikli",$user ,$pass);
//$dbconn = pg_connect("host=localhost port=5432 dbname=bicikli user=$user password=$pass");
if(!$dbconn)
{
print "para van!";
}
else
{
print "okes";
}
for ($i = 1; $i<79; $i++)
{
	if($i > 10)
	$dbconn->query("INSERT INTO helyek (id, state) VALUES ($i, 0);");
	else
	$dbconn->query("INSERT INTO helyek (id, state) VALUES ($i, 2);");
}

$dbconn = null;
}
catch(PDOException $e)
{
        print $e->getMessage();
}

?>
</body>
</html>
