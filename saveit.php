<html>
<head>
	<meta charset='utf-8'>
<title> Bicikli hely foglalás</title>
<link href="tarolo.css" rel="stylesheet" type="text/css">

</head>
<body>
<table border="2px">
	<tr>
		<td>Sorszám</td><td>Név</td><td>állapot</td><td>szerzõdés?</td><td>kulcs?</td><td>idoszak</td><TD>megjegyzes</td><td>modosítás dátuma</td><td>ki modosította</td><td>user email</td><td></td>
	</tr>
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
            if(!$dbconn)
            {
                    print "DB connection Error";
            }
            //Esetleges POST kezelése db-be
            if(isset($_POST['id']))
            {
            	print "van ájdí";
            	$id=pg_escape_string($_POST['id']);
            	$name=pg_escape_string($_POST['name']);
            	$state=pg_escape_string($_POST['state']);
            	$szerzodes=pg_escape_string($_POST['szerzodes']);
		if($szerzodes=="") $szerzodes=0;
            	$haskey=pg_escape_string($_POST['haskey']);
           	if($haskey=="") $haskey=0;
            	$idoszak=pg_escape_string($_POST['idoszak']);
            	$comment=pg_escape_string($_POST['comment']);
            	$useremail=pg_escape_string($_POST['useremail']);
            	$modifydate=time();
            	$modifyadmin=$_SESSION['username'];
            	$sql = "UPDATE helyek SET name='$name', state='$state', szerzodes='$szerzodes', haskey='$haskey', idoszak='$idoszak', comment='$comment', modifydate='CURRENT_TIMESTAMP', modifyadmin='$modifyadmin' WHERE id='$id'";
		print "$sql";
            	$asd = $dbconn->exec($sql);
		print "$asd";
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
								<td><input type='checkbox' name='szerzodes' value='no'></td>
								<td><input type='checkbox' name='kulcs' value='no'></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'>nyár</option>
										<option value='osz' selected>õsz</option>
										<option value='tavasz'>tavasz</option>
										<option value='ideiglenes'>ideiglenes</option>
									</select>
								</td>
								<td><textarea name='comment' maxlength='255' rows='1'></textarea></td>
								<td><p>".$row['modifydate']."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
								<td><input type='text' name='email' size='30' value=''></td>
								<td><input type='submit' value='Submit'></td>
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
								<td><input type='checkbox' name='szerzodes' value='".$row['szerzodes']."' checked></td>
								<td><input type='checkbox' name='kulcs' value='".$row['haskey']."' checked></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'".($row['idoszak']=="nyar"?"selected":"").">nyár</option>
										<option value='osz' ".($row['idoszak']=="osz"?"selected":"").">õsz</option>
										<option value='tavasz'".($row['idoszak']=="tavasz"?"selected":"").">tavasz</option>
										<option value='ideiglenes'".($row['idoszak']=="ideiglenes"?"selected":"").">ideiglenes</option>
									</select>
								</td>
								<td><textarea name='comment' maxlength='255' rows='1' value='".$row['comment']."'></textarea></td>
								<td><p>".$row['modifydate']."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
								<td><input type='text' name='email' size='30' value='".$row['useremail']."'></td>
								<td><input type='submit' value='Submit'></td>
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
								<td><input type='checkbox' name='szerzodes' value='no'></td>
								<td><input type='checkbox' name='kulcs' value='no'></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'>nyár</option>
										<option value='osz' selected>õsz</option>
										<option value='tavasz'>tavasz</option>
										<option value='ideiglenes'>ideiglenes</option>
									</select>
								</td>
								<td><textarea name='comment' maxlength='255' rows='1'></textarea></td>
								<td><p>".$row['modifydate']."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
								<td><input type='text' name='email' size='30' value=''></td>
								<td><input type='submit' value='Submit'></td>
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
								<td><input type='checkbox' name='szerzodes' value='".$row['szerzodes']."' checked></td>
								<td><input type='checkbox' name='kulcs' value='".$row['haskey']."' checked></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'".($row['idoszak']=="nyar"?"selected":"").">nyár</option>
										<option value='osz' ".($row['idoszak']=="osz"?"selected":"").">õsz</option>
										<option value='tavasz'".($row['idoszak']=="tavasz"?"selected":"").">tavasz</option>
										<option value='ideiglenes'".($row['idoszak']=="ideiglenes"?"selected":"").">ideiglenes</option>
									</select>
								</td>
								<td><textarea name='comment' maxlength='255' rows='1' value='".$row['comment']."'></textarea></td>
								<td><p>".$row['modifydate']."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
								<td><input type='text' name='email' size='30' value='".$row['useremail']."'></td>
								<td><input type='submit' value='Submit'></td>
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
								<td><input type='checkbox' name='szerzodes' value='no'></td>
								<td><input type='checkbox' name='kulcs' value='no'></td>
								<td>
									<select name='idoszak'>
										<option value='nyar'>nyár</option>
										<option value='osz' selected>õsz</option>
										<option value='tavasz'>tavasz</option>
										<option value='ideiglenes'>ideiglenes</option>
									</select>
								</td>
								<td><textarea name='comment' maxlength='255' rows='1'></textarea></td>
								<td><p>".$row['modifydate']."</p></td>
								<td><p>".$row['modifyadmin']."</p></td>
								<td><input type='text' name='email' size='30' value=''></td>
								<td><input type='submit' value='Submit'></td>
							</form>
							</tr>";
                   			break;
                   			default:
                   			break;
                    }

                }


            $dbconn = null;
	        }
	        catch(PDOException $e)
	        {
	                print $e->getMessage();
	        }
	    }

	?>
	<!--
	<tr class="koroshely" title="koroshely">
	<form action="taroloadmin.php" method="post" id="">

		<td><input type="text" name="id" value="1." size="5" disabled></td>
		<td><input type="text" name="name" size="30" value=" Kerékpár Kör"></td>
		<td>
			<select name="state">
				<option value="0">Üres</option>
				<option value="1">Foglalt</option>
				<option value="2" selected>Körös hely</option>
				<option value="3">Feldolgozás alatt</option>
				<option value="4">Ismeretlen bicikli</option>
			</select>
		</td>
		<td><input type="checkbox" name="szerzodes" value="yes"></td>
		<td><input type="checkbox" name="kulcs" value="yes"></td>
		<td>-</td>
		<td><textarea name="comment" maxlength="255" rows="3"></textarea></td>
		<td></td>
		<td></td>
		<td><input type="text" name="email" size="30" value="korvezeto@bicikli.sch.bme.hu"></td>
		<td><input type="submit" value="Submit"></td>
	</form>
	</tr>
	<tr class="ures" title="Igényelhetõ">
	<form action="demo_form.asp">
		<td> 2. </td> 
		<td><input type="text" name="name" size="30" value=" Üres"></td> 
		<td>
			<select name="state">
				<option value="0" selected>Üres</option>
				<option value="1">Foglalt</option>
				<option value="2">Körös hely</option>
				<option value="3">Feldolgozás alatt</option>
				<option value="4">Ismeretlen bicikli</option>
			</select>
		</td>
		<td><input type="checkbox" name="szerzodes" value="no"></td>
		<td><input type="checkbox" name="kulcs" value="no"></td>
		<td>-</td>
		<td><textarea name="comment" maxlength="255" rows="3"></textarea></td>
		<td></td>
		<td></td>
		<td><input type="text" name="email" size="30" value=""></td>
		<td><input type="submit" value="Submit"></td>
	</form>
	</tr>
	<tr class="foglalt" title="Szerzõdéses">
	<form action="demo_form.asp">
		<td> 3.	</td> 
		<td><input type="text" name="name" size="30" value=" Gedeon Geza"></td> 
		<td>
			<select name="state">
				<option value="0">Üres</option>
				<option value="1" selected>Foglalt</option>
				<option value="2">Körös hely</option>
				<option value="3">Feldolgozás alatt</option>
				<option value="4">Ismeretlen bicikli</option>
			</select>
		</td>
		<td><input type="checkbox" name="szerzodes" value="yes" checked></td>
		<td><input type="checkbox" name="szerzodes" value="yes" checked></td>
		<td>
			<select name="idoszak">
				<option value="nyar">nyár</option>
				<option value="osz">õsz</option>
				<option value="tavasz">tavasz</option>
				<option value="ideiglenes">ideiglenes</option>
			</select>
		</td>
		<td><textarea name="comment" maxlength="255" rows="3"></textarea></td>
		<td></td>
		<td></td>
		<td><input type="text" name="email" size="30" value="geza@gedeon.hu"></td>
		<td><input type="submit" value="Submit"></td>
	</form>
	</tr>
	<tr class="var">
	<form action="demo_form.asp">
		<td> 4. </td> 
		<td><input type="text" name="name" size="30" value=" Taroló Tamás"></td> 
		<td>
			<select name="state">
				<option value="0">Üres</option>
				<option value="1">Foglalt</option>
				<option value="2">Körös hely</option>
				<option value="3" selected>Feldolgozás alatt</option>
				<option value="4">Ismeretlen bicikli</option>
			</select>
		</td>
		<td><input type="checkbox" name="szerzodes" value="no"></td>
		<td><input type="checkbox" name="kulcs" value="no"></td>
		<td>
			<select name="idoszak">
				<option value="nyar">nyár</option>
				<option value="osz">õsz</option>
				<option value="tavasz">tavasz</option>
				<option value="ideiglenes" selected>ideiglenes</option>
			</select>
		</td>
		<td><textarea name="comment" maxlength="255" rows="3"></textarea></td>
		<td></td>
		<td></td>
		<td><input type="text" name="email" size="30" value="tamas@tarolo.hu"></td>
		<td><input type="submit" value="Submit"></td>
	</form>
	</tr>
	<tr class="ures">
	<form action="demo_form.asp">
		<td> 5. </td> 
		<td><input type="text" name="name" size="30" value=" Üres"></td> 
		<td>
			<select name="state">
				<option value="0" selected>Üres</option>
				<option value="1">Foglalt</option>
				<option value="2">Körös hely</option>
				<option value="3">Feldolgozás alatt</option>
				<option value="4">Ismeretlen bicikli</option>
			</select>
		</td>
		<td><input type="checkbox" name="szerzodes" value="no"></td>
		<td><input type="checkbox" name="kulcs" value="no"></td>
		<td>-</td>
		<td><textarea name="comment" maxlength="255" rows="3"></textarea></td>
		<td></td>
		<td></td>
		<td><input type="text" name="email" size="30" value=""></td>
		<td><input type="submit" value="Submit"></td>
	</form>
	</tr>
	<tr class="foglalt">
	<form action="demo_form.asp">
		<td> 6.	</td> 
		<td><input type="text" name="name" size="30" value="Alma Aladár"></td>
		<td>
			<select name="state">
				<option value="0">Üres</option>
				<option value="1" selected>Foglalt</option>
				<option value="2">Körös hely</option>
				<option value="3">Feldolgozás alatt</option>
				<option value="4">Ismeretlen bicikli</option>
			</select>
		</td>
		<td><input type="checkbox" name="szerzodes" value="yes" checked></td>
		<td><input type="checkbox" name="kulcs" value="no"></td>
		<td>
			<select name="state">
				<option value="nyar" selected>nyár</option>
				<option value="osz">õsz</option>
				<option value="tavasz">tavasz</option>
				<option value="ideiglenes">ideiglenes</option>
			</select>	
		</td>
		<td><textarea name="comment" maxlength="255" rows="3"></textarea></td>
		<td></td>
		<td></td>
		<td><input type="text" name="email" size="30" value="aladar@alma.hu"></td>
		<td><input type="submit" value="Submit"></td>
	</form>
	</tr>
	!-->
</table>
</body>
</html>
