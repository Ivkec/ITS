
<?php 
require_once "db_conn.php";
require "scripts/sendMail.php";

session_start();
error_reporting(0);

$status = $_SESSION['status'];
$broj_tic = $_SESSION['br_tic'];
$komentar = trim($_POST['komentar']);

$query = "SELECT * FROM tits.tickets WHERE tickets.tic_number='$broj_tic'";     
$result = mysqli_query($conn, $query);

while($res = mysqli_fetch_assoc($result)) 
{       
    $ime = $res['name'];
    $prezime =  $res['surname'];
    $email = $res['email'];
    $departman = $res['department'];
    $lokacija = $res['location'];
    $vrsta_tiketa = $res['type_tic'];
    $tip_problema = $res['type_problem'];
    $opis = $res['description'];
    $date = $res['date_created'];
    $IT_tech_ID =  $res['IT_tech_ID'];
}

$sql2 = "SELECT * FROM users WHERE id='$IT_tech_ID'";
  $result2 = mysqli_query($conn, $sql2);

  while($res2 =  mysqli_fetch_assoc($result2)){
    $ITime = $res2['name'];
    $ITprezime = $res2['surname'];
    $ITemail = $res2['email'];
  }

if(isset($_POST['btn_insert'])){

  if($komentar == ""){
    $msg = "<h4 class='text-danger text-center text-h4'>Niste popunili polje!</h4>";
  }
  else if(strlen($komentar) <= 6){
    $msg = "<h4 class='text-danger text-center text-h4'>Vaš komentar mora biti duži od 6 karaktera!</h4>";
  }
  else{
    $date = date('Y-m-d H:i:s');
    $query = "UPDATE tickets SET tickets.reklamacija_comment='$komentar', tickets.status='REKLAMACIJA', tickets.date_reklama='$date', tickets.reklama_status=1 WHERE tic_number='$broj_tic'";
    
    $result = mysqli_query($conn, $query);
    mysqli_close($conn);

    //SENT MAIL
    require "scripts/arrayMails.php";
    sendMail($mailSubject['reklama'], $mailBody['reklama'], $email, $ITemail, "", false, $mailAlert['reklama'], $mailRedirect['reklama']);
  }
}
?>
<div class="container animate-bottom" id="container">
    <div class="card card-main bg-secondary text-white border border-white">
    <h3 class="text-center h3f">ITS TIKET</h3><br>
         <form class="frm1" action="/ITS/ticket_reklamacija" method="POST" onsubmit="return confirm('Da li želite da pošaljete reklamaciju na ovaj tiket? \nPotvrdite na OK dugme.');">
         <center><h5 class="h5-status">Status: <?php echo $status; ?> TIKET,  <span class="text-warning">BROJ TIKETA: <?php echo $broj_tic; ?></span></h5></center>
         <div class="form-group">   
               </div>
               <div class="form-group">
                 <label>Vrsta tiketa:</label>
                 <input type="text" name="type_tic" class="form-control" value="<?php echo $vrsta_tiketa; ?>" disabled>
               </div>
               <div class="form-group">
                 <label>Komentar reklamacije:</label>
                 <textarea name="komentar" class="form-control" rows="3" placeholder="Upišite Vaš komentar.."></textarea>
               </div>
            <p><?php echo $msg; ?></p>
             <button type="submit" name="btn_insert" class="btn btn-success btn-main button-s">Pošalji</button>
             <button type="reset" class="btn btn-primary float-right btn-main button-p">Resetuj</button>
         </form>
    </div>
</div>