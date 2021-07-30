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

  //GET FROM FORM
    $GETname = $_POST['name'];
    $GETprezime = $_POST['surname'];
    $GETemail = $_POST['email'];
    $GETlokacija = $_POST['location'];
    $GETdepartman = $_POST['department'];
    $GETdate_cr = $_POST['date_cr'];
    $GETtip_tiketa = $_POST['type_tic'];
    $GETtip_problema = $_POST['type_prob'];
    $GETopis_problema = $_POST['opis_prob'];
    $GETstatus = $_POST['status'];
    $GETreklama_opis =  $_POST['rekl_opis'];
    $GETdate_reklama =  $_POST['date_rek'];
    $GETIT_tech_ID =  $_POST['id_it_tech'];
    $GETdate_accepted =  $_POST['date_acc'];
    $GETdate_closed =  $_POST['date_cl'];
    $GETdesc_solution =  $_POST['desc_solution'];
    $GETtic_hold = $_POST['hold_status'];
    $GETtic_hold_comm = $_POST['hold_comm'];
    $GETtic_hold_date = $_POST['hold_date'];
    $GETtic_forwarded = $_POST['tic_forwarded'];
    $GETtic_forwarded_date = $_POST['tic_forwarded_date'];

if(isset($_POST['btn_rootUpdate'])){
  $sql2 = "UPDATE tits.tickets SET name='$GETname', surname='$GETprezime', email='$GETemail', location='$GETlokacija', department='$GETdepartman',date_created='$GETdate_cr', type_tic='$GETtip_tiketa', type_problem='$GETtip_problema',description='$GETopis_problema',status='$GETstatus',reklamacija_comment='$GETreklama_opis',date_reklama='$GETdate_reklama',IT_tech_ID='$GETIT_tech_ID',date_accepted_tic='$GETdate_accepted',date_closed_tic='$GETdate_closed',desc_solution='$GETdesc_solution',hold_status='$GETtic_hold',hold_comment='$GETtic_hold_comm',hold_date='$GETtic_hold_date', tic_forwarded='$GETtic_forwarded', tic_forwarded_date='$GETtic_forwarded_date' WHERE tic_number='$br_tic'";
  $result2 = mysqli_query($conn, $sql2);
  header("Location: rootadmin_oneTic_select?ticID=".$br_tic);

}

?>
<div class="container animate-bottom" id="container"> 
<h2 class="text-center">TIKET ID: <span class="text-info"><?php echo $_GET['ticID']; ?></span></h2>
<a href="rootAdmin_tickets" class="btn btn-primary button-back btn-main"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

<div class="row text-center">
    <div class="col">
    <form action="" method="POST" onsubmit="return confirm('Da li želite da azurirate ove podatke?');">
      <table class="table table-sm table-dark table-tic">
         <tbody>
          <tr>
            <th class="table-tic-th text-center bg-secondary">Podaci o tiketu</th>
          </tr>
          <tr>
            <th class="table-tic-th">Ime: <input type="text" name="name" value="<?php echo $ime; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Prezime: <input type="text" name="surname" value="<?php echo $prezime; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">E-mail: <input type="email" name="email" value="<?php echo $email; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Lokacija:<input type="text" name="location" value="<?php echo $lokacija; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Tip tiketa: <input type="text" name="type_tic" value="<?php echo $tip_tiketa; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Departman: <input type="text" name="department" value="<?php echo $departman; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Status: <input type="text" name="status" value="<?php echo $status; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Tip problema: <input type="text" name="type_prob" value="<?php echo $tip_problema; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Opis problema: <textarea name="opis_prob" cols="40" rows="3"><?php echo $opis_problema; ?></textarea></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Datum kreiranja: <input type="text" name="date_cr" value="<?php echo $date_cr; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Datum preuzimanja: <input type="text" name="date_acc" value="<?php echo $date_accepted; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Datum zatvaranja: <input type="text" name="date_cl" value="<?php echo $date_closed; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Datum reklamacije: <input type="text" name="date_rek" value="<?php echo $date_reklama; ?>"></th>
          </tr>
          <tr>
            <th  class="table-tic-th">IT tehničar (ID): <input type="text" name="id_it_tech" value="<?php echo $IT_tech_ID; ?>"> <span><?php echo "(".$ITime. " " .$ITprezime.")"; ?></span></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Komentar IT tehničara: <textarea name="desc_solution" cols="40" rows="3"><?php echo $desc_solution; ?></textarea></th>
          </tr>
          <tr>
            <th  class="table-tic-th">Komentar reklamacije: <textarea name="rekl_opis" cols="40" rows="3"><?php echo $reklama_opis; ?></textarea></th>
          </tr>
          <tr>
          <?php if($tic_hold == 0): ?>
             <th  class="table-tic-th">HOLD status: <span class='text-success'>NEMA <i class='bi bi-check-circle-fill'></i></span></th>
          <?php else: ?> 
             <th  class="table-tic-th">HOLD status: <span class='text-danger'>NA ČEKANJU <i class='bi bi-exclamation-octagon-fill'></i></span></th>
          <?php endif; ?>
          <tr>
             <th class="table-tic-th">Hold Status(0-1): <input type="text" name="hold_status" value="<?php echo $tic_hold; ?>"> </th>
          </tr>
          <tr>
            <th  class="table-tic-th">Hold komentar: <textarea name="hold_comm" cols="40" rows="3"><?php echo $tic_hold_comm; ?></textarea></th>
          </tr>
          <tr>
             <th class="table-tic-th">Hold Date: <input type="text" name="hold_date" value="<?php echo $tic_hold_date; ?>"> </th>
          </tr>
          <tr>
             <th class="table-tic-th">Prosledjivanje tiketa (tic forwarded): <input type="text" name="tic_forwarded" value="<?php echo $tic_forwarded; ?>"> </th>
          </tr>
          <tr>
             <th class="table-tic-th">Tic forwarded Date: <input type="text" name="tic_forwarded_date" value="<?php echo $tic_forwarded_date; ?>"> </th>
          </tr>
        </tbody>
      </table>
      <input type="submit" name="btn_rootUpdate" class="form-control btn btn-danger" value="IZMENI">
  </form>
   </div>
 </div>
</div>
  <script src="JS/btnModal.js"></script>
