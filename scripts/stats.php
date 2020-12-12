<!DOCTYPE html>
<link rel="stylesheet" href="../css/ui.css">
<style media="screen">

</style>
<html>
<?php
$dbh = new PDO("mysql:host=localhost;dbname=classroom","root","");
$aim = isset($_GET['id'])?$_GET['id'] : "";

$stmm = $dbh-> prepare("select grade, count(*) as number from storage inner join grades on storage.id = grades.id  where aim = ? group by grade");
$stmm ->bindParam(1,$aim);
$stmm->execute();
 ?>
<head>
  <title>Assignment Statistics</title>
  <link rel="apple-touch-icon" sizes="180x180" href="../images/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="../images/favicon-16x16.png">
  <link rel="manifest" href="images/site.webmanifest">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
             <script type="text/javascript">
             google.charts.load('current', {'packages':['corechart']});
             google.charts.setOnLoadCallback(drawChart);
             function drawChart()
             {
                  var data = google.visualization.arrayToDataTable([
                            ['Grade', 'Number'],
                            <?php
                            while($row = $result = $stmm->fetch())
                            {
                                 echo "['".$row["grade"]."', ".$row["number"]."],";
                            }
                            ?>
                       ]);
                  var options = {
                        title: 'Analysis: ',
                        is3D:true,
                        pieHole: 0.4
                       };
                  var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                  chart.draw(data, options);
             }

<?php
$stmm2 = $dbh-> prepare("select status, count(*) as number from storage inner join grades on storage.id = grades.id  where aim = ? group by status");
$stmm2 ->bindParam(1,$aim);
$stmm2->execute();

 ?>
 google.charts.load('current', {'packages':['corechart']});
             google.charts.setOnLoadCallback(drawChart1);
             function drawChart1()
             {
                  var data = google.visualization.arrayToDataTable([
                            ['Status', 'Number'],
                            <?php
                            while($row = $result = $stmm2->fetch())
                            {
                                 echo "['".$row["status"]."', ".$row["number"]."],";
                            }
                            ?>
                       ]);
                  var options = {
                        title: 'Analysis: ',
                        is3D:true,
                        pieHole: 0.4
                       };
                  var chart = new google.visualization.PieChart(document.getElementById('piechart1'));
                  chart.draw(data, options);
             }
             </script>
<style>
*{
  margin: 0px;
  padding: 0px;
}
</style>
</head>
<body>
    <img src="../images/logo.png" alt="" style="padding-left:450px;">

  <div class="wholepage" style="margin-left:100px; margin-top:-50px;">
  <div class="assignpart">
    <section id="a1">
<?php

echo "<p class='section_heading'>".$aim." Statistics</p><hr><br>";

$stmt = $dbh-> prepare("select count(*) from storage where aim = ?");
$stmt ->bindParam(1,$aim);
$stmt->execute();
while($rows = $stmt->fetch()){
echo "<p style='font-size:20px'>Total Responses: <b>".$rows[0]."</b></p><br>";
}
$a = array();
$b = array();
echo "<p style='font-size:20px'>Students who haven't submitted:<br>";

$stmt = $dbh-> prepare("select student from storage where aim = ?");
$stmt ->bindParam(1,$aim);
$stmt->execute();
while($rows = $stmt->fetch()){
array_push($a,$rows[0]);
}
$student = 'student';
$stmt = $dbh-> prepare("select email from reg where role = ?");
$stmt ->bindParam(1,$student);
$stmt->execute();
while($rows = $stmt->fetch()){
array_push($b,$rows[0]);
}

$result = array_diff($b,$a);
foreach($result as $value){
  echo '<b>'.$value.'</b><br>';
}
echo "<br>";

$stmt = $dbh-> prepare("select avg(grade) from storage inner join grades on storage.id = grades.id where aim = ?");
$stmt ->bindParam(1,$aim);
$stmt->execute();
while($rows = $stmt->fetch()){
echo "<p style='font-size:20px'>Average marks for this assignment: <b>".$rows[0]."</b></p>";
}
$limit = '3';
echo "<br><p style='font-size:20px'>Students with less than 3 marks: </p>";
$stmt = $dbh-> prepare("select student from storage inner join grades on storage.id = grades.id where aim = ? and grade < ?");
$stmt ->bindParam(1,$aim);
$stmt ->bindParam(2,$limit);
$stmt->execute();
while($rows = $stmt->fetch()){
echo "<p style='font-size:20px'><b>".$rows[0]."</b></p>";
}
echo "<br><p style='font-size:20px'>Highest Scorer: </p>";
$stm = $dbh-> prepare("select max(grade) from storage inner join grades on storage.id = grades.id where aim = ?");
$stm ->bindParam(1,$aim);
$stm->execute();
$top = $stm->fetch();
$stmt = $dbh-> prepare("select student from storage inner join grades on storage.id = grades.id where aim = ? and grade =?");
$stmt ->bindParam(1,$aim);
$stmt ->bindParam(2,$top[0]);
$stmt->execute();
while($rows = $stmt->fetch()){
echo "<p style='font-size:20px'><b>".$rows[0]."</b></p>";
echo "<br>";
}
?>

  <br /><br />
           <div style="width:700px; padding-left:100px;">
                <h3 align="center">Distribution of marks</h3>
                <br />
                <div id="piechart" style="width: 900px; height: 500px;"></div>
           </div>
           <br>

                    <div style="width:700px; padding-left:100px;">
                         <h3 align="center">Submit Status</h3>
                         <br />
                         <div id="piechart1" style="width: 900px; height: 500px;"></div>
                    </div>
    </section>
  </div>  </div><hr>
</body>
<footer style="text-align:center; padding: 50px;">
  <span>&copy; Smart Assign, 2020. All Rights Reserved</span>
</footer>
</html>
