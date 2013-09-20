<?php
	require 'db.php';
        try{
                $dbconn = new PDO("pgsql:host=localhost;port=5432;dbname=bicikli",$user ,$pass);
                if(!$dbconn)
                {
                        print "DB connection Error";
                }
	$username = pg_escape_string($_POST['username']);
	$password = md5(pg_escape_string($_POST['password']));
	$sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
	$result = $dbconn->query($sql);
	if($result->rowCount()==1)
	{
	session_register("usrnm");
	session_register("pwd");
	$_SESSION["username"] = $username;
	header("location:taroloadmin.php");
	}
	else
	{
	print "Wrong username/password!";
	
	}
	}
	catch(PDOEception $e)
	{
		print $e->getMessage();
	}
?>
