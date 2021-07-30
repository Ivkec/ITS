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

$pw = "admin";
$pwHash = md5($pw);
$sql = "UPDATE tits.users SET password='$pwHash' WHERE id='$UID'";
$query = mysqli_query($conn, $sql);
echo "<script>
  swal({
    title: 'Ticketing System',
    text: 'Ušpesno ste resetovali šifru korisnika.',
    timer: 1500,
    icon: 'success',
    button: 'OK',
  })
  .then((value) => {
    location='rootUsers';
  });
  </script>";
?>