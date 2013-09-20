<html>
<head>
	<meta charset='utf-8'>
<title> Bicikli hely foglalás</title>
<link href="tarolo.css" rel="stylesheet" type="text/css">

</head>
<body>
<div align='center'>
<p class='adminmgm'>Admin settings:</p>
<p class='adminmgm'><a href="adminlogout.php">Logout</a></p>
<p class='adminmgm'><a href="emailmgm.php">Email management</a></p>

</div>
<table border="2px">
	<tr>
		<td>Sorszám</td><td>Név</td><td>állapot</td><td>Szerzõdés?</td><td>Kulcs?</td><td>Időszak</td><td>Emailcím</td><td></td><td>Megjegyzés</td><td>modosítás dátuma</td><td>ki modosította</td>
	</tr>
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
            $remuser = pg_escape_string($_SERVER['REMOTE_USER']);
            $sql = "SELECT * FROM admin WHERE username='$remuser'";
            if($dbconn->query($sql)->rowCount()!=1)
            	header("location:https://stewie.sch.bme.hu/Shibboleth.sso/Login?target=https://stewie.sch.bme.hu/geri/taroloadmin.php");

            //Esetleges POST kezelése db-be
            if(isset($_POST['id']))
            {
            	$id=pg_escape_string($_POST['id']);
            	$name=pg_escape_string($_POST['name']);
            	$state=pg_escape_string($_POST['state']);
            	$szerzodes=pg_escape_string($_POST['szerzodes']);
            	if($szerzodes=="") $szerzodes=0;
            	$haskey=pg_escape_string($_POST['kulcs']);
            	if($haskey=="") $haskey=0;
            	$idoszak=pg_escape_string($_POST['idoszak']);
            	$comment=pg_escape_string($_POST['comment']);
            	$useremail=pg_escape_string($_POST['email']);
            	$modifyadmin=pg_escape_string($_SERVER['HTTP_COMMON_NAME']);
            	if($state==1 || $state==3)
            		$sql = "UPDATE helyek SET name='$name', state='$state', szerzodes='$szerzodes', haskey='$haskey', idoszak='$idoszak', comment='$comment', useremail='$useremail', modifydate=CURRENT_TIMESTAMP, modifyadmin='$modifyadmin' WHERE id='$id'";
            	else
            		$sql = "UPDATE helyek SET name='', state='$state', szerzodes='0', haskey='0', idoszak='', comment='$comment', useremail='', uservirid='', modifydate=CURRENT_TIMESTAMP, modifyadmin='$modifyadmin' WHERE id='$id'";
            	$dbconn->exec($sql);
            	print "Adatok sikeresen módosítva";
            }

            //Adatok kiírása
            $sql = "SELECT * FROM helyek ORDER BY id";
            foreach ($dbconn->query($sql) as $row) {
                    switch ($row['state']) {
                  			case 0:
                   			print "<tr class='ures' title='Igényelhetõ'>
                   				<form action='taroloadmin.php#' method='post'>
                   				<td><input type='text' value='".$row['id']."' size='5' disabled>
                   				<input type='hidden' name='id' value='".$row['id']."'></td>
                   				<td><input type='text' name='name' size='30' value=' Üres'></td>
                   				<td>
									<select name='state'>
										<option value='0' selected>Üres</option>
										<option value='1'>Foglalt</option>
										<option value='2'>Körös hely</option>
										<option value='3'>Feldolgozás alatt</option>
										<option value='4'>Ismeretlen bicikli</option>
									</select>
								</td>
								<td><input type='checkbox' name='szerzodes'></td>
								<td><input type='checkbox' name='kulcs'</td>
								<td>
									<select name='idoszak'>
										<option value='nyar'>nyár</option>
										<option value='osz' selected>õsz</option>
										<option value='tavasz'>tavasz</option>
										<option value='ideiglenes'>ideiglenes</option>
									</select>
								</td>
								<td><input type='text' name='email' size='30' value=''></td>
								<td><input type='submit' value='Submit'></td>
								<td><textarea name='comment' maxlength='255' rows='1'>".$row['comment']."</textarea></td>
								<td><p>".substr($row['modifydate'], 0, 20)."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
							</form>
							</tr>";
                   			break;
                   			case 1:
                   			print "<tr class='foglalt' title='Szerzõdéses'>
							<form action='taroloadmin.php#' method='post'>
								<td><input type='text' value='".$row['id']."' size='5' disabled>
                   				<input type='hidden' name='id' value='".$row['id']."'></td>
								<td><input type='text' name='name' size='30' value='".$row['name']."'></td> 
								<td>
									<select name='state'>
										<option value='0'>Üres</option>
										<option value='1' selected>Foglalt</option>
										<option value='2'>Körös hely</option>
										<option value='3'>Feldolgozás alatt</option>
										<option value='4'>Ismeretlen bicikli</option>
									</select>
								</td>
								<td><input type='checkbox' name='szerzodes' ".($row['szerzodes']==1?"checked":"")."></td>
								<td><input type='checkbox' name='kulcs' ".($row['haskey']==1?"checked":"")."></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'".($row['idoszak']=="nyar"?"selected":"").">nyár</option>
										<option value='osz' ".($row['idoszak']=="osz"?"selected":"").">õsz</option>
										<option value='tavasz'".($row['idoszak']=="tavasz"?"selected":"").">tavasz</option>
										<option value='ideiglenes'".($row['idoszak']=="ideiglenes"?"selected":"").">ideiglenes</option>
									</select>
								</td>
								<td><input type='text' name='email' size='30' value='".$row['useremail']."'></td>
								<td><input type='submit' value='Submit'></td>
								<td><textarea name='comment' maxlength='255' rows='1'>".$row['comment']."</textarea></td>
								<td><p>".substr($row['modifydate'], 0, 20)."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
							</form>
							</tr>";
							break;
							case 2:
                   			print "<tr class='koroshely' title='koroshely'>
                   				<form action='taroloadmin.php#' method='post'>
                   				<td><input type='text' value='".$row['id']."' size='5' disabled>
                   				<input type='hidden' name='id' value='".$row['id']."'></td>
                   				<td><input type='text' name='name' size='30' value='Kerékpár Kör'></td>
                   				<td>
									<select name='state'>
										<option value='0'>Üres</option>
										<option value='1'>Foglalt</option>
										<option value='2' selected>Körös hely</option>
										<option value='3'>Feldolgozás alatt</option>
										<option value='4'>Ismeretlen bicikli</option>
									</select>
								</td>
								<td><input type='checkbox' name='szerzodes'></td>
								<td><input type='checkbox' name='kulcs'></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'>nyár</option>
										<option value='osz' selected>õsz</option>
										<option value='tavasz'>tavasz</option>
										<option value='ideiglenes'>ideiglenes</option>
									</select>
								</td>
								<td><input type='text' name='email' size='30' value=''></td>
								<td><input type='submit' value='Submit'></td>
								<td><textarea name='comment' maxlength='255' rows='1'>".$row['comment']."</textarea></td>
								<td><p>".substr($row['modifydate'], 0, 20)."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
							</form>
							</tr>";
                   			break;
                   			case 3:
                   			print "<tr class='var' title='var'>
							<form action='taroloadmin.php#' method='post'>
								<td><input type='text' value='".$row['id']."' size='5' disabled>
                   				<input type='hidden' name='id' value='".$row['id']."'></td>
								<td><input type='text' name='name' size='30' value='".$row['name']."'></td> 
								<td>
									<select name='state'>
										<option value='0'>Üres</option>
										<option value='1'>Foglalt</option>
										<option value='2'>Körös hely</option>
										<option value='3' selected>Feldolgozás alatt</option>
										<option value='4'>Ismeretlen bicikli</option>
									</select>
								</td>
								<td><input type='checkbox' name='szerzodes' ".($row['szerzodes']==1?"checked":"")."></td>
								<td><input type='checkbox' name='kulcs' ".($row['hsakey']==1?"checked":"")."></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'".($row['idoszak']=="nyar"?"selected":"").">nyár</option>
										<option value='osz' ".($row['idoszak']=="osz"?"selected":"").">õsz</option>
										<option value='tavasz'".($row['idoszak']=="tavasz"?"selected":"").">tavasz</option>
										<option value='ideiglenes'".($row['idoszak']=="ideiglenes"?"selected":"").">ideiglenes</option>
									</select>
								</td>
								<td><input type='text' name='email' size='30' value='".$row['useremail']."'></td>
								<td><input type='submit' value='Submit'></td>
								<td><textarea name='comment' maxlength='255' rows='1'>".$row['comment']."</textarea></td>
								<td><p>".substr($row['modifydate'], 0, 20)."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
							</form>
							</tr>";
							break;
                   			case 4:
                   			print "<tr class='ismeretlen' title='ismeretlen'>
                   				<form action='taroloadmin.php#' method='post'>
                   				<td><input type='text' value='".$row['id']."' size='5' disabled>
                   				<input type='hidden' name='id' value='".$row['id']."'></td>
                   				<td><input type='text' name='name' size='30' value='Ismeretlen bicikli'></td>
                   				<td>
									<select name='state'>
										<option value='0' >Üres</option>
										<option value='1'>Foglalt</option>
										<option value='2'>Körös hely</option>
										<option value='3'>Feldolgozás alatt</option>
										<option value='4' selected>Ismeretlen bicikli</option>
									</select>
								</td>
								<td><input type='checkbox' name='szerzodes'></td>
								<td><input type='checkbox' name='kulcs'></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'>nyár</option>
										<option value='osz' selected>õsz</option>
										<option value='tavasz'>tavasz</option>
										<option value='ideiglenes'>ideiglenes</option>
									</select>
								</td>
								<td><input type='text' name='email' size='30' value=''></td>
								<td><input type='submit' value='Submit'></td>
								<td><textarea name='comment' maxlength='255' rows='1'>".$row['comment']."</textarea></td>
								<td><p>".substr($row['modifydate'], 0, 20)."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
							</form>
							</tr>";
                   			break;
                   			default:
                   			break;
                    }

                }


            $dbconn = null;
	    }

	?>
</table>
</body>
</html>
