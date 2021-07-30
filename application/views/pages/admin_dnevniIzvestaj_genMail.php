<?php 
require_once "db_conn.php";
require "scripts/sendMail_Izvestaji.php"; 

session_start();
error_reporting(0);

//Proracun smene na osnovu SOP etikete
$TempLogin = date('H:i:s');
if($TempLogin >= "06:00:00" && $TempLogin <= "14:30:00") $SHIFT = "1";
if($TempLogin >= "14:30:00" && $TempLogin <= "22:30:00") $SHIFT = "2"; 
if(($TempLogin >= "22:30:00" && $TempLogin <= "00:30:00") OR ($TempLogin >= "00:00:00" && $TempLogin <= "05:59:00")) $SHIFT = "3";


$date = date('Y-m-d');
$techID = $_SESSION['user_id'];

$kom = $_POST['kom'];
$tehnician = $_SESSION['ime']." ".$_SESSION['prezime'];

$mailSubject = "Dnevni Izveštaji - ".date('d.m.Y.')." Smena: $SHIFT";
$user_email = "ivan.funcik@aptiv.com"; //rsnvslocalitns@aptiv.com
$alertInfo = "Uspešno ste poslali mejl za dnevne izveštaje."; 
$redirect = "admin_dnevniIzvestaji";


$sql = "SELECT daily_reports.*, users.*, daily_r_line.*, daily_r_loc.*, daily_r_pos.* FROM tits.daily_reports 
INNER JOIN tits.users ON daily_reports.id_card_tech = users.id
INNER JOIN tits.daily_r_line ON daily_r_line.id = daily_reports.id_line
INNER JOIN tits.daily_r_loc ON daily_r_loc.id = daily_reports.id_location
INNER JOIN tits.daily_r_pos ON daily_r_pos.id = daily_reports.id_position 
WHERE date_created LIKE '$date%' AND shift='$SHIFT' AND id_card_tech='$techID'";

$query = mysqli_query($conn, $sql);

//IF EXIST, SHOW FORM, IF NOT< DO NOT SENT MAIL
$sql2 = "SELECT * FROM daily_reports WHERE date_created LIKE '$date%' AND shift='$SHIFT' AND id_card_tech='$techID'";
$query2 = mysqli_query($conn, $sql2);
?>


<div class="container" id="container">
<a href="admin_dnevniIzvestaji" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>
<?php if($res2 = mysqli_fetch_assoc($query2) > 0): ?>
     <div class="card card-main bg-secondary text-white border border-white">
      <h4 class="text-center">Generiši sve današnje izveštaje trenutne smene u mejl</h4>
      <p class="text-center">Trenutni datum: <?php echo date("d.m.Y"); ?>, Smena: <?php echo $SHIFT; ?></p><br>
         <form action="" method="POST" class="frm1" onsubmit="return confirm('Da li želite da generišete sve izveštaje u ovoj smeni u mail svim IT tehničarima? \nPotvrdite na OK dugme.');">
            <label>Unesi komentar (opciono):</label>
            <textarea name="kom" cols="20" rows="6" placeholder="Upisite svoj komentar.." class="form-control"></textarea><br>
            <button type="submit" name="btn_submit" class="btn btn-success button-s">Pošalji smenski izveštaj</button><br><br>
            <small>NAPOMENA/INFO: Pre nego što pošaljete izveštaje proverite podatke u tabelama ispod da li ste spravno uneli. <br>
            Ovaj mejl će biti poslat svim tehničarima.<br><i class='text-danger bg-white'>Posle isteka smene, imate još 30min da izgenerišete izveštaje za vašu smenu.</i></small>
         </form>
     </div>
<?php else: ?>
  <h3 class="text-center text-danger">Nemate još uvek ni jedan izveštaj u ovoj smeni za generisanje.</h3>
<?php endif; ?>
</div>

<?php 
$body = "<h1 style='text-align:center; color: #002080'>>> APTIV NS DNEVNI IZVEŠTAJI <<</h1><br>
<p>Pregled dnevnih izveštaja za datum $date, Smena: $SHIFT.</p>
<p>Ovaj mejl je generisao IT tehničar $tehnician.</p>

<h3>Komentar:</h3>
<h3>$kom</h3>
<br>
<center>

";

$body2 = "
<table style='border: 2px solid #002080; text-align:center; width: 85%; background: white;'>
     <tr style='background: #cce6ff'>
     <th style='border: 2px solid #002080;'>Izveštaj br.</th>
     <th style='border: 2px solid #002080;'>IT Tehničar</th>
     <th style='border: 2px solid #002080;'>Smena</th>
     <th style='border: 2px solid #002080;'>Lokacija</th>
     <th style='border: 2px solid #002080;'>Linija</th>
     <th style='border: 2px solid #002080;'>Pozicija</th>
     <th style='border: 2px solid #002080;'>Kvar</th>
     <th style='border: 2px solid #002080;'>Kratak opis</th>
     <th style='border: 2px solid #002080;'>Rešeno?</th>
     <th style='border: 2px solid #002080;'>Linija stajala?</th>
     <th style='border: 2px solid #002080;'>Zastoj zapisan?</th>
     <th style='border: 2px solid #002080;'>Vreme poziva</th>
     <th style='border: 2px solid #002080;'>Vreme odziva</th>
     <th style='border: 2px solid #002080;'>Vreme rada IT</th>
     <th style='border: 2px solid #002080;'>Vreme kraja zastoja</th>
</tr>";

$i = 0;

$body .= $body2;
$writeBody .= $body2;

while($res = mysqli_fetch_assoc($query)){
  $i++;

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

  $mailBody = "
    <tr>
         <th style='border: 2px solid #002080;'>$i.</th>
         <th style='border: 2px solid #002080;'>$name $surname</th>
         <th style='border: 2px solid #002080;'>$shift</th>
         <th style='border: 2px solid #002080;'>$loc</th>
         <th style='border: 2px solid #002080;'>$line</th>
         <th style='border: 2px solid #002080;'>$pos</th>
         <th style='border: 2px solid #002080;'>$glitch</th>
         <th style='border: 2px solid #002080;'>$sd</th>
         <th style='border: 2px solid #002080;'>$solved</th>
         <th style='border: 2px solid #002080;'>$ls</th>
         <th style='border: 2px solid #002080;'>$s_rc</th>
         <th style='border: 2px solid #002080;'>$vreme_poziva</th>
         <th style='border: 2px solid #002080;'>$vreme_odziva</th>
         <th style='border: 2px solid #002080;'>$vreme_radaIT</th>
         <th style='border: 2px solid #002080;'>$vreme_krajaZ</th>
    </tr>
    ";
    $body .= $mailBody;
    $writeBody .= $mailBody;

}
echo "<center>" .$writeBody. "</center>";

$footer = "
</table><br>
</center><br><br>

<hr>
<img src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.aptiv.com%2Fimages%2Fdefault-source%2Femail-campaigns%2Faptiv_logo_color_rgb.png&f=1&nofb=1' alt='APTIV' style='width: auto; height: 20px; float: right;'>
</div>
<br>";
$body = $body . $footer;

if(isset($_POST['btn_submit'])){
    sendMail($mailSubject, $body, $user_email, $alertInfo, $redirect);
}


?>
