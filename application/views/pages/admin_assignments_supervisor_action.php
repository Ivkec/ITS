<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

if($_SESSION['role'] != "Supervisor" AND $_SESSION['role'] != "RootAdmin"){
  header('Location: /ITS/home');
  exit;
}

$aID = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags((int)$_GET['aID'])));

$sql = "SELECT assignments.*, users.* FROM tits.assignments 
        INNER JOIN tits.users ON users.id = assignments.id_tech WHERE id_assignment='$aID'";

$query = mysqli_query($conn, $sql);
while($res = mysqli_fetch_assoc($query)){
  $id = $res['id_assignment'];
  $userName  = $res['name'];
  $userSurname = $res['surname'];
  $userRole = $res['role'];
  $date_started = $res['date_started'];
  $date_ended = $res['date_ended'];
  $zadatak = $res['assignment'];
  $desc_tech = $res['description_tech'];
  $finished = $res['finished'];
  $percent = $res['percent'];
  $date_cr = $res['date_created'];
}

$assignment_ID = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags((int)$_POST['assignment_ID'])));

if(isset($_POST['btn_finish']) AND !empty($assignment_ID)){
  
  $sql2 = "UPDATE tits.assignments SET finished=1 WHERE id_assignment='$assignment_ID';";
  $query2 = mysqli_query($conn, $sql2) OR die('Error: Something is wrong :(');

  echo "<script>
              swal({
                title: 'Zadaci ALERT',
                text: 'Ovaj zadatak je sada uspešno rešen.',
                timer: 1500,
                icon: 'success',
                button: 'OK',
              })
              .then((value) => {
                location='admin_assignments_supervisor_action?aID=$aID';
              });
              </script>";
              
}
else{
  $msg = "<h4 class='text-danger text-center text-h4'>Nešto nije u redu, ntačni podaci.</h4>";
}


?>

<div class="container animate-bottom" id="container">
<a href="admin_assignments_supervisor" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

<table class="table table-md table-hover table-dark text-center table-shadow">
  <thead>
    <tr>
      <th scope="col" colspan="2" class="bg-dark"><h3 class="text-center">Zadatak za tehničara:  <?php echo $userName." ".$userSurname;  ?></h3></th>
    </tr>
  </thead>
  <tbody>
  <?php $msg; ?>
    <tr>
      <th>IT Tehničar:</th>
      <?php echo "<td>".roleBadge($userRole)."</span> ".$userName." ".$userSurname."</td>";  ?>
    </tr>
    <tr>
      <th>Datum početka:</th>
      <th><?php echo convertDate($date_started); ?></th>
    </tr>
    <tr>
      <th>Datum završetka:</th>
      <th><?php echo convertDate($date_ended); ?></th>
    </tr>
    <tr>
      <th>Opis zadatka:</th>
      <th><?php echo $zadatak; ?></th>
    </tr>
    <tr>
      <th>Odgovor Tehničara:</th>
      <th><?php echo  $desc_tech; ?></th>
    </tr>
    <tr>
      <th>Procenat [%] završenog zadatka:</th>
      <th>
      <div class="progress">
         <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo  $percent; ?>%;" aria-valuenow="<?php echo  $percent; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo  $percent; ?>%</div>
      </div>
      </th>
    </tr>
    <tr>
      <th>Status:</th>
      <?php if($finished): ?>
        <th><b class='text-success'>Završen ✔</b></th>
      <?php else: ?>
        <th><b class='text-danger'>Nije Završen ❌</b>
      <br><br>
      <form action="" method="POST"> 
          <input type="hidden" name="assignment_ID" value="<?php echo $id; ?>">
          <button type="submit" name="btn_finish" class="btn btn-success button-s" onclick='return confirm("Da li ste sigurni da želite da označite ovaj zadatak kao ZAVRŠEN? \nPotvrdite na OK dugme.");'>Označi kao završen ✔</button>
      </form>
      </th></tr>
      <?php endif; ?> 
  </tbody>
  <caption><?php 
  echo "Created: ".convertDateTime($date_cr)."<br>";
  ?></caption>
</table>
           
</div>