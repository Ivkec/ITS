<?php
require_once "db_conn.php";
require "scripts/sendMail.php";

session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}


//SELECT TICKET INFO
$tic_ID = $_GET['ticID'];
$sql = "SELECT * FROM tickets WHERE tic_number='$tic_ID'";
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
    $tic_hold = $res['hold_status'];
    $tic_hold_comm = $res['hold_comment'];
    $tic_hold_date = $res['hold_date'];
    $tic_forwarded = $res['tic_forwarded'];
    $tic_forwarded_date = $res['tic_forwarded_date'];
}

$sql2 = "SELECT * FROM users WHERE id='$IT_tech_ID'";
  $result2 = mysqli_query($conn, $sql2);

  while($res2 =  mysqli_fetch_assoc($result2)){
    $ITime = $res2['name'];
    $ITprezime = $res2['surname'];
    $ITemail = $res2['email'];
  }

//CONFIRM TICKET (OPEN)
if(isset($_POST['btn_open'])){
    $user_id = $_SESSION['user_id'];
    $date = date('Y-m-d H:i:s');

    $sql= "UPDATE tickets SET status='OTVOREN', IT_tech_ID='$user_id', date_accepted_tic='$date' WHERE tic_number='$tic_ID'";
    $result2 = mysqli_query($conn, $sql);
    echo "<script>
    swal({
      title: 'Ticketing System',
      text: '".$_SESSION['ime'].", uspešno ste preuzeli tiket broj $br_tic.',
      timer: 1500,
      icon: 'success',
      button: 'OK',
    })
    .then((value) => {
      location='admin_oneTic_select?ticID=$br_tic';
    });
    </script>";
}

  //COMMENT TICKET
if(isset($_POST['btn_comment'])){

  if($_POST['IT_comment'] == ""){
    echo " <script>
           swal({
            title: 'Ticketing System',
            text: 'Niste popunili Vaš komentar.',
            icon: 'error',
            button: 'OK',
          });
          </script>";
  }
  else if(preg_match('/[^a-zA-Z0-9 \[\]_.,;*+=~!:|\/„”€\$?čČćĆšŠđĐžŽ@\#%^&()<>{}\-\[\]\"]/',$_POST['IT_comment'])){  //regex for alphabet, numbers, special chars
    echo " <script>
           swal({
            title: 'Ticketing System',
            text: 'Vaš komentar može da sadrži isključivo latinična slova, brojeve ili specialne karaktere osim ( \', i enter-a).',
            icon: 'error',
            button: 'OK',
          });
          </script>";
  }
  else{
    $date_comment = date('d.m.Y. H:i:s');
    $komentar =  $desc_solution." $date_comment <<<|| ".mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['IT_comment'])))." || \n";
    $sql= "UPDATE tickets SET desc_solution='$komentar'  WHERE tic_number='$tic_ID'";
    $result = mysqli_query($conn, $sql);
    echo "<script>
    swal({
      title: 'Ticketing System',
      text: 'Uspešno ste postavili svoj komentar.',
      timer: 1500,
      icon: 'success',
      button: 'OK',
    })
    .then((value) => {
      location='admin_oneTic_select?ticID=$br_tic';
    });
    </script>";
  }

}

//CONFIRM TO CLOSE TICKET (CLOSE)
if(isset($_POST['btn_close'])){

$sql_validate_comm = "SELECT * FROM tits.tickets WHERE tic_number='$br_tic' AND desc_solution IS NOT NULL";
$result_validate_comm = mysqli_query($conn, $sql_validate_comm);
$res = mysqli_num_rows($result_validate_comm);

if($res > 0){

  $date_closed = date('Y-m-d H:i:s');
  $sql= "UPDATE tickets SET status='ZATVOREN', date_closed_tic='$date_closed', hold_status='0' WHERE tic_number='$tic_ID'";
  $result = mysqli_query($conn, $sql);
  //SENT MAIL
  require "scripts/arrayMails.php";
  sendMail($mailSubject['closed'], $mailBody['closed'], $email, $ITemail, "", false, $mailAlert['closed'], $mailRedirect['closed']);
  
}
else{
  echo "<script>
    swal({
      title: 'Ticketing System',
      text: 'Ne možete da zatvorite ovaj tiket, jer još niste postavili ni jedan komentar.',
      icon: 'error',
      button: 'OK',
    })
    .then((value) => {
      location='admin_oneTic_select?ticID=$br_tic';
    });
    </script>";
  }
}



 //VERIFY IF ID USER MATCH
               
 $userID = $_SESSION['user_id'];
 $query = "SELECT * FROM tickets WHERE tic_number='$br_tic' AND IT_tech_ID='$userID'";
 $result3 = mysqli_query($conn, $query);
 
 //SELECT ALL IT TECH, BUT NOT LOGGED IN USER
 $query2 = "SELECT * FROM tits.users WHERE NOT id='$userID'";
 $result4 = mysqli_query($conn, $query2);

 //IT TECH CHANGE
 $uid = $_POST['user'];
 if(isset($_POST['btn_change_tech'])){

  $date_changeTECH = date('Y-m-d H:i:s');

  $query3 = "UPDATE tits.tickets SET IT_tech_ID='$uid', tic_forwarded='$IT_tech_ID', tic_forwarded_date='$date_changeTECH' WHERE tic_number='$br_tic'";
  $result5 = mysqli_query($conn, $query3);

  $query4 = "SELECT * FROM tits.users WHERE id='$uid'";
  $resultU = mysqli_query($conn, $query4);
  while($res = mysqli_fetch_assoc($resultU)){
    $tech_USERNAME = $res['name']. " ".$res['surname'];
    $newTechMail = $res['email'];
  }
  
  require "scripts/arrayMails.php";
  sendMail($mailSubject['chengeTECH'], $mailBody['chengeTECH'], $email, $newTechMail, $ITemail, false, $mailAlert['chengeTECH'], $mailRedirect['chengeTECH']);
 
 }

 $query5 = "SELECT * FROM tits.users WHERE id='$tic_forwarded'";
  $result5 = mysqli_query($conn, $query5);
  while($res = mysqli_fetch_assoc($result5)){
    $tech_forwarded_name = $res['name']. " ".$res['surname'];
  }


 //TICKET HOLD
 $hold_comment = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['hold_comment'])));

 if(isset($_POST['btn_hold'])){

  $dateHOLD = date('Y-m-d H:i:s');
  $sqlH = "UPDATE tits.tickets SET hold_status=1, hold_comment='$hold_comment', hold_date='$dateHOLD' WHERE tic_number='$br_tic' AND IT_tech_ID='$IT_tech_ID'";
  $result = mysqli_query($conn, $sqlH);

  echo "<script>
  swal({
    title: 'Ticketing System',
    text: 'Ušpesno ste postavili komentar odlaganja tiket broj $br_tic.',
    timer: 1500,
    icon: 'success',
    button: 'OK',
  })
  .then((value) => {
    location='admin_oneTic_select?ticID=$br_tic';
  });
  </script>";
 }

 if(isset($_POST['btn_RM_hold'])){

  $sqlRMH = "UPDATE tits.tickets SET hold_status=0 WHERE tic_number='$br_tic' AND IT_tech_ID='$IT_tech_ID'";
  $result1 = mysqli_query($conn, $sqlRMH);

  echo "<script>
  swal({
    title: 'Ticketing System',
    text: 'Ušpesno ste uklonili komentar odlaganja tiketa broj $br_tic.',
    timer: 1500,
    icon: 'success',
    button: 'OK',
  })
  .then((value) => {
    location='admin_oneTic_select?ticID=$br_tic';
  });
  </script>";
 }

?>
<div class="container animate-bottom" id="container"> 
<h2 class="text-center">TIKET ID: <span class="text-info"><?php echo $_GET['ticID']; ?></span></h2>
<a href="admin_main" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

<!-- NOTIFY IF USER FORWARD -->
<?php if((!empty($tech_forwarded_name) || !empty($tic_forwarded_date)) && $_SESSION['user_id'] != $tic_forwarded): ?>
<div class="alert">
  <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span> 
  <strong>NOTIFY: </strong> Ovaj tiket Vam je prosledio IT tehničar <?php echo $tech_forwarded_name; ?>, datuma: <?php echo $tic_forwarded_date; ?>.
</div>
<?php endif; ?>

<div class="row">
    <div class="col-lg-6">
      <table class="table table-sm table-dark table-tic">
         <tbody>
          <tr>
            <th class="table-tic-th text-center bg-secondary">Podaci o tiketu</th>
          </tr>
          <tr>
            <th class="table-tic-th">Ime: <?php echo "<span class='text-success'>".$ime."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Prezime: <?php echo "<span class='text-success'>".$prezime."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">E-mail: <?php echo "<span class='text-success'>".$email."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Lokacija: <?php echo "<span class='text-success'>".$lokacija."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Tip tiketa: <?php echo "<span class='text-success'>".$tip_tiketa."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Departman: <?php echo "<span class='text-success'>".$departman."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Status: <?php echo "<span class='text-success'>".$status."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Tip problema: <?php echo "<span class='text-success'>".$tip_problema."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th" style="word-wrap: break-word; max-width: 1px;">Opis problema: <?php echo "<span class='text-success'>".$opis_problema."</span>" ?></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Datum kreiranja: <?php echo "<span class='text-success'>".convertDateTime($date_cr)."</span>" ?></th>
          </tr>
          <tr>
          <?php if(!empty($date_accepted)): ?>
            <th  class="table-tic-th">Datum preuzimanja: <?php echo "<span class='text-success'>".convertDateTime($date_accepted)."</span>" ?></th>
          </tr>
          <?php endif; ?>
          <?php if(!empty($date_closed)): ?>
          <tr>
            <th  class="table-tic-th">Datum zatvaranja: <?php echo "<span class='text-success'>".convertDateTime($date_closed)."</span>" ?></th>
          </tr>
          <?php endif; ?>
          <?php if(!empty($date_reklama)): ?>
          <tr>
            <th  class="table-tic-th">Datum reklamacije: <?php echo "<span class='text-success'>".convertDateTime($date_reklama)."</span>" ?></th>
          </tr>
          <?php endif; ?>
          <?php if(!empty($IT_tech_ID)): ?>
             <tr>
               <th  class="table-tic-th">IT tehničar: <?php echo "<span class='text-success'>".$ITime. " ".$ITprezime."</span>" ?></th>
             </tr>
          <?php endif; ?>
          <?php if(!empty($desc_solution)): ?>
          <tr>
            <th  class="table-tic-th" style="word-wrap: break-word; max-width: 1px;">Komentar IT tehničara: <?php echo "<span class='text-success'>".$desc_solution."</span>" ?></th>
          </tr>
          <?php endif; ?>
          <?php if(!empty($reklama_opis)): ?>
          <tr>
            <th  class="table-tic-th" style="word-wrap: break-word; max-width: 1px;">Komentar reklamacije: <?php echo "<span class='text-success'>".$reklama_opis."</span>" ?></th>
          </tr>
          <?php endif; ?>
          <tr>
          <?php if($tic_hold == 0): ?>
             <th  class="table-tic-th">HOLD status: <span class='text-success'>NEMA <i class='bi bi-check-circle-fill'></i></span></th>
          <?php else: ?> 
             <th  class="table-tic-th">HOLD status: <span class='text-danger'>NA ČEKANJU <i class='bi bi-exclamation-octagon-fill'></i></span></th>
          <?php endif; ?> 
          </tr>
        </tbody>
      </table>
   </div>
   <div class="col-lg-6">
         <div class="card card-comm-it bg-secondary">
         <h4 class="text-center text-white">IT tehničar <?php echo "<span class='text-success'>".$_SESSION['ime']. " ".$_SESSION['prezime']."</span>" ?></h4>
             <?php if($status == "NOV"):?>
               <form class="frm1" action="" method="POST" onsubmit="return confirm('Da li želite da preuzmete ovaj tiket? \nPotvrdom na dugme OK prihvatate tiket na Vaše ime i status ovog tiketa će se voditi kao OTVOREN tiket.');">
                  <label class="text-white">Ime i prezime podnosioca tiketa:</label>
                    <input type="text" class="form-control border-success" value="<?php echo $ime." ".$prezime;?>" disabled>
                  <label class="text-white">Komentar korisnika:</label>
                    <textarea type="text" class="form-control border-success" name="user_comment" rows="4" disabled><?php echo $opis_problema; ?></textarea><br>
   
                     <button type="submit" name="btn_open" class="btn btn-primary form-control button-s">Prihvati tiket</button>
               </form>
              <?php endif; ?>
              <?php if($res1 =  mysqli_fetch_array($result3) > 0): ?>

                <?php if($status == "REKLAMACIJA"): ?>
                  <div class="frm1">
                    <label class="text-success">Komentar REKLAMACIJE korisnika:</label>
                      <textarea type='text' class='form-control border-success' rows='4' style='background: #e5ffcc;' disabled><?php echo $reklama_opis; ?></textarea>
                  </div>
                <?php endif; ?>

                 <?php if($status == "OTVOREN" OR $status == "REKLAMACIJA"):?> 
                 <form class="frm1" action="" method="POST" onsubmit="return confirm('Da li želite da prosledite ovaj komentar?');">
                 <?php if(!empty($desc_solution)):?>
                  <label class="text-white">Prethodni komentari IT tehničara:</label>
                    <textarea type='text' class='form-control' rows='4' disabled><?php echo $desc_solution; ?></textarea><br>
                <?php endif; ?>
                 <label class="text-white">Komentar IT tehničara:</label>
                       <textarea type="text" class="form-control border-success" name="IT_comment" placeholder="Napišite Vaš komentar ovde.." rows="4" style="border-radius: 5px 5px 0 0"></textarea>
                    <button type="submit" name="btn_comment" class="btn btn-success form-control" style="border-radius: 0 0 5px 5px">Postavi komentar</button>
                 </form>
                 
                   <form class="frm1" id="tic_form" action="" method="POST" onsubmit="return confirm('Da li želite da zatvorite ovaj tiket? \nPotvrdom na dugme OK prihvatate tiket na Vaše ime i status ovog tiketa će se voditi kao ZATVOREN tiket.');">
                     <p class="text-white">Napomena: Zatvaranjem tiketa se smatra da je tiket završen i više ne možete da dodajete komentar.</p>
                     <button type="submit" name="btn_close" class="btn btn-danger form-control button-d">Zatvori tiket</button>
                  </form>
                  <form class="frm1" action="" method="POST" onsubmit="return confirm('Da li želite da prosledite Vaš tiket drugom IT tehničaru?');">
                    <div class="form-group">
                       <label class="text-white">Lista IT tehničara:</label>
                       <select class="form-control" id="sel1" name="user" required>
                         <?php 
                         while($row = mysqli_fetch_assoc($result4)){
                           echo "<option value='".$row['id']."'>".$row['name']." ".$row['surname']."</option>";
                        }
                         ?>
                       </select>
                    </div>
                     <button type="submit" name="btn_change_tech" class="btn btn-primary form-control button-p">Prosledi tiket drugom tehničaru</button>
                  </form>

                  <!--******************* TICKET HOLD *******************-->
                      <div class="frm1"> 
                          <button class="btn btn-primary form-control button-p" id="myBtn">Odlaganje - Ticket HOLD</button>
                          
                          <!-- The Modal -->
                          <div id="Modal" class="modal">
                            <!-- Modal content -->
                            <div class="modal-content border-primary">
                              <span class="close">&times;</span>
                              <?php if($tic_hold != 1): ?>
                                 <form class="frm1 text-primary" action="" method="POST" onsubmit="return confirm('Da li želite da prosledite ovaj komentar?');">
                                        <h5>Ovaj deo je namenjen isključivo ako IT tehničar iz nekih razloga ne može da reši tiket za kraći period.</h5>
                                        <h5>Potrebno je navesti u komentaru zašto odlažete Vaš tiket, na primer: čeka se na isporuku baterije, pa nije moguće da se odradi recimo u naredna 2 dana.</h5>
                                        <label class="text-white">Komentar odloženog tiketa:</label>
                                        <textarea type="text" class="form-control border-primary" name="hold_comment" placeholder="Napišite komentar zašto odlažete Vaš tiket.." rows="4" style="border-radius: 5px 5px 0 0"></textarea>
                                        <button type="submit" name="btn_hold" class="btn btn-primary form-control" style="border-radius: 0 0 5px 5px">Odloži tiket</button>
                                  </form>
                               <?php elseif($tic_hold == 1): ?>
                                  <div class="frm1 text-primary">
                                          <h5>Ovaj deo je namenjen isključivo ako IT tehničar iz nekih razloga ne može da reši tiket za kraći period.</h5>
                                          <h5>Potrebno je navesti u komentaru zašto odlažete Vaš tiket, na primer: čeka se na isporuku baterije, pa nije moguće da se odradi recimo u naredna 2 dana.</h5>
                                          <label class="text-danger">Razlog odlaganja:</label>
                                          <textarea type="text" class="form-control border-danger" name="hold_comment" rows="4" style="border-radius: 5px" disabled><?php echo $tic_hold_comm; ?></textarea>
                                          <h4 class="text-danger text-center">Ovaj tiket je odložen datuma: <?php echo convertDateTime($tic_hold_date); ?></h4>
                                          <form class="frm1" action="" method="POST" onsubmit="return confirm('Da li ste sigurni da želite ukloniti odlaganje ovog tiketa?');">
                                             <button type="submit" name="btn_RM_hold" class="btn btn-danger form-control">Ukloni HOLD status ovog tiketa</button>
                                          </form>
                                  </div>
                               <?php endif; ?>          
                            </div>
                          </div>
                      </div>
            <?php endif; ?>
   
                 <?php if($status == "ZATVOREN"): ?>
                 <form class="frm1">
                    <label class="text-white">Ime i prezime podnosioca tiketa:</label>
                      <input type="text" class="form-control border-danger" value="<?php echo $ITime." ".$ITprezime;?>" disabled>
                    <label class="text-white">Komentari IT tehničara:</label>
                      <textarea type='text' class='form-control border-danger' rows='4' disabled><?php echo $desc_solution; ?></textarea><br>

                    <h4 class="text-danger text-center">Ovaj tiket je zatvoren.</h4>
                 </form>
                 <?php endif; ?>
                 <?php else: ?>
                  <?php if($status == "OTVOREN" OR $status == "ZATVOREN" OR $status == "REKLAMACIJA"): ?>

                    <form class="frm1">
                    <label class="text-white">Ime i prezime podnosioca tiketa:</label>
                      <input type="text" class="form-control border-success" value="<?php echo $ime." ".$prezime;?>" disabled>
                    <label class="text-white">Komentar korisnika:</label>
                      <textarea type="text" class="form-control border-success" name="user_comment" rows="4" disabled><?php echo $opis_problema; ?></textarea><br>

                      <h4 class="text-danger text-center">Ovaj tiket je već preuzet od strane drugog tehničara.</h4>
                 </form>
                  <?php endif; ?>
                  
              <?php endif; ?>
              </div>
         </div>
     </div>      
  </div>

  <script src="JS/btnModal.js"></script>
