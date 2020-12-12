<?php
$dbh = new PDO("mysql:host=localhost;dbname=classroom","root","");
$name = isset($_GET['id'])?$_GET['id'] : "";
$stat = $dbh-> prepare("delete from logs where name = ?");
$stat->bindParam(1,$name);
$stat->execute();
header("refresh:1;url=../tui.php")
 ?>
