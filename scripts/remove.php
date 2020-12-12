<?php
$dbh = new PDO("mysql:host=localhost;dbname=classroom","root","");
$id = isset($_GET['id'])?$_GET['id'] : "";
$stat = $dbh-> prepare("select * from assignment where id= ?");
$stat->bindParam(1,$id);
$stat->execute();
$row = $stat->fetch();
$aim = $row['aim'];
$teacher = $row['teacher'];
$stat1 = $dbh-> prepare("delete from assignment where id= ?");
$stat1->bindParam(1,$id);
$stat1->execute();
$stat2 = $dbh-> prepare("delete from storage where aim = ? and teacher = ?");
$stat2->bindParam(1,$aim);
$stat2->bindParam(2,$teacher);
$stat2->execute();
header("refresh:1;url=../tui.php")
 ?>
