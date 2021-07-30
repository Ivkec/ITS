<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

$repID = mysqli_real_escape_string($conn, htmlspecialchars($_GET['repID']));
$location = mysqli_real_escape_string($conn, htmlspecialchars($_GET['loc']));

$sql = "SELECT daily_reports.*, users.*, daily_r_line.*, daily_r_loc.*, daily_r_pos.* FROM tits.daily_reports 
INNER JOIN tits.users ON daily_reports.id_card_tech = users.id
INNER JOIN tits.daily_r_line ON daily_r_line.id = daily_reports.id_line
INNER JOIN tits.daily_r_loc ON daily_r_loc.id = daily_reports.id_location
INNER JOIN tits.daily_r_pos ON daily_r_pos.id = daily_reports.id_position 
WHERE id_rep='$repID'";
$query = mysqli_query($conn, $sql);
while($res = mysqli_fetch_assoc($query)){
  $rep_id = $res['id_rep'];
  $comp_loc = $res['company_loc'];
  $loc = $res['loc'];
  $name = $res['name'];
  $surname = $res['surname'];
  $role = $res['role'];
  $line = $res['line'];
  $pos = $res['position'];
  $glitch = $res['glitch'];
  $sd =  $res['short_description'];
  $solved =  $res['solved'];
  $solved_on =  $res['solved_on'];
  $ls =  $res['line_stopped'];
  $s_rc = $res['stagnation_recorded'];
  $shift = $res['shift'];
  $date_cr = $res['date_created'];
  $date_ed = $res['date_edited'];

  $vreme_pocetkaZ = $res['time_pz'];
  $vreme_poziva = $res['time_call'];
  $vreme_pocetkaIT = $res['time_pIT'];
  $vreme_krajaIT = $res['time_kIT'];
  $vreme_krajaZ = $res['time_kz'];
  $vreme_tr_naloga = $res['time_tn'];
  $vreme_odziva = $res['time_response'];
  $vreme_radaIT = $res['time_workIT'];
  $dataEXPexcel = $res['data_inserted_toEXC'];
  $ME_contact = $res['ME_contacted'];
  $komentar = $res['comment'];
  $materijal = $res['materijal'];
}

?>


<div class="container" id="container">
<a href="admin_dnevniIzvestaji" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

<table class="table table-sm table-hover table-dark text-center table-shadow">
  <thead>
    <tr>
      <th scope="col" colspan="2" class="bg-dark"><h3 class="text-center">Prikaz IZVEŠTAJA iz <?php echo  $location; ?></h3></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>ID:</th>
      <th><?php echo $rep_id; ?></th>
    </tr>
    <tr>
      <th>IT Tehničar:</th>
      <th><?php echo roleBadge($role) .$name." ".$surname; ?></th>
    </tr>
    <tr>
      <th>Smena:</th>
      <th><?php echo $shift; ?></th>
    </tr>
    <tr>
      <th>NS Lokacija:</th>
      <th><?php echo $comp_loc; ?></th>
    </tr>
    <tr>
      <th>Lokacija:</th>
      <th><?php echo $loc; ?></th>
    </tr>
    <tr>
      <th>Linija:</th>
      <th><?php echo $line; ?></th>
    </tr>
    <tr>
      <th>Pozicija:</th>
      <th><?php echo $pos; ?></th>
    </tr>
    <tr>
      <th>Kvar:</th>
      <th><?php echo $glitch; ?></th>
    </tr>
    <tr>
      <th>Kratak opis:</th>
      <th><?php echo $sd; ?></th>
    </tr>
    <tr>
      <th>Rešeno?</th>
      <th><?php echo $solved; ?></th>
    </tr>
    <tr>
      <th>Rešeno na:</th>
      <th><?php echo $solved_on; ?></th>
    </tr>
    <tr>
      <th>Linija stala?</th>
      <th><?php echo $ls; ?></th>
    </tr>
    <tr>
      <th>Zastoj zapisan?</th>
      <th><?php echo $s_rc; ?></th>
    </tr>
      <th>Vreme početka zastoja:</th>
      <th><?php echo convertDateTime($vreme_pocetkaZ); ?></th>
    </tr>
    <tr>
      <th>Vreme poziva:</th>
      <th><?php echo convertDateTime($vreme_poziva); ?></th>
    </tr>
    <tr>
      <th>Vreme početka rada IT:</th>
      <th><?php echo convertDateTime($vreme_pocetkaIT); ?></th>
    </tr>
    <tr>
      <th>Vreme kraja rada IT:</th>
      <th><?php echo convertDateTime($vreme_krajaIT); ?></th>
    </tr>
    <tr>
      <th>Vreme kraja zastoja:</th>
      <th><?php echo convertDateTime($vreme_krajaZ);; ?></th>
    </tr>
    <tr>
      <th>Vreme trajanja naloga:</th>
      <th><?php echo $vreme_tr_naloga; ?></th>
    </tr>
    <tr>
      <th>Vreme odziva:</th>
      <th><?php echo $vreme_odziva; ?></th>
    </tr>
    <tr>
      <th>Vreme rada IT:</th>
      <th><?php echo $vreme_radaIT; ?></th>
    </tr>
    <?php if($dataEXPexcel != ""): ?>
    <tr>
      <th>Ako se radi VM da li su podaci unešeni u excel?</th>
      <th><?php echo $dataEXPexcel; ?></th>
    </tr>
    <?php endif; ?>
    <tr>
      <th>Kontaktiran ME?</th>
      <th><?php echo $ME_contact; ?></th>
    </tr>
    <tr>
      <th>Komentar:</th>
      <th><?php echo $komentar; ?></th>
    </tr>
    <?php if($materijal != ""): ?>
    <tr>
      <th>Nabavka materijala:</th>
      <th><?php echo $materijal; ?></th>
    </tr>
    <?php endif; ?>
  </tbody>
  <caption><?php 
  echo "Created: ".convertDateTime($date_cr)."<br>";

  if($date_ed != "0000-00-00 00:00:00"){
      echo "Last modified: ".convertDateTime($date_ed);
  }
  ?></caption>
</table>
</div>