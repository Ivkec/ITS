<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

require "scripts/sqlTIC_upiti.php";

?>

<div class="container animate-bottom" id="container">
<div class="card card-admin bg-secondary text-white text-center">
            <h3 class="text-center h3f">LOKACIJA</h3>
            <table class="table table-dark">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col" class="text-success">Novi</th>
                  <th scope="col" class="text-warning">Otvoreni</th>
                  <th scope="col" class="text-danger">Zatvoreni</th>
                  <th scope="col" class="text-info">Reklamacije</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">NS-1</th>
                  <td class="text-success"><?php echo $res5; ?> &nbsp; <a href="admin_selectTic?tic=5&type=NOVI"><button class="btn btn-sm btn-success btn-main button-s">Prikaži</button></a></td>
                  <td class="text-warning"><?php echo $res7; ?> &nbsp; <a href="admin_selectTic?tic=6&type=OTVORENI"><button class="btn btn-sm btn-warning btn-main button-w">Prikaži</button></a></td>
                  <td class="text-danger"><?php echo $res9; ?> &nbsp; <a href="admin_selectTic?tic=7&type=ZATVORENI"><button class="btn btn-sm btn-danger btn-main button-d">Prikaži</button></a></td>
                  <td class="text-info"><?php echo $res11; ?> &nbsp; <a href="admin_selectTic?tic=8&type=REKLAMACIJA"><button class="btn btn-sm btn-info btn-main button-i">Prikaži</button></a></td>
                </tr>
                <tr>
                  <th scope="row">NS-2</th>
                  <td class="text-success"><?php echo $res6; ?> &nbsp; <a href="admin_selectTic?tic=9&type=NOVI"><button class="btn btn-sm btn-success btn-main button-s">Prikaži</button></a></td>
                  <td class="text-warning"><?php echo $res8; ?> &nbsp; <a href="admin_selectTic?tic=10&type=OTVORENI"><button class="btn btn-sm btn-warning btn-main button-w">Prikaži</button></a></td>
                  <td class="text-danger"><?php echo $res10; ?> &nbsp; <a href="admin_selectTic?tic=11&type=ZATVORENI"><button class="btn btn-sm btn-danger btn-main button-d">Prikaži</button></a></td>
                  <td class="text-info"><?php echo $res12; ?> &nbsp; <a href="admin_selectTic?tic=12&type=REKLAMACIJA"><button class="btn btn-sm btn-info btn-main button-i">Prikaži</button></a></td>
                </tr>
                <tr>
              </tbody>
            </table>
    </div>
<br>
    <div class="card card-admin bg-secondary text-white text-center">
            <h3 class="text-center h3f">STATISTIKA TIKETA</h3>
            <table class="table table-dark">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col" class="text-success">Novi</th>
                  <th scope="col" class="text-warning">Otvoreni</th>
                  <th scope="col" class="text-danger">Zatvoreni</th>
                  <th scope="col" class="text-info">Reklamacije</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">Svi tiketi</th>
                  <td class="text-success"><?php echo $res1; ?> &nbsp; <a href="admin_selectTic?tic=1&type=NOVI"><button class="btn btn-sm btn-success btn-main button-s">Prikaži</button></a></td>
                  <td class="text-warning"><?php echo $res2; ?> &nbsp; <a href="admin_selectTic?tic=2&type=OTVORENI"><button class="btn btn-sm btn-warning btn-main button-w">Prikaži</button></a></td>
                  <td class="text-danger"><?php echo $res3; ?> &nbsp; <a href="admin_selectTic?tic=3&type=ZATVORENI"><button class="btn btn-sm btn-danger btn-main button-d">Prikaži</button></a></td>
                  <td class="text-info"><?php echo $res4; ?> &nbsp; <a href="admin_selectTic?tic=4&type=REKLAMACIJA"><button class="btn btn-sm btn-info btn-main button-i">Prikaži</button></a></td>
                </tr>
                <tr>
                  <th scope="row">INCIDENTI</th>
                  <td class="text-success"><?php echo $res13; ?> &nbsp; <a href="admin_selectTic?tic=13&type=NOVI"><button class="btn btn-sm btn-success btn-main button-s">Prikaži</button></a></td>
                  <td class="text-warning"><?php echo $res15; ?> &nbsp; <a href="admin_selectTic?tic=14&type=OTVORENI"><button class="btn btn-sm btn-warning btn-main button-w">Prikaži</button></a></td>
                  <td class="text-danger"><?php echo $res17; ?> &nbsp; <a href="admin_selectTic?tic=15&type=ZATVORENI"><button class="btn btn-sm btn-danger btn-main button-d">Prikaži</button></a></td>
                  <td class="text-info"><?php echo $res19; ?> &nbsp; <a href="admin_selectTic?tic=16&type=REKLAMACIJA"><button class="btn btn-sm btn-info btn-main button-i">Prikaži</button></a></td>
                </tr>
                <tr>
                  <th scope="row">ZAHTEVI</th>
                  <td class="text-success"><?php echo $res14; ?> &nbsp; <a href="admin_selectTic?tic=17&type=NOVI"><button class="btn btn-sm btn-success btn-main button-s">Prikaži</button></a></td>
                  <td class="text-warning"><?php echo $res16; ?> &nbsp; <a href="admin_selectTic?tic=18&type=OTVORENI"><button class="btn btn-sm btn-warning btn-main button-w">Prikaži</button></a></td>
                  <td class="text-danger"><?php echo $res18; ?> &nbsp; <a href="admin_selectTic?tic=19&type=ZATVORENI"><button class="btn btn-sm btn-danger btn-main button-d">Prikaži</button></a></td>
                  <td class="text-info"><?php echo $res20; ?> &nbsp; <a href="admin_selectTic?tic=20&type=REKLAMACIJA"><button class="btn btn-sm btn-info btn-main button-i">Prikaži</button></a></td>
                </tr>
                <tr>
                   <th scope="row" colspan="6">Ukupno: <?php echo "<span class='text-success'>$res_ALL</span>"; ?> tiketa</th>
                </tr>
              </tbody>
         </table>
    </div>
    <br>
    <div class="card card-admin bg-secondary text-white text-center">
    <h3 class="text-center h3f">MOJI TIKETI</h3>
    <p>Ovde možete da pregledate sve tikete koje ste prihvatili, zatvorili ili dobili reklamaciju na iste. </p>
        <table class="table table-dark">
            <thead>
              <tr>
                <th scope="col" class="text-warning">Otvoreni</th>
                <th scope="col" class="text-danger ">Zatvoreni</th>
                <th scope="col" class="text-info">Reklamacije</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                  <td class="text-warning"><?php echo $res_u1; ?> &nbsp; <a href="admin_selectTic?tic=21&type=OTVORENI"><button class="btn btn-sm btn-warning btn-main button-w">Prikaži</button></a></td>
                  <td class="text-danger"><?php echo $res_u2; ?> &nbsp; <a href="admin_selectTic?tic=22&type=ZATVORENI"><button class="btn btn-sm btn-danger btn-main button-d">Prikaži</button></a></td>
                  <td class="text-info"><?php echo $res_u3; ?> &nbsp; <a href="admin_selectTic?tic=23&type=REKLAMACIJA"><button class="btn btn-sm btn-info btn-main button-i">Prikaži</button></a></td>
              </tr>
            </tbody>
        </table>
    </div>
</div>

<?php 
//IF PASSWORD DEFAULT< NOTIFY USER
$id = $_SESSION['user_id'];
$pw = md5("admin");
$sqlA = "SELECT * FROM tits.users WHERE id='$id' AND password='$pw'";
$resultA = mysqli_query($conn, $sqlA);
$resA =  mysqli_fetch_array($resultA);

if($resA > 0){
  echo " <script>
  swal({
   title: 'Password WARNING',
   text: '".$_SESSION['ime'].", molimo vas da promenite Vašu generičku šifru!',
   icon: 'warning',
   dangerMode: true,
   button: 'OK',
 });
 </script>";
}
?>