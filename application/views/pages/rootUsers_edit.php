<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

$sql = "SELECT `role` FROM tits.users WHERE id='".$_SESSION['user_id']."' AND role='RootAdmin'";
$userRoleValidation = mysqli_query($conn, $sql);
$resultURV = mysqli_fetch_assoc($userRoleValidation);
if($resultURV == 0){
    header('Location: /ITS/admin_main');
    exit;
}

$UID = mysqli_real_escape_string($conn, $_GET['UID']);

$sql2 = "SELECT * FROM tits.users WHERE id='".$UID."'";
$query2 = mysqli_query($conn, $sql2);

while($res = mysqli_fetch_assoc($query2)){
  $uname = $res['name'];
  $usurname = $res['surname'];
  $uemail = $res['email'];
  $urole = $res['role'];
}

//UPDATE USER

$name = htmlspecialchars($_POST['name']);
$surname = htmlspecialchars($_POST['surname']);
$email = htmlspecialchars($_POST['email']);
$role = htmlspecialchars($_POST['role']);


if(isset($_POST['btn_userUpdate'])){
  $sql3 = "UPDATE users SET name='$name', surname='$surname', email='$email', role='$role' WHERE id='$UID';";
  $query3 = mysqli_query($conn, $sql3) OR die("Error: Doslo je do greske :(");
  echo "<script>
  swal({
    title: 'Ticketing System',
    text: 'Ušpesno ste ažurirali nalog korisniku $name $surname.',
    timer: 2000,
    icon: 'success',
    button: 'OK',
  })
  .then((value) => {
    location='rootUsers';
  });
  </script>";
}
?>


    <div class="container">
         <h2 class="text-danger text-center">ROOT ADMINISTRATOR - <em>EDIT USER</em></h2><br><br>
         <a href="rootUsers" class="btn btn-primary button-back btn-main"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>
         <div class="card card-main bg-dark text-white border-white">
         <form class="frm1" action="rootUsers_edit?UID=<?php echo $UID; ?>" method="POST" onsubmit="return confirm('Da li ste sigurni da želite azurirati nalog ovom korisniku? \nPotvrdite na OK dugme.');"><br>
             <label>Name:</label>
             <input type="text" value="<?php echo $uname; ?>" name="name" class="form-control"><br>
             <label>Surname:</label>
             <input type="text" value="<?php echo $usurname; ?>" name="surname" class="form-control"><br>
             <label>E-mail:</label>
             <input type="email" value="<?php echo $uemail; ?>" name="email" class="form-control"><br>
             <label>Role:</label>
             <input type="text" value="<?php echo $urole; ?>" name="role" class="form-control">
             <br><br>
             <button type="submit" name="btn_userUpdate" class="btn btn-success float-right btn-main btn-insert">Ažuriraj Nalog &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button>
         </form>
         </div>
     </div>


