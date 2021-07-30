<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(!empty($_SESSION['logged_in']))
{
    header('Location: /ITS/Home');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['sifra']); // Encrypt Password 
    $pw = mysqli_real_escape_string($conn, $password);
}

if(isset($_POST['btn_logIn'])){

    $sql = "SELECT * FROM tits.users WHERE `email`='$email' AND `password`='$pw'";
    $result = mysqli_query($conn, $sql);
    $res = mysqli_num_rows($result);

    if ($res == 1) {

        while($row = mysqli_fetch_assoc($result)) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['ime'] =  $row['name'];
            $_SESSION['prezime'] =  $row['surname'];
            $_SESSION['role'] = $row['role'];
          }

        $_SESSION['logged_in'] = 1;
        header("location: /ITS/admin_main");
    }
    else if($_POST['email'] == "" OR $_POST['sifra'] == ""){
        $error = " <div class='text-danger text-center text-h4'>Niste popunili sva polja!</div>";
    } 
    else {
          $error = " <div class='text-danger text-center text-h4'>Pogrešno ste uneli email ili šifru!</div>";
      }
}
?>

<div class="container animate-bottom" id="container">
    <div class="card card-main bg-secondary text-white border border-white">
            <h3 class="text-center h3f">PRIJAVITE SE</h3>
                <form class="frm1" action="/ITS/admin_logIn" method="POST">
                   <img src="Slike/icon-admin-18.jpg" alt="administration" class="img-login">
                   <h6 class="text-center">IT Administracija</h6>
                       <label class="text-center" style="margin-left: 25%">E-MAIL:</label>
                       <div class="form-group text-center" style="width:50%; margin-left: 25%">
                          <input type="email" name="email" class="form-control" placeholder="Unesite Vašu email adresu..">
                       </div>
                       <label style="margin-left: 25%">ŠIFRA:</label>
                         <div class="input-group" id="show_hide_password" style="width:50%; margin-left: 25%">
                           <input type="password" name="sifra" class="form-control" placeholder="Unesite Vašu šifru.." autocomplete="off">
                           <div class="input-group-addon">
                             <a href=""><i class="fa fa-eye-slash" ria-hidden="true"></i></a>
                           </div>
                         </div>
                         <br>
                       <?php echo $error; ?><br>
                       <center><button type="submit" name="btn_logIn" class="btn btn-primary btn-main button-p">LogIn <i class="bi bi-person-check"></i></button></center>
                </form>
    </div>

</div>