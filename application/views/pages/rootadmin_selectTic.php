<?php
require_once "db_conn.php";
session_start();
error_reporting(0);
//ako nije ulogovan, redirektuj
if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

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


require "scripts/sqlTIC_upiti.php";
$GLOBALS['conn'] = $conn; //postavi konekciju kao globalnu, zbog funkcije ticList

function ticList($data){ //funkcija za prikaz tabela, sa parametrom "result-a" u zavisnosti sta se izlistava
    $i = 0;//brojac
    $x = 0;//close counter
    while($res =  mysqli_fetch_assoc($data)){
       $i++;
       $idTech = $res['IT_tech_ID'];

       $sql = "SELECT * FROM tits.users WHERE id='$idTech'"; //sql upit za izlistavanje IT-teh
       
       $result = mysqli_query($GLOBALS['conn'], $sql);
       while($res_2 =  mysqli_fetch_assoc($result)){ 
         $nameTECH = $res_2['name'];
         $surnameTECH = $res_2['surname'];
         $role = $res_2['role'];
       }//ispis podataka
       echo "<tr>";
       echo "<th  scope='col' class='text-white text-center'>$i</th>";
       echo "<th scope='col' class='text-white text-center'>".$res['tic_number']."</th>";
       echo "<th scope='col' class='text-white text-center'>".$res['email']."</th>";
       echo "<th scope='col' class='text-white text-center'>".$res['name']." ".$res['surname']."</th>";
       if($_GET['type'] == "NOVI"){
           echo "<th scope='col' class='text-white text-center'>--</th>"; //nije preuzet
       }
       else{
           echo "<th scope='col' class='text-white text-center'>" .roleBadge($role) .$nameTECH." ".$surnameTECH."</th>";
       }
       echo "<th scope='col' class='text-white text-center'>".$res['type_tic']."</th>";
       echo "<th scope='col' class='text-white text-center'>".$res['status']."</th>";
       echo "<th scope='col' class='text-white text-center'>".convertDateTime($res['date_created'])."</th>";
       // --------------- HOLD STATUS --------------------------
       //ako je nula, ne postoji hold, ako je 1 postoji i prikazuje formu.
       if($res['hold_status'] == 0){
        echo "<th scope='col' class='text-success text-center'>NEMA <i class='bi bi-check-circle-fill'></i></th>";
       } 
       else if($res['hold_status'] == 1){
        $x++;
        echo "<th scope='col' class='text-white'><div> 
             <button class='btn btn-danger btn-sm' id='myBtn$i'>Prikaz <i class='bi bi-exclamation-octagon-fill'></i></button></th>
             
             <!-- The Modal -->
             <div id='myModal$i' class='modal'>
               <!-- Modal content -->
               <div class='modal-content border-primary'>
                 <span class='close'>&times;</span>
                <div class='frm1 text-primary'>
                             <h5>Ovaj deo je namenjen isključivo ako IT tehničar iz nekih razloga ne može da reši tiket za kraći period.</h5>
                             <h5>Potrebno je navesti u komentaru zašto odlažete Vaš tiket, na primer: čeka se na isporuku baterije, pa nije moguće da se odradi recimo u naredna 2 dana.</h5>
                             <label class='text-danger'>Razlog odlaganja:</label>
                             <textarea type='text' class='form-control border-danger' rows='4' style='border-radius: 5px' disabled>".$res['hold_comment']."</textarea>
                             <h4 class='text-danger text-center'>Ovaj tiket je odložen datuma: ".convertDateTime($res['hold_date'])."</h4>
                  </div></div></div></div>";
       }            
       echo "<th scope='col' class='text-white text-center'><a href='rootadmin_oneTic_select?ticID=".$res['tic_number']."'><button class='btn btn-sm btn-primary btn-select btn-main'>Edituj</button></a></th>";
       echo "<th scope='col' class='text-white text-center'><a href='rootadmin_rmticket?ticID=".$res['tic_number']."'><button class='btn btn-sm btn-danger btn-select btn-main'>Obriši</button></a></th>";
       echo "</tr>";
       //-------- JAVASCRIPT FOR BTN MODAL ------------------- i is a php counter.
       echo "<script>// Get the modal
       var modal$i = document.getElementById('myModal$i');
       
       // Get the button that opens the modal
       var btn$i = document.getElementById('myBtn$i');
       
       // Get the <span> element that closes the modal
       var span$i = document.getElementsByClassName('close')[$x-1];
       
       // When the user clicks on the button, open the modal
       btn$i.onclick = function() {
         modal$i.style.display = 'block';
       }
       
       // When the user clicks on <span> (x), close the modal
       span$i.onclick = function() {
         modal$i.style.display = 'none';
       }
       
       // When the user clicks anywhere outside of the modal, close it
       window.onclick = function(event) {
         if (event.target == modal$i) {
           modal$i.style.display = 'none';
         }
       }</script>";
   }
  }
   //Uzmi kategoriju
   if($_GET['tic'] == 1 OR $_GET['tic'] == 2 OR $_GET['tic'] == 3 OR $_GET['tic'] == 4){
    $kategorija = "Svi tiketi";
   }
   if($_GET['tic'] == 5 OR $_GET['tic'] == 6 OR $_GET['tic'] == 7 OR $_GET['tic'] == 8){
    $kategorija = "NS1";
   }
   if($_GET['tic'] == 9 OR $_GET['tic'] == 10 OR $_GET['tic'] == 11 OR $_GET['tic'] == 12){
    $kategorija = "NS2";
   }
   if($_GET['tic'] == 13 OR $_GET['tic'] == 14 OR $_GET['tic'] == 15 OR $_GET['tic'] == 16){
    $kategorija = "INCIDENTI";
   }
   if($_GET['tic'] == 17 OR $_GET['tic'] == 18 OR $_GET['tic'] == 19 OR $_GET['tic'] == 20){
    $kategorija = "ZAHTEVI";
   }
   if($_GET['tic'] == 21 OR $_GET['tic'] == 22 OR $_GET['tic'] == 23){
    $kategorija = "Moji Tiketi";
   }


//HTML KOD 
?>
<div class="container-fluid animate-bottom bg-danger" id="container">
<a href="rootAdmin" class="btn btn-primary button-back btn-main"><i class="bi bi-arrow-left-square"></i>  Nazad</a>
<br><br>

    <div class="card card-admin bg-white text-white">
            <h3 class="text-center h3f text-dark">TIKETI: <?php echo $_GET['type']. " - " .$kategorija; ?> - <span class="text-danger">ROOT ADMINISTRATOR</span></h3>
            <table class="table table-dark">
              <thead>
                <tr class="text-center">
                  <th scope="col">#</th>
                  <th scope="col" class="text-primary">ID TIKETA</th>
                  <th scope="col" class="text-primary">Email</th>
                  <th scope="col" class="text-primary">Ime i Prezime korisnika</th>
                  <th scope="col" class="text-primary">Preuzeo</th>
                  <th scope="col" class="text-primary">TIP</th>
                  <th scope="col" class="text-primary">STATUS</th>
                  <th scope="col" class="text-primary">Datum kreiranja</th>
                  <th scope="col" class="text-primary">HOLD</th>
                  <th scope="col" colspan="2" class="text-primary">Action</th>
                </tr>
              </thead>
              <tbody>
              <?php //selekcije u zavisnosti koja je GET vrednost  
              if($_GET['tic'] == 1){
                ticList($result1);
              }
              else if($_GET['tic'] == 2){
                ticList($result2);
              }
              else if($_GET['tic'] == 3){
                ticList($result3);
              }
              else if($_GET['tic'] == 4){
                ticList($result4);
              }
              else if($_GET['tic'] == 5){
                ticList($result5);
              }
              else if($_GET['tic'] == 6){
                ticList($result7);
              }
              else if($_GET['tic'] == 7){
                ticList($result9);
              }
              else if($_GET['tic'] == 8){
                ticList($result11);
              }
              else if($_GET['tic'] == 9){
                ticList($result6);
              }
              else if($_GET['tic'] == 10){
                ticList($result8);
              }
              else if($_GET['tic'] == 11){
                ticList($result10);
              }
              else if($_GET['tic'] == 12){
                ticList($result12);
              }
              else if($_GET['tic'] == 13){
                ticList($result13);
              }
              else if($_GET['tic'] == 14){
                ticList($result15);
              }
              else if($_GET['tic'] == 15){
                ticList($result17);
              }
              else if($_GET['tic'] == 16){
                ticList($result19);
              }
              else if($_GET['tic'] == 17){
                ticList($result14);
              }
              else if($_GET['tic'] == 18){
                ticList($result16);
              }
              else if($_GET['tic'] == 19){
                ticList($result18);
              }
              else if($_GET['tic'] == 20){
                ticList($result20);
              }
              //MY TICKETS
              else if($_GET['tic'] == 21){
                ticList($result_u_o);
              }
              else if($_GET['tic'] == 22){
                ticList($result_u_z);
              }
              else if($_GET['tic'] == 23){
                ticList($result_u_r);
              }
                 
               ?>              
              </tbody>
         </table>
    </div>
</div>
