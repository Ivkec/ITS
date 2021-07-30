<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

$sql = "SELECT `role` FROM tits.users WHERE id='".$_SESSION['user_id']."' AND role='RootAdmin'";
$userRoleValidation = mysqli_query($conn, $sql);
$resultURV = mysqli_fetch_assoc($userRoleValidation);
if($resultURV == 0){
    header('Location: /ITS/admin_main');
    exit;
}

$sql1 = "SELECT tickets.IT_tech_ID, users.name AS ime, users.surname AS prezime, users.role, COUNT(tickets.IT_tech_ID) AS ValueFrequency FROM tickets 
INNER JOIN users ON tickets.IT_tech_ID = users.id 
WHERE IT_tech_ID IS NOT NULL /* AND tickets.status = 'ZATVOREN'*/ GROUP BY IT_tech_ID  ORDER BY ValueFrequency DESC LIMIT 5;";
$query1 = mysqli_query($conn, $sql1);

?>



      <div class="container">
         <h2 class="text-danger text-center">ROOT ADMINISTRATOR - <em>STATISTICS  <img src="Slike/icon-admin-18.jpg" alt="ing admin" width="50px" height="50px"></em></h2>
         <br>

       
<?php
 
 
 while($row = mysqli_fetch_assoc($query1)){
  $dataPoints[] = array("label"=> $row['ime']." ".$row['prezime'], "y"=> $row['ValueFrequency']);
 }


   
 ?>
 <div>
      <script>
      window.onload = function () {
       
      var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        exportEnabled: false,
        title:{
          text: "Top 5 IT Tehničara ' ALL TIME"
        },
        subtitles: [{
          text: "Hoveruj da vidiš koliko je tiketa rešeno."
        }],
        data: [{
          type: "pie",
          showInLegend: "true",
          legendText: "{label}",
          indexLabelFontSize: 18,
          indexLabel: "{label} - #percent%",
          yValueFormatString: "REŠIO #,##0 TIKETA",
          dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chart.render();
       
      }
      </script>
      
      <div id="chartContainer" style="height: 370px; width: 100%;"></div>
      <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
    
</div>


