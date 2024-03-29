<html>
<head>
<meta charset='utf-8'>
<title> Bicikli hely foglalás</title>
<link href="dist/css/bootstrap.min.css" rel="stylesheet">

<script src="dist/js/bootstrap.min.js"></script>
</head>
<body background="gray">
                <?php
                        $loggedin = isset($_SERVER['REMOTE_USER']);
                        if(!$loggedin)
                                header("location:tarolo.php");
			require 'db.php';			
                        
                        try{
                                $dbconn = new PDO("pgsql:host=localhost;port=5432;dbname=bicikli",$user ,$pass);
                        }
                        catch(PDOException $e)
                        {
                                print $e->getMessage();
                        }
                        if(!$dbconn)
                        {
                                print "DB connection Error";
                        }

                        $sql = "SELECT state FROM helyek WHERE id='$id'";

                        $count=0;
                        foreach ($dbconn->query($sql) as $row) {
                        	$count++;
                        	if($row['state']!=0) $count+=10;
                        }
                        print "<p class='lead'>";
                        if($count==1)
                        {
                        	$name = pg_escape_string($_SERVER['HTTP_COMMON_NAME']);
                        	$virid = pg_escape_string($_SERVER['REMOTE_USER']);
                        	$viremail = pg_escape_string($_SERVER['HTTP_EMAIL']);
                        	$sql = "SELECT * FROM helyek WHERE uservirid='$virid'";
                        	$result = $dbconn->query($sql);
                        	if($result->rowCount()==0)
                        	{
                        		$sql = "UPDATE helyek SET name='$name', uservirid='$virid', state='3', modifyadmin='', modifydate=CURRENT_TIMESTAMP, idoszak='osz', useremail='$viremail' WHERE id='$id' ";
                        		$rowsaffected = $dbconn->exec($sql);
                        		print "Sikeresen jelentkeztél helyre!";
                        		$sql = "SELECT email FROM admin WHERE email NOT NULL";
                        		foreach($dbconn->query($sql) as $row)
                        		{
                        			email($row['email'], "Helyre jelentkezés: $id", "$name jelentkezett a $id sorszámú helyre ".date('Y-m-d H:i:s')." időpontban.");
                        		}
                        	}
                        	else
                        		print "Már van foglalás a neveden!";
                        }
                        else
                        	print "Error: A hely nem üres";
                        print "</p>";
                        $dbconn=null;
                ?>
                <br/>
                <a href="tarolo.php" class="btn btn-default">Vissza</a>
        </body>
</html>
