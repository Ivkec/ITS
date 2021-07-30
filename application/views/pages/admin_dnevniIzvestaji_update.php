<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

function dateInputConvert($date){
  $vpZ_d = substr($date, 0, 10);
  $vpZ_h = substr($date, 11, 5);
  $format = $vpZ_d."T".$vpZ_h;
  return $format;
}

function validateDate($date, $format = 'Y-m-d\TH:i')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}


$repID = mysqli_real_escape_string($conn, htmlspecialchars($_GET['repID']));
$loc = mysqli_real_escape_string($conn, htmlspecialchars($_GET['loc']));

//prikaz podataka sa baze
$sql = "SELECT * FROM daily_r_loc WHERE loc_company='$loc';";
$query = mysqli_query($conn, $sql);
$sql2 = "SELECT * FROM daily_r_line WHERE loc_company='$loc';";
$query2 = mysqli_query($conn, $sql2);
$sql3 = "SELECT * FROM daily_r_pos WHERE loc_company='$loc';";
$query3 = mysqli_query($conn, $sql3);
$sql4 = "SELECT * FROM daily_r_glitch WHERE department='IT'";
$query4 = mysqli_query($conn, $sql4);
$sql5 = "SELECT * FROM daily_r_glitch WHERE department='PE'";
$query5 = mysqli_query($conn, $sql5);

//prikaz unetih podataka kao selected
$sqlrep = "SELECT daily_reports.*, users.*, daily_r_line.*, daily_r_loc.*, daily_r_pos.* FROM tits.daily_reports 
INNER JOIN tits.users ON daily_reports.id_card_tech = users.id
INNER JOIN tits.daily_r_line ON daily_r_line.id = daily_reports.id_line
INNER JOIN tits.daily_r_loc ON daily_r_loc.id = daily_reports.id_location
INNER JOIN tits.daily_r_pos ON daily_r_pos.id = daily_reports.id_position 
WHERE id_rep='$repID'";

//izlistavanje unetih podataka
$querysqlrep = mysqli_query($conn, $sqlrep);
while($row = mysqli_fetch_assoc($querysqlrep)){
  $imeT = $row['name'];
  $prezimeT = $row['surname'];
  $id_loc = $row['id_location'];
  $location = $row['loc'];
  $id_line = $row['id_line'];
  $line = $row['line'];
  $id_position = $row['id_position'];
  $position = $row['position'];
  $glitch = $row['glitch'];
  $short_description = $row['short_description'];
  $solved = $row['solved'];
  $solved_on = $row['solved_on'];
  $line_stopped = $row['line_stopped'];
  $stagnation_recorded = $row['stagnation_recorded'];
  $komentar = $row['comment'];
  $dataEXPexcel = $row['data_inserted_toEXC'];
  $ME_contact = $row['ME_contacted'];
  $materijal = $row['materijal'];
  
  $vreme_pocetkaZ =  $row['time_pz'];
  $vreme_poziva =  $row['time_call'];
  $vreme_pocetkaIT =  $row['time_pIT'];
  $vreme_krajaIT =  $row['time_kIT'];
  $vreme_krajaZ =  $row['time_kz'];

}


$company_loca = trim(mysqli_real_escape_string($conn, htmlspecialchars($_GET['loc'])));
$id_card_teca = $_SESSION['user_id']; 
$id_locationa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['loc'])));
$id_linea = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['line'])));
$id_positiona = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['pozicija'])));
$glitcha = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['kvar'])));
$glitch_customa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['kvar_rucno'])));
$solveda = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['solved'])));
$solved_ona = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['solved_on'])));
$line_stoppeda = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['line_stopped'])));
$stagnation_recordeda = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['stagnation_recorded'])));
$short_desca = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['opis'])));
$vreme_pocetkaZa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_pocetkaZ'])));
$vreme_pozivaa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_poziva'])));
$vreme_pocetkaITa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_pocetkaIT'])));
$vreme_krajaITa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_krajaIT'])));
$vreme_krajaZa = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_krajaZ'])));
$dataEXPexcela = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['dataEXPexcel'])));
$ME_contacta = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['ME_contact'])));
$komentara = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['komentar'])));
$materijala = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['materijal'])));





$time1 = new DateTime($vreme_pocetkaZa);
$time2 = new DateTime($vreme_krajaZa);
$vreme_tr_naloga = $time1->diff($time2);
$vreme_tr_naloga = $vreme_tr_naloga->format('%dd %hh %imin');

$time3 = new DateTime($vreme_pozivaa);
$time4 = new DateTime($vreme_pocetkaITa);
$vreme_odziva = $time3->diff($time4);
$vreme_odziva = $vreme_odziva->format('%dd %hh %imin');

$time5 = new DateTime($vreme_pocetkaITa);
$time6 = new DateTime($vreme_krajaITa);
$vreme_radaIT = $time5->diff($time6);
$vreme_radaIT = $vreme_radaIT->format('%dd %hh %imin');



//ako je kliknut btn_insert
if(isset($_POST['btn_insert'])){

  if($company_loca == "" OR $id_locationa == "" OR $id_linea == "" OR $id_positiona == ""  OR $solveda == "" OR $solved_ona == "" OR $line_stoppeda == "" OR $stagnation_recordeda == "" OR $short_desca == "" OR $komentara == "" OR  $ME_contacta == "" OR $vreme_pocetkaZa == "" OR $vreme_pozivaa == "" OR $vreme_pocetkaITa == "" OR $vreme_krajaITa == "" OR $vreme_krajaZa == ""){
    $msg = "<h4 class='text-danger text-center text-h4'>Ni jedno polje ili check-box ne sme biti prazno!</h4>";
  }
  else if(!is_numeric($id_locationa)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos lokacije!</h4>";
  }
  else if(!is_numeric($id_linea)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos linije!</h4>";
  }
  else if(!is_numeric($id_positiona)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos pozicije!</h4>";
  }
  else if($solveda != "DA" AND $solveda != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Reseno?'!</h4>";
  }
  else if($solved_ona != "VNC" AND $solved_ona != "LOKACIJA" AND $solved_ona != "LOKACIJA+VNC"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Reseno na:'!</h4>";
  }
  else if($line_stoppeda != "DA" AND $line_stoppeda != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Linija stajala?'!</h4>";
  }
  else if($stagnation_recordeda != "DA" AND $stagnation_recordeda != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Zastoj zapisan?'!</h4>";
  }
  else if($ME_contacta != "DA" AND $ME_contacta != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Da li je kontaktiran ME?'!</h4>";
  }
  else if(!validateDate($vreme_pocetkaZa)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme pocetka zastoja!</h4>";
  }
  else if(!validateDate($vreme_pozivaa)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme poziva!</h4>";
  }
  else if(!validateDate($vreme_pocetkaITa)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme pocetka IT!</h4>";
  }
  else if(!validateDate($vreme_krajaITa)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme kraja IT!</h4>";
  }
  else if(!validateDate($vreme_krajaZa)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme kraja zastoja!</h4>";
  }
  else{

     if($glitcha == ""){
      $glitcha = $glitch_customa;
     }
     if($id_positiona == 14 OR $id_positiona == 29){ //ID-ovi od VISION za NS1 i NS2
       if($dataEXPexcela == ""){
         $msg = "<h4 class='text-danger text-center text-h4'>Polje za VM niste cekirali!</h4>";
       }
       else if($dataEXPexcela != "DA" AND $dataEXPexcela != "NE"){
          $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'za VM'!</h4>";
        }
        else{
          goto updateInDB;
        }
      }
      else{
         updateInDB:
         $date_up = date('Y-m-d H:i:s');
         $sql = "UPDATE tits.daily_reports SET id_location='$id_locationa', id_line='$id_linea', id_position='$id_positiona', glitch='$glitcha', solved='$solveda', solved_on='$solved_ona', line_stopped='$line_stoppeda', stagnation_recorded='$stagnation_recordeda', short_description='$short_desca', date_edited='$date_up', time_pz='$vreme_pocetkaZa', time_call='$vreme_pozivaa', time_pIT='$vreme_pocetkaITa', time_kIT='$vreme_krajaITa', time_kz='$vreme_krajaZa', time_tn='$vreme_tr_naloga', time_response='$vreme_odziva', time_workIT='$vreme_radaIT', data_inserted_toEXC='$dataEXPexcela', ME_contacted='$ME_contacta', comment='$komentara', materijal='$materijala' WHERE id_rep='$repID';";
         $query = mysqli_query($conn, $sql) OR die("Data inserted failed, connection error :(");
     
         echo "<script>
               swal({
                 title: 'Izveštaji ALERT',
                 text: 'Ušpesno ste ažurirali izveštaj.',
                 timer: 1500,
                 icon: 'success',
                 button: 'OK',
               })
               .then((value) => {
                 location='admin_dnevniIzvestaji';
               });
               </script>";
       }
  }
}

//automatsko popunjavanje radio buttona
switch($solved)
{
   case "DA":
    $checked_da = "checked";
   break;
   case "NE":
   $checked_ne = "checked";
   break;
}
switch($solved_on)
{
   case "LOKACIJA":
    $checked_loc = "checked";
   break;
   case "VNC":
   $checked_vnc = "checked";
   break;
   case "LOKACIJA+VNC":
    $checked_locPLUSvnc = "checked";
}
switch($line_stopped)
{
   case "DA":
    $checked_lsda = "checked";
   break;
   case "NE":
   $checked_lsne = "checked";
   break;
}
switch($stagnation_recorded)
{
   case "DA":
    $checked_srda = "checked";
   break;
   case "NE":
   $checked_srne = "checked";
   break;
}
switch($dataEXPexcel)
{
   case "DA":
    $checked_dataEXPexcelda = "checked";
   break;
   case "NE":
   $checked_dataEXPexcelne = "checked";
   break;
}

switch($ME_contact)
{
   case "DA":
    $ME_contact_da = "checked";
   break;
   case "NE":
   $ME_contact_ne = "checked";
   break;
}

?>

<div class="container-fluid">
<a href="admin_dnevniIzvestaji" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

           
            <div class="card card-main bg-dark text-white border border-white">
              <h3 class="text-center h3f"><?php echo $_GET['loc']; ?> - AŽURIRANJE IZVEŠTAJA</h3>
         <form class="frm1" action="/ITS/admin_dnevniIzvestaji_update?repID=<?php echo $repID; ?>&loc=<?php echo $_GET['loc']; ?>" method="POST" onsubmit="return confirm('Da li želite da ažurirate ovaj izvestaj? \nPotvrdite na OK dugme.');">
             <?php echo $msg; ?>
             <div class="row">
                <div class="col-lg-4">
                 <div class="form-group">
                      <label>IT Tehničar:</label>
                      <input type="text" class="form-control text-primary" value="<?php echo $imeT." ".$prezimeT; ?>" disabled>
                  </div>
                  <div class="form-group">
                      <label>Lokacija:</label>
                      <select class="form-control" id="sel1" name="loc" required>
                        <?php while($res = mysqli_fetch_assoc($query)): ?>
                          <option value="<?php echo $res['id']; ?>"><?php echo $res['loc']; ?></option>
                        <?php endwhile; ?>
                        <option value="<?php echo $id_loc; ?>" selected><?php echo $location; ?></option>
                      </select>
                      </div>
                      <div class="form-group">
                      <label>Linija:</label>
                      <select class="form-control" id="sel2" name="line" required>
                        <?php while($res2 = mysqli_fetch_assoc($query2)): ?>
                          <option value="<?php echo $res2['id']; ?>"><?php echo $res2['line']; ?></option>
                        <?php endwhile; ?>
                        <option value="<?php echo $id_line; ?>" selected><?php echo $line; ?></option>
                      </select>
                      </div>
                      <div class="form-group">
                      <label>Pozicija:</label>
                      <select class="form-control" id="sel3" name="pozicija" required onchange="VMdisable();">
                        <?php while($res3 = mysqli_fetch_assoc($query3)): ?>
                          <option value="<?php echo $res3['id']; ?>"><?php echo $res3['position']; ?></option>
                        <?php endwhile; ?>
                        <option value="<?php echo $id_position; ?>" selected><?php echo $position; ?></option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label>Kvar (Opis):</label>
                      <select class="form-control" id="sel4" name="kvar" required onchange="if(this.options[this.selectedIndex].value=='OSTALO'){
                          toggleField(this,this.nextSibling);
                          this.selectedIndex='0';}">
                      <optgroup label="IT:">
                      <?php while($res5 = mysqli_fetch_assoc($query5)): ?>
                          <option value="<?php echo $res5['glitch']; ?>"><?php echo $res5['glitch']; ?></option>
                        <?php endwhile; ?>
                      </optgroup>
                      <optgroup label="PE:">
                      <?php while($res4 = mysqli_fetch_assoc($query4)): ?>
                          <option value="<?php echo $res4['glitch']; ?>"><?php echo $res4['glitch']; ?></option>
                        <?php endwhile; ?>
                      </optgroup>
                      <optgroup label="PE/IT:">
                           <option value="OSTALO">OSTALO</option>
                           <option value="<?php echo $glitch; ?>" selected><?php echo $glitch; ?></option>
                           <input type="text" class="form-control" name="kvar_rucno" style="display:none;" disabled="disabled" placeholder="Unesi ručno.."
                             onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
                      </optgroup>
                     
                      </select>
                    </div>
                    
                    <div class="form-group">
                      <label>Način rešavanja:</label>
                      <textarea name="opis" class="form-control" placeholder="Opis.." rows="3" required><?php echo $short_description; ?></textarea>
                    </div>
             </div>
             <div class="col-lg-4"><br>
                 <div class="form-group">
                      <b>REŠENO?</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio" name="solved" value="DA" required <?php echo $checked_da; ?>>
                         <label class="custom-control-label" for="customRadio">DA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio2" name="solved" value="NE" required <?php echo $checked_ne; ?>>
                         <label class="custom-control-label" for="customRadio2">NE</label>
                       </div>      
                   </div>
                   <div class="form-group"><br>
                      <b>REŠENO NA:</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio3" name="solved_on" value="LOKACIJA" required <?php echo $checked_loc; ?>>
                         <label class="custom-control-label" for="customRadio3">LOKACIJA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio4" name="solved_on" value="VNC" required <?php echo $checked_vnc; ?>>
                         <label class="custom-control-label" for="customRadio4">VNC</label>
                       </div> 
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio5" name="solved_on" value="LOKACIJA+VNC" required <?php echo $checked_locPLUSvnc; ?>>
                         <label class="custom-control-label" for="customRadio5">LOKACIJA+VNC</label>
                       </div>        
                   </div>
                   <div class="form-group"><br>
                      <b>LINIJA STAJALA?</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio6" name="line_stopped" value="DA" required <?php echo $checked_lsda; ?>>
                         <label class="custom-control-label" for="customRadio6">DA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio7" name="line_stopped" value="NE" required <?php echo $checked_lsne; ?>>
                         <label class="custom-control-label" for="customRadio7">NE</label>
                       </div>      
                   </div>
                   <div class="form-group"><br>
                      <b>ZASTOJ ZAPISAN?</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio8" name="stagnation_recorded" value="DA" required <?php echo $checked_srda; ?>>
                         <label class="custom-control-label" for="customRadio8">DA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio9" name="stagnation_recorded" value="NE" required <?php echo $checked_srne; ?>>
                         <label class="custom-control-label" for="customRadio9">NE</label>
                       </div>
                   </div>
                   <div class="form-group">
                           <label>Vreme početka zastoja:</label>
                           <input type="datetime-local" name="vreme_pocetkaZ" class="form-control" value="<?php echo dateInputConvert($vreme_pocetkaZ); ?>" required>
                       </div>
                       <div class="form-group">
                           <label>Vreme poziva:</label> 
                           <input type="datetime-local" name="vreme_poziva" class="form-control" value="<?php echo dateInputConvert($vreme_poziva); ?>" required>
                       </div>
                          <br>
                          <button type="submit" name="btn_insert" class="btn btn-success form-control btn-main btn-insert button-s">Ažuriraj Izveštaj &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button>
                       </div>
             <div class="col-lg-4"><br>
                        <div class="form-group">
                                  <label>Vreme početka rada IT:</label>
                                  <input type="datetime-local" name="vreme_pocetkaIT" class="form-control" value="<?php echo dateInputConvert($vreme_pocetkaIT); ?>" required>
                        </div>
                        <div class="form-group">
                                  <label>Vreme kraja rada IT:</label>
                                  <input type="datetime-local" name="vreme_krajaIT" class="form-control" value="<?php echo dateInputConvert($vreme_krajaIT); ?>" required>
                        </div>
                        <div class="form-group">
                                  <label>Vreme kraja zastoja:</label>
                                  <input type="datetime-local" name="vreme_krajaZ" class="form-control" value="<?php echo dateInputConvert($vreme_krajaZ); ?>" required>
                        </div>
                        <div class="form-group">
                           <b id="VMtext" style="color:gray;">Ako se radi VM da li su podaci unešeni u excel?</b><br>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio10" name="dataEXPexcel" value="DA" <?php echo $checked_dataEXPexcelda; ?>>
                              <label class="custom-control-label" for="customRadio10">DA</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio11" name="dataEXPexcel" value="NE" <?php echo $checked_dataEXPexcelne; ?>>
                              <label class="custom-control-label" for="customRadio11">NE</label>
                            </div>      
                        </div>
                        <div class="form-group">
                           <b>Da li je kontaktiran ME?</b><br>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio12" name="ME_contact" value="DA" <?php echo $ME_contact_da; ?> required>
                              <label class="custom-control-label" for="customRadio12">DA</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio13" name="ME_contact" value="NE" <?php echo $ME_contact_ne; ?> required>
                              <label class="custom-control-label" for="customRadio13">NE</label>
                            </div>      
                        </div>
                        <div class="form-group">
                          <label>Komentar:</label>
                          <textarea name="komentar" class="form-control" placeholder="Upišite komentar.." rows="2" required><?php echo $komentar; ?></textarea>
                        </div>
                        <div class="form-group">
                          <label>Nabavka materijala: <small>(OPCIONO)</small></label>
                          <textarea name="materijal" class="form-control" placeholder="Ako je potrebno upisite koji materijal treba da se poruci.." rows="2"><?php echo $materijal; ?></textarea>
                        </div>
                   </div>
              
             </div>
              

               
               

           
         </form>
       
    </div>          
</div>

<!-- DISABLE VM input -->
<script src="JS/disableVM.js"></script>
<!-- ZA RUCNO UNOSENJE PRI ODABIRU U PADAJUCEM MENIJU "OSTALO" -->
<script src="JS/toggleField.js"></script>

