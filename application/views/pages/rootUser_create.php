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

$name = htmlspecialchars($_POST['name']);
$surname = htmlspecialchars($_POST['surname']);
$email = htmlspecialchars($_POST['email']);
$pw = md5($_POST['pass']);

if(isset($_POST['btn_insert'])){
   $sql = "INSERT INTO tits.users (`email`, `password`, `name`, `surname`, `role`) VALUES ('$email', '$pw', '$name', '$surname', 'Technician');";
   $query = mysqli_query($conn, $sql) OR die("Error: Doslo je do greske, nalog nije kreiran :(");
   
   echo "<script>
  swal({
    title: 'Ticketing System',
    text: 'Ušpesno ste napravili nov nalog korisniku $name $surname.',
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
         <h2 class="text-danger text-center">ROOT ADMINISTRATOR - <em>CREATE NEW USER</em></h2>
         <br><br>
         <a href="rootUsers" class="btn btn-primary button-back btn-main"><i class="bi bi-arrow-left-square"></i>  Nazad</a><br><br>
         <div class="card card-main bg-dark text-white border-white">
         <form class="frm1" action="rootUser_create" method="POST" onsubmit="return confirm('Da li ste sigurni da želite napraviti nov nalog ovom korisniku? \nPotvrdite na OK dugme.');"><br>
             <label>Name:</label>
             <input type="text" name="name" class="form-control" placeholder="Ime tehničara.." required><br>
             <label>Surname:</label>
             <input type="text" name="surname" class="form-control" placeholder="Prezime tehničara.." required><br>
             <label>E-mail:</label>
             <input type="email" name="email" class="form-control" placeholder="Email tehničara.." required><br>
             <label>Šifra:</label>
             <input type="password" value="<?php echo "admin"; ?>" name="pass" class="form-control" placeholder="Šifra tehničara.." required>
             <small class="text-danger">* Default: admin</small>
             <br><br>
             <button type="submit" name="btn_insert" class="btn btn-success float-right btn-main btn-insert">Napravi Nalog &nbsp;<i class="bi bi-cloud-arrow-up"></i></i></button>
         </form>
         </div>
     </div>


