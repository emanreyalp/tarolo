<html>
<head>
<meta charset='utf-8'>
<title> Bicikli hely foglalás</title>
<link href="dist/css/bootstrap.min.css" rel="stylesheet">

<script src="dist/js/bootstrap.min.js"></script>

</head>
<body background="gray">
<p align="center">
        <font  size="50px">Schönherz kerékpár tároló</font>
</p>
                <?php
                        $loggedin = isset($_SERVER['REMOTE_USER']);
                        print "<nav class='navbar navbar-default' role='navigation'>";
                        if(!$loggedin)
                                print "<font color='darkblue'><b><a class='btn btn-default btn-block' href='https://stewie.sch.bme.hu/Shibboleth.sso/Login?target=https://stewie.sch.bme.hu/geri/tarolo.php'>Bejelentkezés</a></b></font>
                                A helyre jelentkezés csak bejelentkezés után érhető el.";
                        else
                                print "<div id='leiras'>
                                <p><font color='darkblue'><b><a href='https://stewie.sch.bme.hu/Shibboleth.sso/Logout?target=https://stewie.sch.bme.hu/geri/tarolo.php'>Kijelentkezés</a></b></font></p>
                                </div>";
                        print "</nav><div id='helyek' class='col-md-4'>
                        <table  class='table table-bordered'>";
                        print "<thead><tr>
                        <th>Sorszám</th><th>Állapot/Név</th>";
                        if ($loggedin)
                                print "<th>Hely igénylése</th>";
                        print "</tr></thead>";
                
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
                        $sql = "SELECT id, name, state FROM helyek ORDER BY id";
                        foreach ($dbconn->query($sql) as $row) {

                                switch ($row['state']) {
                                        case 0:
                                                print "<tr class='success' title='Igényelhetõ'>";
                                                print "<td> ".$row['id']." </td> <td> Üres</td>";
                                                break;
                                        case 1:
                                                print "<tr class='' title='Szerzõdéses'>";
						print "<td> ".$row['id']." </td> <td> ".$row['name']." </td>";
                                                break;
                                        case 2:
                                                print "<tr class='warning' title='Körös hely'>";
                                                print "<td > ".$row['id']." </td> <td> Kerékpár Kör</td>";
                                                break;
                                        case 3:
                                                print "<tr class='active'>";
                                                print "<td> ".$row['id']." </td> <td> Elbírálás alatt</td>";
                                                break;
                                        case 4:
                                                print "<tr class='danger'>";
                                                print "<td> ".$row['id']." </td> <td> Ismeretlen bicikli</td>";
                                                break;
                                        default:
                                                print "<tr>";
                                                break;
                                }
                        
                        if($loggedin)
                        {
                                print "<td>";
                                if($row['state']==0)
                                        print "<button onclick= 'window.location = \"request.php?id=".$row['id']."\";' class='btn btn-primary'>Jelentkezés</button>";
                                print "</td>";
                        }
                        print "</tr>";
                        
                        }
                        print "</table>";
                              
                        $dbconn = null;
                ?>
        
        <br>
</div>
</body>
</html>
