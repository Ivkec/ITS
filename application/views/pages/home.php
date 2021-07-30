<?php
require_once "db_conn.php";
session_start();
error_reporting(0);
$brtic =$_POST['br_tic'];

if(isset($_POST['btn_next'])){
  
  $status = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['status']))));

  if($status == "NOV"){

    $_SESSION['status'] = "NOV";
    header("Location: /ITS/ticket_insert");
  }
  else if(trim($_POST['br_tic']) == ""){
    $msg = "Niste odabrali status ili uneli broj tiketa.";
  }
  else{
    $_SESSION['status'] = "REKLAMACIJA";
    $sql = "SELECT * FROM tickets WHERE tic_number='$brtic' AND status='ZATVOREN' AND reklama_status=0";
    $result = mysqli_query($conn, $sql);

    if($res =  mysqli_fetch_array($result) == 0){
      $msg = "Ne možete da reklamirate ovaj tiket, tiket je u obradi ili je već bio reklamiran.";
    }
    else{
      $_SESSION['br_tic'] = $_POST['br_tic'];
      $_SESSION['status'] = "REKLAMACIJA";
      header("Location: /ITS/ticket_reklamacija");
    }
  }
}


$search = trim(mysqli_real_escape_string($conn, htmlspecialchars(strip_tags($_POST['tic_search']))));

$query = "SELECT * FROM tickets WHERE tickets.tic_number='$search'";
$result2 = mysqli_query($conn, $query);

if(isset($_POST['btn_search'])){
  if($search == ""){
   echo " <script>
   swal({
    title: 'Ticketing System',
    text: 'Niste uneli broj tiketa.',
    icon: 'error',
    button: 'OK',
  });
  </script>";
  $msgS = "Niste uneli broj tiketa.";
  }
  else if($res1 =  mysqli_fetch_array($result2) == 0){
    echo " <script>
     swal({
      title: 'Ticketing System',
      text: 'Uneli ste nepostojeći tiket!',
      icon: 'error',
      button: 'OK',
    });
    </script>";
    $msgS = "Uneli ste nepostojeći tiket!";
  }
  else{
    header('Location: ticket_info?ticID='.$search);
  }
}

?>


<div class = 'container1 projects'>
   <h1>Dobrodošli NA IT TICKETING :)</h1>
   <div class="overlay"></div>
</div>
<div class="container animate-bottom" id="container">
    <div class="card card-main bg-secondary border border-white text-white">
        <h3 class="text-center h3f">TIKET</h3>
             <form class="frm1" action="/ITS/home" method="POST">
             <h6 class="text-center">Izaberite da li želite da kreirate nov tiket ili reklamirate postojeći.</h6>
             <p class="text-center"><small>(Ukoliko izaberete status "REKLAMACIJA", upišite u polje vaš broj tiketa koji reklamirate.)</small></p>
                   <div class="form-group input-ticket">
                   <label>TIP TIKETA:</label>
                           <select class="form-control" name="status" 
                      onchange="if(this.options[this.selectedIndex].value=='REKLAMACIJA'){
                          toggleField(this,this.nextSibling);
                          this.selectedIndex='0';
                      }">
                        <option value="NOV">NOV TIKET</option>
                        <option value="REKLAMACIJA">REKLAMACIJA TIKETA</option>
                    </select><input type="text" class="form-control" name="br_tic" style="display:none;" disabled="disabled" placeholder="Upisi BROJ tiketa.."
                        onblur="if(this.value==''){toggleField(this,this.previousSibling);}">
                   </div>
    
                   <?php echo "<p class='text-warning'>$msg</p>"; ?>
                   <center><button type="submit" name="btn_next" class="btn btn-success btn-main button-s">Dalje &nbsp;<i class="bi bi-arrow-right-square"></i></button></center>
            </form>
    </div>
    <center><div class="search-bar">
    <form class="form-inline search-ticket" method="POST" action="/ITS/home">
        <p class="text-success"><b>Ako želite da proverite status tiketa, upišite broj/ID vašeg tiketa.</b></p>
        
        <input class="form-control mr-sm-2" type="text" placeholder="UNESITE BROJ TIKETA.."  name="tic_search" autocomplete="off">
        <button class="btn btn-outline-success my-2 my-sm-0 btn-select btn-main button-s" type="submit" name="btn_search">Pretraži tiket &nbsp;<i class="fa fa-search"></i></button>
        <?php echo "<h6 class='text-danger'>$msgS</h6>";?>
      </form>
      </div></center>
</div>
<!-- SHOW OR TOGGLE FIELD IN OPTION (REKLAMACIJA) -->
<script src="JS/toggleField.js"></script>
<script src="JS/IE_block.js"></script>