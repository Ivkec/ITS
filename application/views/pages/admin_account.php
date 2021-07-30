<?php
require_once "db_conn.php";

session_start();
error_reporting(0);


if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM tits.users WHERE `id`='$user_id'";
$result = mysqli_query($conn, $sql);
$res = mysqli_num_rows($result);

while($row = mysqli_fetch_assoc($result)) {
    $user_id = $row['id'];
    $user_name =  $row['name'];
    $user_surname =  $row['surname'];
    $user_email = $row['email'];
    $user_pw = $row['password'];
    $user_role = $row['role'];
  }
  
  $old_pw = md5($_POST['old_pass']);
  $new_pw = $_POST['new_pass'];
  $new_pw_confirm = $_POST['new_pass_confirm'];

if(isset($_POST['btn_changepw'])){

    if($new_pw == "" OR $new_pw_confirm == ""){
        $msg = "<div class='text-danger text-center text-h4'>Niste popunili sva polja!</div>";
        echo "<script>
     swal({
        title: 'Ticketing System',
        text: 'Niste popunili sva polja!',
        icon: 'error',
        button: 'OK',
      })
      .then((value) => {
        location='admin_account';
      });
     </script>";
    }
    else if(strlen($new_pw) < 5 OR strlen($new_pw_confirm) < 5){
        $msg = "<div class='text-danger text-center text-h4'>Šifra mora biti minimalno dužine 6 karaktera!</div>";
        echo "<script>
     swal({
        title: 'Ticketing System',
        text: 'Šifra mora biti minimalno dužine 6 karaktera!',
        icon: 'error',
        button: 'OK',
      })
      .then((value) => {
        location='admin_account';
      });
     </script>";
    }
    else if($old_pw != $user_pw){
     $msg = "<div class='text-danger text-center text-h4'>Pogrešili ste staru šifru!</div>";
     $new_pw = md5($new_pw);
     $new_pw_confirm = md5($new_pw_confirm);
     echo "<script>
     swal({
        title: 'Ticketing System',
        text: 'Pogrešili ste staru šifru!',
        icon: 'error',
        button: 'OK',
      })
      .then((value) => {
        location='admin_account';
      });
     </script>";
    }
    else if($new_pw != $new_pw_confirm){
     $new_pw = md5($new_pw);
     $new_pw_confirm = md5($new_pw_confirm);
     $msg = "<div class='text-danger text-center text-h4'>Nove šifre se ne poklapaju!</div>";
     echo "<script>
     swal({
        title: 'Ticketing System',
        text: 'Nove šifre se ne poklapaju!',
        icon: 'error',
        button: 'OK',
      })
      .then((value) => {
        location='admin_account';
      });
     </script>";
    }
    else{
     $new_pw = md5($new_pw);
     $new_pw_confirm = md5($new_pw_confirm);
     $sql1 = "UPDATE users SET password='$new_pw' WHERE `id`='$user_id'";
     $result1 = mysqli_query($conn, $sql1);
     
     echo "<script>
     swal({
        title: 'Ticketing System',
        text: 'Uspešno ste promenili šifru.',
        icon: 'success',
        button: 'OK',
      })
      .then((value) => {
        location='admin_account';
      });
     </script>";
    }
}

?>
<div class="container animate-bottom" id="container">
    <div class="card card-main bg-secondary text-white border border-white">
            <h3 class="text-center h3f">NALOG</h3>
                <form class="frm1" action="/ITS/admin_account" method="POST">
                       
                       <center>
                         <img src="Slike/1486564400-account_81513.png" alt="account" class="acc-img">
                         <h5><?php echo roleBadge($user_role) .$user_name; ?> <?php echo   $user_surname; ?></h5>
                       </center>
                       <label>E-MAIL:</label>
                       <div class="form-group">
                          <input type="email" class="form-control" value="<?php echo  $user_email; ?>" disabled>
                       </div><br>
                       <h6 class="text-white"><i class="bi bi-info-circle"> Promena šifre </i><hr></h6>
                       <label>STARA ŠIFRA:</label>
                       <div class="form-group">
                          <input type="password" name="old_pass" class="form-control" placeholder="Unesite STARU šifru..">
                       </div>
                       <label>NOVA ŠIFRA:</label>
                       <div class="form-group">
                          <input type="password" name="new_pass" class="form-control" placeholder="Unesite NOVU šifru..">
                       </div>
                       <label>POTVRDITE NOVU ŠIFRU:</label>
                       <div class="form-group">
                          <input type="password" name="new_pass_confirm" class="form-control" placeholder="POTVRDITE novu šifru..">
                       </div>
        
                      <?php echo $msg; ?><br>
                      <button type="submit" name="btn_changepw" class="btn btn-success btn-main button-s">Promeni šifru</button>
                      <button type="reset" class="btn btn-primary float-right btn-main button-p">Reset</button>
                </form>
    </div>
</div>