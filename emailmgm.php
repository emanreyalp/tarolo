<html>
<head>
<meta charset='utf-8'>
<link href="dist/css/bootstrap.min.css" rel="stylesheet">

<script src="dist/js/bootstrap.min.js"></script>

</head>
<body>
	<p>
		Az itt megadott emailcímre értesítés fog érkezni amikor valaki helyre jelentkezik. Ha nem szeretnél értesítéseket kapni, akkor üres mezőt adj be.
	</p>
	<?php
		$loggedin = isset($_SERVER['REMOTE_USER']);
			if(!$loggedin)
			{
				header("location:https://stewie.sch.bme.hu/Shibboleth.sso/Login?target=https://stewie.sch.bme.hu/geri/taroloadmin.php");
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
	            $adminuser = pg_escape_string($_SERVER['REMOTE_USER']);
	            $sql = "SELECT * FROM admin WHERE username='$adminuser'";
	            if($dbconn->query($sql)->rowCount()!=1)
            		header("location:https://stewie.sch.bme.hu/Shibboleth.sso/Login?target=https://stewie.sch.bme.hu/geri/taroloadmin.php");
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
	            print "
			            <form  action='#' method='post' class='form-inline'>
			            <div class='col-md-4'>
						    <input type='email' class='form-control' id='InputEmail' placeholder='Enter email'>
						    </div>
						  	<button type='submit' class='btn btn-primary'>Submit</button>
					  	</form>
				  	";


	            $dbconn = null;
	        }
	?>
	<a href="taroloadmin.php" class="btn btn-default">Vissza</a>
</body>
</html>
