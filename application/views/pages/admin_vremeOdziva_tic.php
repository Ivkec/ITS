<?php
require_once "db_conn.php";

session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

//prihvaceni tiketi
$sql = "SELECT * FROM tits.tickets WHERE date_accepted_tic IS NOT NULL ORDER BY date_accepted_tic DESC LIMIT 100";
$query = mysqli_query($conn, $sql);

$date_from = $_POST['dateFrom'];
$date_to = $_POST['dateTo'];
$techID = $_POST['techID'];
$techSQL = ""; 

//TIC FILTER BETWEN TWO DATES AND IT TECH
if(isset($_POST['btn_filter'])){

  if($techID == "ALL"){ //IF SELECTED ALL TECH, NOT NEED ID IT TECH
    $techSQL ="";
  }
  else{
    $techSQL = "AND IT_tech_ID='$techID'";
  }
  
  $sql = "SELECT * FROM tickets WHERE date_accepted_tic between '$date_from' AND '$date_to' $techSQL LIMIT 100"; 
  $query = mysqli_query($conn, $sql);
}
//LIST IT TECH
$sql3 = "SELECT * FROM tits.users";
$query3 = mysqli_query($conn, $sql3);

?>

<div class="card-vr-odziva"> 
<div class="bg-light card-filter">
    <form action="" method="POST">
        <small class="text-primary"><b>FILTER</b></small>&nbsp;&nbsp;&nbsp;
        <label class="text-primary">OD:</label>
        <input type="datetime-local" name="dateFrom" class="border-primary text-primary rounded">&nbsp;
        <label class="text-primary">DO:</label>
        <input type="datetime-local" name="dateTo" value="<?php echo date('Y-m-d\TH:i'); ?>" class="border-primary text-primary rounded">&nbsp;
        <select name="techID" class="border-primary text-primary rounded">
        <option value="ALL" selected>Svi tehničari</option>
        <?php while($res3 = mysqli_fetch_assoc($query3)) echo "<option value='".$res3['id']."' class='text-primary'>".$res3['name']." ".$res3['surname']."</option>"; ?>
        </select>
        &nbsp;
        <input type="submit" value="Pretraži" name="btn_filter" class="btn btn-sm btn-secondary ">
    </form>
</div>
    <table class="table table-bordered table-dark text-center">
  <thead>
      <tr>
          <th colspan="7"><h2>Vreme odziva tiketa - ZADNJIH 100</h2></th>
      </tr>
    <tr class="text-info">
      <th scope="col">#</th>
      <th scope="col">Broj tiketa</th>
      <th scope="col">Ime i prezime IT tehničara</th>
      <th scope="col">E-mail Korisnika</th>
      <th scope="col">Vreme kreiranja</th>
      <th scope="col">Vreme prihvatanja</th>
      <th scope="col">Ukupno vreme odziva</th>
    </tr>
  </thead>
  <tbody>
      <?php  $i = 0; while($res =  mysqli_fetch_assoc($query)): ?>
        <?php $i++; ?>
        <?php 
           $itTECH_ID = $res['IT_tech_ID'];
           //it teknicari
           $sql2 = "SELECT * FROM tits.users WHERE id='$itTECH_ID'";
           $query2 = mysqli_query($conn, $sql2);
           while($res2 =  mysqli_fetch_assoc($query2)){
              $ime_teh = $res2['name'];
              $prezime_teh = $res2['surname'];
              $email_teh = $res2['email'];
              $role = $res2['role'];
           }
        ?>
    <tr>
    <th scope="col"><?php echo $i; ?></th>
      <th scope="col"><?php echo $res['tic_number']; ?></th>
      <th scope="col"><?php echo roleBadge($role) .$ime_teh." ".$prezime_teh; ?></th>
      <th scope="col"><?php echo $res['email']; ?></th>
      <th scope="col">
      <?php
        $date_cr_y = substr($res['date_created'], 0, 4); //get year
        $date_cr_m = substr($res['date_created'], 5, 2); //get month
        $date_cr_d = substr($res['date_created'], 8, 2); //get day
        $date_cr_time = substr($res['date_created'], -8, 5); //get TIME
        $format_date_cr = $date_cr_d.".".$date_cr_m.".".$date_cr_y.". ". $date_cr_time;
      echo $format_date_cr; 
      ?>
      </th>
      <th scope="col">
      <?php 
        $date_ac_y = substr($res['date_accepted_tic'], 0, 4); //get year
        $date_ac_m = substr($res['date_accepted_tic'], 5, 2); //get month
        $date_ac_d = substr($res['date_accepted_tic'], 8, 2); //get day
        $date_ac_time = substr($res['date_accepted_tic'], -8, 5); //get TIME
        $format_date_ac = $date_ac_d.".".$date_ac_m.".".$date_ac_y.". ". $date_ac_time;
        echo $format_date_ac; 
        ?>
        </th>
      <th scope="col">
      <?php 
        $date_cr_y = substr($res['date_created'], 0, 4); //get year
        $date_cr_m = substr($res['date_created'], 5, 2); //get month
        $date_cr_d = substr($res['date_created'], 8, 2); //get day
        $date_cr_time = substr($res['date_created'], -8); //get TIME
        $format_date_cr = $date_cr_y."-".$date_cr_m."-".$date_cr_d." ". $date_cr_time;
 
        $date_ac_y = substr($res['date_accepted_tic'], 0, 4); //get year
        $date_ac_m = substr($res['date_accepted_tic'], 5, 2); //get month
        $date_ac_d = substr($res['date_accepted_tic'], 8, 2); //get day
        $date_ac_time = substr($res['date_accepted_tic'], -8); //get TIME
        $format_date_ac = $date_ac_y."-".$date_ac_m."-".$date_ac_d." ". $date_ac_time;
 
        $diff = abs(strtotime($format_date_ac) - strtotime($format_date_cr)); 
       
        $years   = floor($diff / (365*60*60*24)); 
        $months  = floor(($diff - $years * 365*60*60*24) / (30*60*60*24)); 
        $days    = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24) / (60*60*24));
        $hours   = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24)/ (60*60)); 
        $minuts  = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60)/ 60); 
        $seconds = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24 - $days*60*60*24 - $hours*60*60 - $minuts*60)); 
    
        // printf("%d years, %d months, %d days, %d hours, %d minuts\n, %d seconds\n", $years, $months, $days, $hours, $minuts, $seconds); 
        printf("%d meseci, %dd, %dh, %dmin\n", $months, $days, $hours, $minuts); 
      ?>
      </th>
    </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>
