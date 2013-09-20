<html>
<head>
	<meta charset='utf-8'>

</head>
<body>
	<p>
		Az itt megadott emailcímre értesítés fog érkezni amikor valaki helyre jelentkezik. Ha nem szeretnél értesítéseket kapni, akkor üres mezőt adj be.
	</p>
	<?php
		session_start();
			if(!session_is_registered("usrnm"))
			{
				header("location:adminlogin.html");
			}
			else
			{
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
	            $adminuser=$_SESSION['username'];
	            if(isset($_POST['email']))
	            {
	            	$email = pg_escape_string($_POST['email']);
	            	$sql = "UPDATE admin SET email='$email' WHERE username='$adminuser'";
	            	$dbconn->exec($sql);
	            	print "Email sikeresen módosítva";
	            }

	            $sql = "SELECT email FROM admin WHERE username='$adminuser'";
	            $result = $dbconn->query($sql);
	            $row = $result->fetchObject();
	            $curemail = $row->email;
	            print "<p><form  action='#' method='post'> <input type='text' name='email' size='30' value='$curemail'><td><input type='submit' value='Submit'></td></form></p>";


	            $dbconn = null;
	        }
	?>
<button onclick= 'window.location = "taroloadmin.php";'>Vissza</button>
</body>
</html>
