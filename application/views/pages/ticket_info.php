<?php
session_start();
error_reporting(0);
require_once "db_conn.php";

    $tic = $_GET['ticID'];
    $sql = "SELECT * FROM tickets WHERE tic_number='$tic'";
    $result = mysqli_query($conn, $sql);
    
  while($res =  mysqli_fetch_assoc($result)){
    $ime = $res['name'];
    $prezime = $res['surname'];
    $email = $res['email'];
    $lokacija = $res['location'];
    $departman = $res['department'];
    $date_cr = $res['date_created'];
    $tip_tiketa = $res['type_tic'];
    $tip_problema = $res['type_problem'];
    $opis_problema = $res['description'];
    $status = $res['status'];
    $br_tic = $res['tic_number'];
    $reklama_opis =  $res['reklamacija_comment'];
    $date_reklama =  $res['date_reklama'];
    $IT_tech_ID =  $res['IT_tech_ID'];
    $date_accepted =  $res['date_accepted_tic'];
    $date_closed =  $res['date_closed_tic'];
    $desc_solution =  $res['desc_solution'];
  }

  $sql2 = "SELECT * FROM users WHERE id='$IT_tech_ID'";
  $result2 = mysqli_query($conn, $sql2);

  while($res2 =  mysqli_fetch_assoc($result2)){
    $ITime = $res2['name'];
    $ITprezime = $res2['surname'];
  }
?>

<div class="container animate-bottom" id="container">
 <div class="card card-main bg-secondary text-white border border-white">
    <h3 class="text-center h3f">TIKET ID: <span class="text-warning"><?php echo $tic; ?></span></h3><br>
    <div class="tic-info">
    <div class="tic-info-child">Status: <span class="text-success"><?php echo $status; ?></span></div>
        <div class="tic-info-child">Broj tiketa: <span class="text-warning"><?php echo $br_tic; ?></span></div>
        <div class="tic-info-child">Datum kreiranja: <span class="text-warning"><?php echo $date_cr; ?></span></div>
        <div class="tic-info-child">Ime: <span class="text-warning"><?php echo $ime; ?></span></div>
        <div class="tic-info-child">Prezime: <span class="text-warning"><?php echo  $prezime; ?></span></div>
        <div class="tic-info-child">Email: <span class="text-warning"><?php echo $email; ?></span></div>
        <div class="tic-info-child">Lokacija: <span class="text-warning"><?php echo $lokacija; ?></span></div>
        <div class="tic-info-child">Departman: <span class="text-warning"><?php echo $departman; ?></span></div>
        <div class="tic-info-child">Tip tiketa: <span class="text-warning"><?php echo $tip_tiketa; ?></span></div>
        <div class="tic-info-child">Tip problema: <span class="text-warning"><?php echo $tip_problema; ?></span></div>
        <div class="tic-info-child">IT tehničar koji je preuzeo tiket: <span class="text-warning"><?php echo $ITime. " ".$ITprezime ; ?></span></div>
        <div class="tic-info-child">Datum kada je tehničar preuzeo tiket: <span class="text-warning"><?php echo convertDateTime($date_accepted); ?></span></div>
        <div class="tic-info-child">Komentar tehničara: <span class="text-warning"><?php echo $desc_solution; ?></span></div>
        <div class="tic-info-child">Datum zatvaranja tiketa: <span class="text-warning"><?php echo convertDateTime($date_closed); ?></span></div>
        <div class="tic-info-child">Opis problema: <span class="text-warning"><?php echo $opis_problema; ?></span></div>
        <div class="tic-info-child">Reklamacija tiketa: <span class="text-warning"><?php echo $reklama_opis; ?></span></div>
        <div class="tic-info-child">Datum reklamacije: <span class="text-warning"><?php echo convertDateTime($date_reklama); ?></span></div>
    </div>
    
              
    </div>      
</div>