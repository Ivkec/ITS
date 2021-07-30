
<?php 
require_once "db_conn.php";
require "scripts/sendMail.php";
session_start();
error_reporting(0);

$ime = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['name']))));
$prezime = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['surname']))));
$email =  strtolower(trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['email'])))));
$departman = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['dep']))));
$lokacija = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['location']))));
$vrsta_tiketa = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['type_tic']))));
$tip_problema = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['type_prob']))));
$opis = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['description']))));
$status = $_SESSION['status'];
//count tickets
$sql = "SELECT count(*) FROM tits.tickets";
$result = mysqli_query($conn, $sql);
$res =  mysqli_fetch_array($result);
$tic_number = $res[0];
$tic_number++;
$num =  $res[0];
$num++;


if($vrsta_tiketa == "INCIDENT"){
  $tic_number = "INC".$tic_number;  //INC100
}
else{
  $tic_number = "REQ".$tic_number;  //REQ100
}

$sql2 = "SELECT * FROM tickets WHERE tic_number LIKE '%$num'";
$result2 = mysqli_query($conn, $sql2);

if($res2 =  mysqli_fetch_array($result2) > 0){
  //count tickets
   $sql3 = "SELECT count(*) FROM tits.tickets";
   $result3 = mysqli_query($conn, $sql3);
   $res3 =  mysqli_fetch_array($result3);
   $tic_number = $res2[0];
   $tic_number = $tic_number + 2;
  
   if($vrsta_tiketa == "INCIDENT"){
    $tic_number = "INC".$tic_number;  //INC100
  }
  else{
    $tic_number = "REQ".$tic_number;  //REQ100
  }

   //insert data in DB
   goto insert;
}
else{

  insert:
  //insert data in DB
  if(isset($_POST['btn_insert'])){
  
    if($ime == "" AND $prezime == "" AND $email == "" AND $opis == ""){
      $POST['location'] = $lokacija;
      echo " <script>
             swal({
              title: 'Ticketing System',
              text: 'Niste popunili sva polja!',
              icon: 'error',
              button: 'OK',
            });
            </script>";
            $msg = "<h4 class='text-danger text-center text-h4'>Niste popunili sva polja!</h4>";
    }
    else if(preg_match('/[^a-zA-Z]/',$ime) OR preg_match('/[^a-zA-Z]/',$prezime)){
      echo " <script>
             swal({
              title: 'Ticketing System',
              text: 'Ime i prezime mora da sadrži slova engleske abecede.',
              icon: 'error',
              button: 'OK',
            });
            </script>";
      $msg = "<h4 class='text-danger text-center text-h4'>Ime i prezime mora da sadrži slova engleske abecede.</h4>";
    }
    else if(strlen($ime) < 3 OR strlen($prezime) < 3){
      echo " <script>
             swal({
              title: 'Ticketing System',
              text: 'Ime ili prezime mora biti duže od 2 karaktera!',
              icon: 'error',
              button: 'OK',
            });
            </script>";
      $msg = "<h4 class='text-danger text-center text-h4'>Ime ili prezime mora biti duže od 2 karaktera!</h4>";
    }
    else if(strlen($email) < 9){
      echo " <script>
             swal({
              title: 'Ticketing System',
              text: 'Email adresa nije validna!',
              icon: 'error',
              button: 'OK',
            });
            </script>";
      $msg = "<h4 class='text-danger text-center text-h4'>Email adresa nije validna!</h4>";
    }
    else if(strlen($opis) < 6){
      echo " <script>
             swal({
              title: 'Ticketing System',
              text: 'Komentar je prekratak, treba da sadrži min. 6 karaktera!',
              icon: 'error',
              button: 'OK',
            });
            </script>";
      $msg = "<h4 class='text-danger text-center text-h4'>Komentar je prekratak, treba da sadrži min. 6 karaktera!</h4>";
    }
    else if(!preg_match("/[-0-9a-zA-Z.+_]+@[-0-9a-zA-Z.+_]+.[a-zA-Z]{2,4}/", $email)){
      echo " <script>
      swal({
       title: 'Ticketing System',
       text: 'Email adresa nije validna!',
       icon: 'error',
       button: 'OK',
     });
     </script>";
$msg = "<h4 class='text-danger text-center text-h4'>Email adresa nije validna!</h4>"; 
    }
    else if(preg_match('/[^a-zA-Z0-9 \[\]_.,;*+čČćĆšŠđĐžŽ=~!:|\/„”€\$?@\#%^&()<>{}\-\[\]\"]/',$opis)){  //regex for alphabet, numbers, special chars
      echo " <script>
             swal({
              title: 'Ticketing System',
              text: 'Vaš komentar može da sadrži isključivo latinična slova, brojeve ili specialne karaktere  osim enter-a.',
              icon: 'error',
              button: 'OK',
            });
            </script>";
      $msg = "<h4 class='text-danger text-center text-h4'>Vaš komentar može da sadrži isključivo latinična slova, brojeve ili specialne karaktere  osim enter-a.</h4>";
    }
    else{
       $_SESSION['newTicInfo'] = " 
       <div class='card bg-dark'>
          <h1 class='text-info text-center'>Vaš broj/ID tiketa je: $tic_number</h1>
          <h4 class='text-warning text-center'>Ime i prezime: $ime $prezime</h4>
          <h4 class='text-warning text-center'>E-mail: $email</h4>
          <h4 class='text-warning text-center'>Lokacija: $lokacija</h4>
          <h4 class='text-warning text-center'>Departman: $departman</h4>
          <h4 class='text-warning text-center'>Vrsta tiketa: $vrsta_tiketa</h4>
          <h4 class='text-warning text-center'>Tip problema: $tip_problema</h4>
          <h6 class='text-warning text-center'>Opis: $opis</h6>
        </div>
       ";

       $date = date('Y-m-d H:i:s');
       $query = "INSERT INTO tickets (`date_created`, `name`, `surname`, `email`, `location`, `department`, `type_tic`, `type_problem`, `description`, `status`, tic_number)  VALUES ('$date', '$ime', '$prezime', '$email', '$lokacija', '$departman', '$vrsta_tiketa', '$tip_problema', '$opis', 'NOV', '$tic_number')";
       $result = mysqli_query($conn, $query);
       mysqli_close($conn);
    
       //log
       include("UserInfo.php");
       //SENT MAIL
       require "scripts/arrayMails.php";
       sendMail($mailSubject['insert'], $mailBody['insert'], $email, "", "", true, $mailAlert['insert'], $mailRedirect['insert']);

    }
  }
  $r_ime = $ime;
  $r_prezime = $prezime;
  $r_email = $email;
  $r_opis = $opis;
}

//reset all input
if(isset($_POST['btn_resert'])){
  $r_ime = "";
  $r_prezime = "";
  $r_email = "";
  $r_opis = "";
}
?>

<div class="container animate-bottom" id="container">
    <div class="card card-main bg-secondary text-white border border-white">
    <h3 class="text-center h3f">ITS TIKET</h3>
         <form class="frm1" action="/ITS/ticket_insert" method="POST" onsubmit="return confirm('Da li želite da pošaljete ovaj tiket? \nPotvrdite na OK dugme.');">
         <center><h5 class="h5-status">Status: otvarate  <?php echo $status; ?> TIKET</h5></center>
             <div class="form-group">
             <?php echo $msg; ?>
               <label>Ime:</label>
               <input type="text" id="name" name="name" class="form-control" placeholder="Unesite ime.." value="<?php echo htmlspecialchars($r_ime); ?>" autocomplete="off">
             </div>
             <div class="form-group">
               <label>Prezime:</label>
               <input type="text" id="surname" name="surname" class="form-control" placeholder="Unesite prezime.." value="<?php echo htmlspecialchars($r_prezime); ?>" autocomplete="off">
             </div>
             <div class="form-group">
               <label>Email:</label>
               <input type="email" style="text-transform: lowercase" id="email" name="email" class="form-control" placeholder="Unesite email.." id="txtHint" autocomplete="email" value="<?php echo htmlspecialchars($r_email); ?>">
             </div>
             <div class="form-group">
                 <label>Departman:</label>
                 <select class="form-control" id="sel1" name="dep" required>
                   <?php 
                    $departmans = array("RECEIVING", "INCOMING", "LABORATORIJA", "SHIPPING", "KOMAX", "LP", "MAGACIN", "SCRAP", "QUALITY", "REWORK", "JIT", "DIY", "SHIFT LEADER", "PRODUCTION", "RADIONICA", "OPEN OFFICE", "TRENING CENTAR", "HR", "ME", "EHS", "FINANSIJE", "SECURITAS");

                    foreach($departmans as $dep){
                     echo "<option value='$dep' $select >$dep</option>";
                    }
   
                    if(isset($_POST['dep']) && $_POST['dep'] == $departman){
                       
                       echo "<option value='$departman' selected>$departman</option>";
                    }
                    ?>
                 </select>
              </div>
              <div class="form-group">
                 <label>Lokacija:</label>
                 <select class="form-control" id="sel2" name="location" required>
                   <option value="NS1" <?php if(isset($_POST['location']) && $_POST['location'] == "NS1") echo 'selected="selected"';?>>NS1</option>
                   <option value="NS2" <?php if(isset($_POST['location']) && $_POST['location'] == "NS2") echo 'selected="selected"';?>>NS2</option>
                 </select>
               </div>
               <div class="form-group">
                 <label>Vrsta tiketa:</label>
                 <select class="form-control" name="type_tic" id="sel3" onchange="izborTiketa();" required>
                   <option value="INCIDENT" <?php if(isset($_POST['type_tic']) && $_POST['type_tic'] == "INCIDENT") echo 'selected="selected"';?>>INCIDENT</option>
                   <option value="ZAHTEV" <?php if(isset($_POST['type_tic']) && $_POST['type_tic'] == "ZAHTEV") echo 'selected="selected"';?>>ZAHTEV</option>
                 </select>
               </div>
               <div class="form-group">
                 <label>Tip problema:</label>
                 <!--incident -->
                <select class="form-control" name="type_prob" id="sel-tip1" required  style="display:block">
                   <option value="Ostalo">Ostalo</option>
                   <optgroup label="NALOZI:">
                     <option value="NALOZI: Problem sa nalogom" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "NALOZI: Problem sa nalogom") echo 'selected="selected"';?>>Problem sa nalogom</option>
                   </optgroup>
                   <optgroup label="STAMPACI:">
                     <option value="STAMPACI: Problem sa stampacem" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "STAMPACI: Problem sa stampacem") echo 'selected="selected"';?>>Problem sa štampačem</option>
                   </optgroup>
                   <optgroup label="OFFICE PAKET:">
                     <option value="OFFICE PAKET: Outlook mail problem" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "OFFICE PAKET: Outlook mail problem") echo 'selected="selected"';?>>Outlook mail problem</option>
                     <option value="OFFICE PAKET: Excel problem" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "OFFICE PAKET: Excel problem") echo 'selected="selected"';?>>Excel problem</option>
                     <option value="OFFICE PAKET: Teams problem" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "OFFICE PAKET: Teams problem") echo 'selected="selected"';?>>Teams problem</option>
                     <option value="OFFICE PAKET: Istekla licenca" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "OFFICE PAKET: Istekla licenca") echo 'selected="selected"';?>>Istekla licenca</option>
                   </optgroup>
                   <optgroup label="TELEFONI:">
                     <option value="TELEFONI: Problem sa telefonom" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "TELEFONI: Problem sa telefonom") echo 'selected="selected"';?>>Problem sa telefonom</option>
                     <option value="TELEFONI: WiFi problem" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "TELEFONI: WiFi problem") echo 'selected="selected"';?>>WiFi problem</option>
                   </optgroup>
                   <optgroup label="RACUNARI:">
                     <option value="RACUNARI: Pristup VPN" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: Pristup VPN") echo 'selected="selected"';?>>Pristup VPN</option>
                     <option value="RACUNARI: Problem sa konekcijom na mrezu" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: Problem sa konekcijom na mrezu") echo 'selected="selected"';?>>Problem sa konekcijom na mrezu</option>
                     <option value="RACUNARI: BitLocker problem" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: BitLocker problem") echo 'selected="selected"';?>>BitLocker problem</option>
                     <option value="RACUNARI: Problem sa racunarom" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: Problem sa racunarom") echo 'selected="selected"';?>>Problem sa računarom</option>
                     <option value="RACUNARI: Problem sa monitorom" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: Problem sa monitorom") echo 'selected="selected"';?>>Problem sa monitorom</option>
                   </optgroup>
                   <optgroup label="APLIKACIJE:">
                     <option value="APLIKACIJE: Ne radi aplikacija" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "APLIKACIJE: Ne radi aplikacija") echo 'selected="selected"';?>>Ne radi aplikacija</option>
                   </optgroup>
                   <optgroup label="KAMERE:">
                     <option value="KAMERE: Ne radi kamera" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "KAMERE: Ne radi kamera") echo 'selected="selected"';?>>Ne radi kamera</option>
                   </optgroup>
                   </optgroup>
                   <optgroup label="SPICA CHECKERI:">
                     <option value="SPICA CHECKERI: Ne rade checkeri" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "SPICA CHECKERI: Ne rade checkeri") echo 'selected="selected"';?>>Ne rade checkeri</option>
                   </optgroup>
                 </select>

                 <!--reqest -->
                 <select class="form-control" name="type_prob" id="sel-tip2" required style="display:none" onchange="choice(this);">
                   <option value="Ostalo">Ostalo</option>
                   <optgroup label="NALOZI:">
                     <option value="NALOZI: Otvaranje novog naloga za zaposlenog" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "NALOZI: Otvaranje novog naloga za zaposlenog") echo 'selected="selected"';?>>Otvaranje novog naloga za zaposlenog</option>
                     <option value="NALOZI: Pristup share folderu" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "NALOZI: Pristup share folderu") echo 'selected="selected"';?>>Pristup share folderu</option>
                     <option value="NALOZI: Podnosenje zahteva" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "NALOZI: Podnosenje zahteva") echo 'selected="selected"';?>>Podnošenje zahteva</option>
                     <option value="NALOZI: Izmena postojeceg naloga" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "NALOZI: Izmena postojeceg naloga") echo 'selected="selected"';?>>Izmena postojećeg naloga</option>
                   </optgroup>
                   <optgroup label="STAMPACI:">
                     <option value="STAMPACI: Setovanje stampaca" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "STAMPACI: Setovanje stampaca") echo 'selected="selected"';?>>Setovanje štampača</option>
                     <option value="STAMPACI: Zamena tonera" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "STAMPACI: Zamena tonera") echo 'selected="selected"';?>>Zamena tonera</option>
                   </optgroup>
                   <optgroup label="OFFICE PAKET:">
                     <option value="OFFICE PAKET: Product activation" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "OFFICE PAKET: Product activation") echo 'selected="selected"';?>>Product activation</option>
                   </optgroup>
                   <optgroup label="RACUNARI:">
                     <option value="RACUNARI: Pristup VPN" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: Pristup VPN") echo 'selected="selected"';?>>Pristup VPN</option>
                     <option value="RACUNARI: Zamena HDD" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "RACUNARI: Zamena HDD") echo 'selected="selected"';?>>Zamena HDD</option>
                   </optgroup>
                   <optgroup label="APLIKACIJE:">
                     <option value="APLIKACIJE: Instaliranje aplikacije" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "APLIKACIJE: Instaliranje aplikacije") echo 'selected="selected"';?>>Instaliranje aplikacije</option>
                     <option value="APLIKACIJE: Obuka za koriscenje" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "APLIKACIJE: Obuka za koriscenje") echo 'selected="selected"';?>>Obuka za korišćenje</option>
                   </optgroup>
                   <optgroup label="KAMERE:">
                     <option value="KAMERE: Pregled snimka" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "KAMERE: Pregled snimka") echo 'selected="selected"';?>>Pregled snimka</option>
                     <option value="KAMERE: Pristup kamerama" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "KAMERE: Pristup kamerama") echo 'selected="selected"';?>>Pristup kamerama</option>
                   </optgroup>
                   </optgroup>
                   <optgroup label="SPICA CHECKERI:">
                     <option value="SPICA CHECKERI: Odobrenje za pristup" <?php if(isset($_POST['type_prob']) && $_POST['type_prob'] == "SPICA CHECKERI: Odobrenje za pristup") echo 'selected="selected"';?>>Odobrenje za pristup</option>
                   </optgroup>
                 </select>
               </div>
               <div class="form-group">
                 <label>Opis problema:</label>
                 <textarea name="description" class="form-control" placeholder="Opišite problem.." rows="3"><?php echo htmlspecialchars($r_opis); ?></textarea>
               </div>
             <button type="submit" name="btn_insert" class="btn btn-success float-right btn-main btn-insert button-s">Pošalji Tiket &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button>
         </form>
         <form action="" method="POST">
            <button type="submit" name="btn_resert" class="btn btn-primary float-right btn-main btn-reset button-p">Resetuj &nbsp;<i class="bi bi-arrow-repeat"></i></button>
         </form>
    </div>
</div>

<script>//POPUP ALERT OBAVESTENJE UKOLIKO KORISNIK IZABERE OVU OPCIJU.
function choice(select){
    var x = document.getElementById("sel-tip2");
    if(x.value == "NALOZI: Otvaranje novog naloga za zaposlenog"){
      alert("Ukoliko izaberete ovu opciju \"Otvaranje novog naloga za zaposlenog\",\n OBAVEZNO navedite Workday ID zaposlenog, koji možete dobiti od HR-a.");
    }
}

//ako je incident prikaz prve liste, ako je zahtevprokaz liste zahteva
function izborTiketa() {
    
    var x1 = document.getElementById('sel-tip1');
    var x2 = document.getElementById('sel-tip2');
    var tic = document.getElementById('sel3');

    if(tic.value == "INCIDENT"){
      x1.style.display = "block";
      x2.style.display = "none";
    }
    else if(tic.value == "ZAHTEV"){
      x1.style.display = "none";
      x2.style.display = "block";
    }
    
}
</script>
<script>
$("#name").keyup(function(){
    $("#email").val(this.value).toLowerCase();
});

$("#surname").keyup(function(){
    $("#email").val($("#name").val()+"."+this.value+"@aptiv.com").toLowerCase();
});
</script>




