<?php
require_once "db_conn.php";

session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

if($_SESSION['role'] == "Supervisor" AND $_SESSION['role'] == "RootAdmin"){
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

$percent_slider = mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['percent_slider'])));
$comment = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['comment']))));

if(isset($_POST['btn_slider'])){
  if(!is_numeric($percent_slider)){
    $msg = "<h3 class='text-danger text-center text-h4'>Neprihvatljiv unos.</h3>";
  }
  else if($percent_slider < 0 OR $percent_slider > 100){
    $msg = "<h3 class='text-danger text-center text-h4'>Neprihvatljiv unos procenta.</h3>";
  }
  else{
  $sql2 = "UPDATE tits.assignments SET percent='$percent_slider' WHERE id_assignment='$aID';";
  $query2 = mysqli_query($conn, $sql2) OR die('Error: Something is wrong :(');
  echo "<script>
              swal({
                title: 'Zadaci ALERT',
                text: 'Uspešno ste ažurirali slajder procenata rada.',
                timer: 1500,
                icon: 'success',
                button: 'OK',
              })
              .then((value) => {
                location='admin_assignments_technician_action?aID=$aID';
              });
              </script>";
  }
              
}

if(isset($_POST['btn_comment'])){
  if($comment == ""){
    $msg = "<h3 class='text-danger text-center text-h4'>Komentar ne može biti prazan.</h3>";
  }
  else{
    $date_comment = date('d.m.Y. H:i:s');
    $komentar = $desc_tech." $date_comment <<<|| ".$comment." || \n";


  $sql3 = "UPDATE tits.assignments SET description_tech='$komentar' WHERE id_assignment='$aID';";
  $query3 = mysqli_query($conn, $sql3) OR die('Error: Something is wrong :(');
  echo "<script>
              swal({
                title: 'Zadaci ALERT',
                text: 'Uspešno ste ažurirali svo komentar.',
                timer: 1500,
                icon: 'success',
                button: 'OK',
              })
              .then((value) => {
                location='admin_assignments_technician_action?aID=$aID';
              });
              </script>";
  }
              
}


?>

<div class="container animate-bottom" id="container">
<a href="admin_assignments_tech" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

<table class="table table-md table-hover table-dark text-center table-shadow">
  <thead>
    <tr>
      <th scope="col" colspan="2" class="bg-dark"><h3 class="text-center">Zadatak</h3></th>
      <?php echo $msg; ?>
    </tr>
  </thead>
  <tbody>
    <tr>
      <th>Vaše ime i prezime:</th>
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
      <th>Opis Vašeg zadatka:</th>
      <th><?php echo $zadatak; ?></th>
    </tr>
    <tr>
      <th>Status:</th>
      <?php if($finished): ?>
        <th><b class='text-success'>Završen ✔</b></th>
      <?php else: ?>
        <th><b class='text-danger'>Nije Završen ❌</b> <br><small>Supervizor mora da potvrdi da je Vaš zadatak završen.</small>
      </th></tr>
      <?php endif; ?> 
          <tr>
            <form action="" method="POST" onsubmit='return confirm("Da li ste sigurni da želite da ažurirate status Vašeg zadatka? \nPotvrdite na OK dugme.");'>
            <th>Procenat završenog zadatka: <span class="text-info"><?php echo $percent; ?>%</span>&nbsp;
           <?php if($finished == 0): ?>
            <button type="submit" name="btn_slider" class="btn btn-primary">Ažuriraj slajder &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button>
           <?php endif; ?>
          </th>
            <th>
            <?php if($finished == 0): ?>
                 <input class="tech-slider" type="range" value="<?php echo $percent; ?>" name="percent_slider">
                 <div id="h4-container"><div id="h4-subcontainer"><h4 class="percent">0<span></span></h4></div></div>
            <?php else: ?>
              <div class="progress">
                 <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" role="progressbar" style="width: <?php echo  $percent; ?>%;" aria-valuenow="<?php echo  $percent; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo  $percent; ?>%</div>
              </div>
            <?php endif; ?>
            </form>
            </th>
          </tr>
          <tr>
          <th>
                <label>Vaši komentari:</label>
                <textarea type="text" class="form-control border-primary" rows="5" placeholder="Trenutno nemate ni jedan komentar." disabled><?php echo  $desc_tech; ?></textarea>
          </th>
            <th>
              <form action="" method="POST" onsubmit='return confirm("Da li ste sigurni da želite postaviti ovaj komentar? \nPotvrdite na OK dugme.");'>
                <label>Upišite novi komentar:</label>
                <textarea type="text" class="form-control border-primary" name="comment" placeholder="Napišite Vaš komentar, kako ste rešili zadatak, problemi, itd..." rows="3" style="border-radius: 5px 5px 0 0;"></textarea>
                <button type="submit" name="btn_comment" class="btn btn-primary form-control button-p" style="border-radius: 0 0 5px 5px">Postavi komentar</button>
              </form>
              </th>
          </tr>
            
  </tbody>
  <caption><?php 
  echo "Created: ".convertDateTime($date_cr)."<br>";
  ?></caption>
</table>
           
</div>

<script>
$(function() {
	var rangePercent = $('[type="range"]').val();
	$('[type="range"]').on('change input', function() {
		rangePercent = $('[type="range"]').val();
		$('h4').html(rangePercent+'<span></span>');
		$('[type="range"], h4>span').css('filter', 'hue-rotate(-' + rangePercent + 'deg)');
		// $('h4').css({'transform': 'translateX(calc(-50% - 20px)) scale(' + (1+(rangePercent/100)) + ')', 'left': rangePercent+'%'});
		$('h4').css({'transform': 'translateX(-50%) scale(' + (1+(rangePercent/100)) + ')', 'left': rangePercent+'%'});
	});
});
</script>