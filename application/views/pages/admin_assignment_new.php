<?php
require_once "db_conn.php";
require "scripts/sendMail_Zadaci.php"; 
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

  
function validateDate($date, $format = 'Y-m-d') //Y-m-d\TH:i
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

$sql = "SELECT * FROM tits.users WHERE role='Technician'";
$query = mysqli_query($conn, $sql) OR die("Error: Something is wrong :(");



$id_tech = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['id_tech']))));
$date_started = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['vreme_pocetka']))));
$date_finished = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['vreme_zavrsetka']))));
$desc = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['opis']))));
$supervisor = $_SESSION['user_id'];

//GET DATA FOR MAIL
$sql3 = "SELECT * FROM tits.users WHERE id='$id_tech'";
        $query3 = mysqli_query($conn, $sql3);

        while($res3 = mysqli_fetch_assoc($query3)){
            $uEmail = $res3['email'];
            $uName = $res3['name']." ".$res3['surname'];
        }

if(isset($_POST['btn_insert'])){

    if($id_tech == "" OR !is_numeric($id_tech)){
        $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos za polje: IT Tehnicar!</h4>";
    }
    else if(!validateDate($date_started)){
        $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos za polje: Datum vreme pocetka!</h4>";
    }
    else if(!validateDate($date_started)){
        $msg = "<h4 class='text-danger text-center text-h4'>Neprihvatljiv unos za polje: Datum vreme zavrsetka!</h4>";
    }
    else if($desc == ""){
        $msg = "<h4 class='text-danger text-center text-h4'>Polje za unos zadatka je prazno!</h4>";
    }
    else{
        $sql2 = "INSERT INTO tits.assignments (id_tech, assignment, date_started, date_ended, assignmented_uID) VALUES ('$id_tech', '$desc', '$date_started ', '$date_finished', '$supervisor');";
        $query2 = mysqli_query($conn, $sql2) OR die("Error: Something is wrong, data is not inserted :(");
        mysqli_close($conn);

        $mailSubject = "TIC SYSTEM - ZADATAK Supervizora";
        $alertInfo = "Uspešno ste prosledili zadatak tehničaru: ".$uName;
        $redirect = "admin_assignments_supervisor";
        $imeIprezSupervisor =  $_SESSION['ime']." ". $_SESSION['prezime'];
        $body = "
    <h1 style='text-align:center; color: #002080'>>> APTIV NS TICKETING SYSTEM <<</h1><br>
    <p>Poštovani $uName,</p>
    <p>Dobili ste zadatak od Supervizora: $imeIprezSupervisor.</p>
    <p>Datum početka je: $date_started</p>
    <p>Datum završetka je: $date_finished</p>
    <b>Opis zadatka: </b>
    <i><b>$desc</b></i>
    <br>
    <img src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.aptiv.com%2Fimages%2Fdefault-source%2Femail-campaigns%2Faptiv_logo_color_rgb.png&f=1&nofb=1' alt='APTIV' style='width: auto; height: 20px; float: right;'>
    </div>
    ";
//send mail to technician
    sendMail($mailSubject, $body, $uEmail, $alertInfo, $redirect);

        echo "<script>
              swal({
                title: 'Zadaci ALERT',
                text: 'Ušpesno ste poslali zadatak Tehničaru.',
                timer: 1500,
                icon: 'success',
                button: 'OK',
              })
              .then((value) => {
                location='admin_assignments_supervisor';
              });
              </script>";
    }
}

?>

<div class="container">
<a href="admin_assignments_supervisor" class="btn btn-primary button-back btn-main button-p"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>

    <div class="card card-main bg-dark text-white border border-white">
              <h3 class="text-center h3f">KREIRANJE NOVOG ZADATKA</h3>
              <?php echo $msg; ?>
         <form class="frm1" action="" method="POST" onsubmit="return confirm('Da li želite da prosledite ovaj zadatak? \nPotvrdite na OK dugme.');">
            <div class="form-group">
                <label>IT Tehničar kome dodeljujete zadatak:</label>
                <select class="form-control" id="sel1" name="id_tech" required>
                       <?php while($res = mysqli_fetch_assoc($query)): ?>
                         <option value="<?php echo $res['id']; ?>"><?php echo $res['name']." ".$res['surname']; ?></option>
                       <?php endwhile; ?>
                 </select>
            </div>
            <div class="form-group">
                  <label>Datum početka:</label>
                  <input type="date" name="vreme_pocetka" class="form-control" required value="<?php echo date("Y-m-d"); ?>">
            </div>
            <div class="form-group">
                  <label>Datum završetka:</label>
                  <input type="date" name="vreme_zavrsetka" class="form-control" required>
            </div>
            <div class="form-group">
                  <label>Opis zadatka:</label>
                  <textarea name="opis" class="form-control" placeholder="Opišite šta je zadatak za odabranog tehničara.." rows="4" required></textarea>
            </div>
            <br>
            <center><button type="submit" name="btn_insert" class="btn btn-success btn-main form-control button-s">Kreiraj Novi Zadatak &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button></center><br>
         </form>
    </div>
</div>         