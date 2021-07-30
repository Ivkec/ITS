<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

function validateDate($date, $format = 'Y-m-d\TH:i')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$loc = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_GET['loc'])));

$sql = "SELECT * FROM daily_r_loc WHERE loc_company='$loc';";
$query = mysqli_query($conn, $sql);
$sql2 = "SELECT * FROM daily_r_line WHERE loc_company='$loc';";
$query2 = mysqli_query($conn, $sql2);
$sql3 = "SELECT * FROM daily_r_pos WHERE loc_company='$loc';";
$query3 = mysqli_query($conn, $sql3);
$sql4 = "SELECT * FROM daily_r_glitch WHERE department='PE'";
$query4 = mysqli_query($conn, $sql4);
$sql5 = "SELECT * FROM daily_r_glitch WHERE department='IT'";
$query5 = mysqli_query($conn, $sql5);


//Proracun smene na osnovu SOP etikete
$TempLogin = date('H:i:s');
if($TempLogin >= "06:00:00" && $TempLogin <= "13:59:00") $SHIFT = "1";
if($TempLogin >= "14:00:00" && $TempLogin <= "21:59:00") $SHIFT = "2"; 
if(($TempLogin >= "22:00:00" && $TempLogin <= "23:59:00") OR ($TempLogin >= "00:00:00" && $TempLogin <= "05:59:00")) $SHIFT = "3";


$company_loc = trim(mysqli_real_escape_string($conn, htmlspecialchars($_GET['loc'])));
$id_card_tech = $_SESSION['user_id'];
$id_location = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['loc'])));
$id_line =  trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['line'])));
$id_position = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['pozicija'])));
$glitch = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['kvar'])));
$glitch_custom = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['kvar_rucno'])));
$solved = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['solved'])));
$solved_on = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['solved_on'])));
$line_stopped = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['line_stopped'])));
$stagnation_recorded = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['stagnation_recorded'])));
$short_desc = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['opis'])));
$komentar = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['komentar'])));
$dataEXPexcel = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['dataEXPexcel'])));
$ME_contact = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['ME_contact'])));
$materijal = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['materijal'])));

$datum_izvestaj = trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['datum_izvestaj'])));
$vreme_pocetkaZ = $datum_izvestaj."T".trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_pocetkaZ'])));
$vreme_poziva = $datum_izvestaj."T".trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_poziva'])));
$vreme_pocetkaIT = $datum_izvestaj."T".trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_pocetkaIT'])));
$vreme_krajaIT = $datum_izvestaj."T".trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_krajaIT'])));
$vreme_krajaZ = $datum_izvestaj."T".trim(mysqli_real_escape_string($conn, htmlspecialchars($_POST['vreme_krajaZ'])));

$time1 = new DateTime($vreme_pocetkaZ);
$time2 = new DateTime($vreme_krajaZ);
$vreme_tr_naloga = $time1->diff($time2);
$vreme_tr_naloga = $vreme_tr_naloga->format('%dd %hh %imin');

$time3 = new DateTime($vreme_poziva);
$time4 = new DateTime($vreme_pocetkaIT);
$vreme_odziva = $time3->diff($time4);
$vreme_odziva = $vreme_odziva->format('%dd %hh %imin');

$time5 = new DateTime($vreme_pocetkaIT);
$time6 = new DateTime($vreme_krajaIT);
$vreme_radaIT = $time5->diff($time6);
$vreme_radaIT = $vreme_radaIT->format('%dd %hh %imin');

if(isset($_POST['btn_insert'])){

  if($company_loc == "" OR $id_location == "" OR $id_line == "" OR $id_position == ""  OR $solved == "" OR $solved_on == "" OR $line_stopped == "" OR $stagnation_recorded == "" OR $short_desc == "" OR $komentar == "" OR  $ME_contact == "" OR $vreme_pocetkaZ == "" OR $vreme_poziva == "" OR $vreme_pocetkaIT == "" OR $vreme_krajaIT == "" OR $vreme_krajaZ == ""){
    $msg = "<h4 class='text-danger text-center text-h4'>Ni jedno polje ili check-box ne sme biti prazno!</h4>";
  }
  else if(!is_numeric($id_location)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos lokacije!</h4>";
  }
  else if(!is_numeric($id_line)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos linije!</h4>";
  }
  else if(!is_numeric($id_position)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos pozicije!</h4>";
  }
  else if($solved != "DA" AND $solved != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Reseno?'!</h4>";
  }
  else if($solved_on != "VNC" AND $solved_on != "LOKACIJA" AND $solved_on != "LOKACIJA+VNC"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Reseno na:'!</h4>";
  }
  else if($line_stopped != "DA" AND $line_stopped != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Linija stajala?'!</h4>";
  }
  else if($stagnation_recorded != "DA" AND $stagnation_recorded != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Zastoj zapisan?'!</h4>";
  }
  else if($ME_contact != "DA" AND $ME_contact != "NE"){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'Da li je kontaktiran ME?'!</h4>";
  }
  else if(!validateDate($vreme_pocetkaZ)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme pocetka zastoja!</h4>";
  }
  else if(!validateDate($vreme_poziva)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme poziva!</h4>";
  }
  else if(!validateDate($vreme_pocetkaIT)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme pocetka IT!</h4>";
  }
  else if(!validateDate($vreme_krajaIT)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme kraja IT!</h4>";
  }
  else if(!validateDate($vreme_krajaZ)){
    $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv format datuma za polje: vreme kraja zastoja!</h4>";
  }
  else{
    if($glitch == ""){
      $glitch = $glitch_custom;
    }
    if($id_position == 14 OR $id_position == 29){ //ID-ovi od VISION za NS1 i NS2
      if($dataEXPexcel == ""){
        $msg = "<h4 class='text-danger text-center text-h4'>Polje za VM niste cekirali!</h4>";
      }
      else if($dataEXPexcel != "DA" AND $dataEXPexcel != "NE"){
         $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos polja 'za VM'!</h4>";
       }
       else{
         goto insertInDB;
       }
     }
     else{
        insertInDB:
        $sql_insert = "INSERT INTO tits.daily_reports (company_loc, id_card_tech, id_location, id_line, id_position, glitch, solved, solved_on, line_stopped, stagnation_recorded, short_description, shift, time_pz, time_call, time_pIT, time_kIT, time_kz, time_tn,  time_response, time_workIT, data_inserted_toEXC, ME_contacted, comment, materijal) 
        VALUES ('$company_loc','$id_card_tech', '$id_location','$id_line','$id_position','$glitch','$solved','$solved_on','$line_stopped','$stagnation_recorded','$short_desc', '$SHIFT', '$vreme_pocetkaZ', '$vreme_poziva', '$vreme_pocetkaIT', '$vreme_krajaIT', '$vreme_krajaZ', '$vreme_tr_naloga', '$vreme_odziva', '$vreme_radaIT', '$dataEXPexcel', '$ME_contact', '$komentar', '$materijal');";
        $query_insert = mysqli_query($conn, $sql_insert) OR die("Data inserted failed, connection error :(");
        mysqli_close($conn);
        
        echo "<script>
              swal({
                title: 'Izveštaji ALERT',
                text: 'Ušpesno ste napravili izveštaj.',
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


$dep_IT_PE = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dep_IT_PE']));
$dodaj_kvar = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dodaj_kvar']));

if(isset($_POST['btn_dodajKvar'])){

  if($_POST['dodaj_kvar'] = ""){
    echo "<script>
          swal({
            title: 'Izveštaji ALERT',
            text: 'Polje za unos kvara je prazno.',
            icon: 'error',
            button: 'OK',
          })
          .then((value) => {
            location='admin_dnevniIzvestaji_novi?loc=$loc';
          });
          </script>";
  }
  else{

    $sqlupd = "INSERT INTO tits.daily_r_glitch (glitch, department) VALUES ('$dodaj_kvar', '$dep_IT_PE');";
    $queryupd = mysqli_query($conn, $sqlupd) OR die("Error: neuspesno povezivanje sa bazom :(");
    echo "<script>
          swal({
            title: 'Izveštaji ALERT',
            text: 'Ušpesno ažuriranje liste.',
            timer: 1500,
            icon: 'success',
            button: 'OK',
          })
          .then((value) => {
            location='admin_dnevniIzvestaji_novi?loc=$loc';
          });
          </script>";
  }
}

$dodaj_poz = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dodaj_poz']));

if(isset($_POST['btn_dodajPoz'])){

  if($_POST['dodaj_poz'] = ""){
    echo "<script>
          swal({
            title: 'Izveštaji ALERT',
            text: 'Polje za unos kvara je prazno.',
            icon: 'error',
            button: 'OK',
          })
          .then((value) => {
            location='admin_dnevniIzvestaji_novi?loc=$loc';
          });
          </script>";
  }
  else{

    $sqlupd_pos = "INSERT INTO tits.daily_r_pos (`loc_company`, `position`) VALUES ('$loc', '$dodaj_poz');";
    $queryupd_pos = mysqli_query($conn, $sqlupd_pos) OR die("Error: neuspesno povezivanje sa bazom :(");
    echo "<script>
          swal({
            title: 'Izveštaji ALERT',
            text: 'Ušpesno ažuriranje liste.',
            timer: 1500,
            icon: 'success',
            button: 'OK',
          })
          .then((value) => {
            location='admin_dnevniIzvestaji_novi?loc=$loc';
          });
          </script>";
  }
}
?>

<div class="container-fluid">
<a href="admin_dnevniIzvestaji" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

            <div class="card card-main bg-dark text-white border border-white">
              <h3 class="text-center h3f"><?php echo $_GET['loc']; ?> - KREIRANJE NOVOG IZVEŠTAJA</h3>
              <?php echo $msg; ?>
         <form class="frm1" action="/ITS/admin_dnevniIzvestaji_novi?loc=<?php echo $loc; ?>" method="POST" onsubmit="return confirm('Da li želite da pošaljete ovaj izvestaj? \nPotvrdite na OK dugme.');">
             <div class="row">
             <div class="col-lg-4">
                 <div class="form-group">
                      <label>IT Tehničar:</label>
                      <input type="text" class="form-control text-primary" value="<?php echo $_SESSION['ime']." ".$_SESSION['prezime']; ?>" disabled>
                 </div>
                  <div class="form-group">
                      <label>Lokacija:</label>
                      <select class="form-control" id="sel1" name="loc" required>
                        <?php while($res = mysqli_fetch_assoc($query)): 
                          ?>
                          <option value="<?php echo $res['id']; ?>"><?php echo $res['loc']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      </div>
                      <div class="form-group">
                      <label>Linija:</label>
                      <select class="form-control" id="sel2" name="line" required>
                        <?php while($res2 = mysqli_fetch_assoc($query2)): ?>
                          <option value="<?php echo $res2['id']; ?>"><?php echo $res2['line']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      </div>
                      <div class="form-group">
                      <label>Pozicija:</label>
                      <select class="form-control" id="sel3" name="pozicija" required onchange="VMdisable();" onload="VMdisable();">
                        <?php while($res3 = mysqli_fetch_assoc($query3)): ?>
                          <option value="<?php echo $res3['id']; ?>"><?php echo $res3['position']; ?></option>
                        <?php endwhile; ?>
                      </select>
                      <small><a class="btn btn-primary btn-sm" id="myBtn2">Ažuriraj listu</a></small>

                    </div>
                    <div class="form-group">
                      <label>Kvar (Opis):</label>
                      <select class="form-control" id="sel4" name="kvar" required onchange="if(this.options[this.selectedIndex].value=='OSTALO'){
                          toggleField(this,this.nextSibling);
                          this.selectedIndex='0';
                      }">
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
                           </select><input type="text" class="form-control" name="kvar_rucno" style="display:none;" disabled="disabled" placeholder="Unesi ručno.."
                             onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
                      </optgroup>
                      </select>
                      <small><a class="btn btn-primary btn-sm" id="myBtn">Ažuriraj listu</a></small>

                    </div>
                    
                    <div class="form-group">
                      <label>Način rešavanja:</label>
                      <textarea name="opis" class="form-control" placeholder="Opis.." rows="3" required></textarea>
                    </div>
             </div>
             <div class="col-lg-4"><br>
                 <div class="form-group">
                      <b>REŠENO?</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio" name="solved" value="DA" required>
                         <label class="custom-control-label" for="customRadio">DA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio2" name="solved" value="NE" required>
                         <label class="custom-control-label" for="customRadio2">NE</label>
                       </div>      
                   </div>
                   <div class="form-group"><br>
                      <b>REŠENO NA:</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio3" name="solved_on" value="LOKACIJA" required>
                         <label class="custom-control-label" for="customRadio3">LOKACIJA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio4" name="solved_on" value="VNC" required>
                         <label class="custom-control-label" for="customRadio4">VNC</label>
                       </div> 
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio5" name="solved_on" value="LOKACIJA+VNC" required>
                         <label class="custom-control-label" for="customRadio5">LOKACIJA+VNC</label>
                       </div>        
                   </div>
                   <div class="form-group"><br>
                      <b>LINIJA STAJALA?</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio6" name="line_stopped" value="DA" required>
                         <label class="custom-control-label" for="customRadio6">DA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio7" name="line_stopped" value="NE" required>
                         <label class="custom-control-label" for="customRadio7">NE</label>
                       </div>      
                   </div>
                   <div class="form-group"><br>
                      <b>ZASTOJ ZAPISAN?</b><br>
                      <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio8" name="stagnation_recorded" value="DA" required>
                         <label class="custom-control-label" for="customRadio8">DA</label>
                       </div>
                       <div class="custom-control custom-radio custom-control-inline">
                         <input type="radio" class="custom-control-input" id="customRadio9" name="stagnation_recorded" value="NE" required>
                         <label class="custom-control-label" for="customRadio9">NE</label>
                       </div><br><br>
                       <div class="form-group">
                           <label>Datum:</label>
                           <input type="date" name="datum_izvestaj" class="form-control" required">
                       </div>
                       <div class="form-group">
                           <label>Vreme početka zastoja:</label>
                           <input type="time" name="vreme_pocetkaZ" class="form-control" required">
                       </div>
                       <div class="form-group">
                           <label>Vreme poziva:</label> 
                           <input type="time" name="vreme_poziva" class="form-control" required>
                       </div>
                          <br>
                          <button type="submit" name="btn_insert" class="btn btn-success btn-main btn-insert form-control button-s">Kreiraj Novi Izveštaj &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button>
                       </div>
                    </div>
                    <div class="col-lg-4"><br>
                        <div class="form-group">
                                  <label>Vreme početka rada IT:</label>
                                  <input type="time" name="vreme_pocetkaIT" class="form-control" required>
                        </div>
                        <div class="form-group">
                                  <label>Vreme kraja rada IT:</label>
                                  <input type="time" name="vreme_krajaIT" class="form-control" required>
                        </div>
                        <div class="form-group">
                                  <label>Vreme kraja zastoja:</label>
                                  <input type="time" name="vreme_krajaZ" class="form-control" required>
                        </div>
                        <div class="form-group">
                           <b style='color:gray;' id='VMtext'>Ako se radi VM da li su podaci unešeni u excel?</b><br>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio10" name="dataEXPexcel" value="DA" required disabled> 
                              <label class="custom-control-label" for="customRadio10">DA</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio11" name="dataEXPexcel" value="NE" required disabled>
                              <label class="custom-control-label" for="customRadio11">NE</label>
                            </div>      
                        </div>
                        <div class="form-group">
                           <b>Da li je kontaktiran ME?</b><br>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio12" name="ME_contact" value="DA" required>
                              <label class="custom-control-label" for="customRadio12">DA</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio13" name="ME_contact" value="NE" required>
                              <label class="custom-control-label" for="customRadio13">NE</label>
                            </div>      
                        </div>
                        <div class="form-group">
                          <label>Komentar:</label>
                          <textarea name="komentar" class="form-control" placeholder="Upišite komentar.." rows="2" required></textarea>
                        </div>
                        <div class="form-group">
                          <label>Nabavka materijala: <small>(OPCIONO)</small></label>
                          <textarea name="materijal" class="form-control" placeholder="Ako je potrebno upišite koji materijal treba da se poruči.." rows="2"></textarea>
                        </div>
                   </div>
             </div>

         </form>
          <!-- The Modal -->
   <div id="Modal" class="modal">
          <!-- Modal content -->
          <div class="modal-content border-primary">
            <span class="close">&times;</span>
               <form class="frm1 text-primary" action="" method="POST" onsubmit="return confirm('Da li želite da ažurirate ove podatke?');">
                      <h5>Ažuriraj Listu Kvarova</h5>
                      <?php $msg; ?>
                      <div class="form-group"><label>Departman: &nbsp;</label>
                           <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio98" name="dep_IT_PE" value="IT" required checked>
                              <label class="custom-control-label" for="customRadio98">IT</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                              <input type="radio" class="custom-control-input" id="customRadio99" name="dep_IT_PE" value="PE" required>
                              <label class="custom-control-label" for="customRadio99">PE</label>
                      </div>   <br>
                      <label class="text-dark">Dodaj novi kvar:</label>
                      <input type="text" class="form-control border-primary" name="dodaj_kvar" placeholder="Upišite šta dodajete u listu.." rows="4" style="border-radius: 5px 5px 0 0" autocomplete="off">
                      <button type="submit" name="btn_dodajKvar" class="btn btn-primary form-control" style="border-radius: 0 0 5px 5px">Ažuriraj listu</button>
                </form>
       </div>
</div>

    </div>       
      <!-- The Modal -->
   <div id="Modal2" class="modal">
          <!-- Modal content -->
          <div class="modal-content border-primary">
            <span class="close">&times;</span>
               <form class="frm1 text-primary" action="" method="POST" onsubmit="return confirm('Da li želite da ažurirate ove podatke?');">
                      <h5>Ažuriraj Listu Pozicija u <?php echo $loc; ?></h5>
                      <?php $msg; ?>
                      
                      <label class="text-dark">Dodaj novu poziciju na lokaciji <?php echo $loc; ?>:</label>
                      <input type="text" class="form-control border-primary" name="dodaj_poz" placeholder="Upišite šta dodajete u listu.." rows="4" style="border-radius: 5px 5px 0 0" autocomplete="off">
                      <button type="submit" name="btn_dodajPoz" class="btn btn-primary form-control" style="border-radius: 0 0 5px 5px">Ažuriraj listu</button>
                </form>
       </div>
</div>

    </div>          
</div>

<!-- DISABLE VM input -->
<script src="JS/disableVM.js"></script>
<!-- POPUP ZA RUCNI UNOS PODATAKA -->
<script src="JS/btnModal.js"></script>
<!-- ZA RUCNO UNOSENJE PRI ODABIRU U PADAJUCEM MENIJU "OSTALO" -->
<script src="JS/toggleField.js"></script>
