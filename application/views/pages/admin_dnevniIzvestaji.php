<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}


$loc = mysqli_real_escape_string($conn, htmlspecialchars($_POST['loc']));

$sql = "SELECT daily_reports.*, users.*, daily_r_line.*, daily_r_loc.*, daily_r_pos.* FROM tits.daily_reports 
INNER JOIN tits.users ON daily_reports.id_card_tech = users.id
INNER JOIN tits.daily_r_line ON daily_r_line.id = daily_reports.id_line
INNER JOIN tits.daily_r_loc ON daily_r_loc.id = daily_reports.id_location
INNER JOIN tits.daily_r_pos ON daily_r_pos.id = daily_reports.id_position 
ORDER BY date_created DESC LIMIT 20";
$query = mysqli_query($conn, $sql);
$query_ifEmp = mysqli_query($conn, $sql);

$date_from = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dateFrom']));
$date_to = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dateTo']));
$techID = mysqli_real_escape_string($conn, htmlspecialchars($_POST['techID']));
$techSQL = ""; 

if(isset($_POST['btn_filter'])){
  if($techID == "ALL"){ //IF SELECTED ALL TECH, NOT NEED ID IT TECH
    $techSQL ="";
  }
  else{
    $techSQL = "AND id_card_tech='$techID'";
  }

//filter od - do odredjenog datuma
$sql = "SELECT daily_reports.*, users.*, daily_r_line.*, daily_r_loc.*, daily_r_pos.* FROM tits.daily_reports 
INNER JOIN tits.users ON daily_reports.id_card_tech = users.id
INNER JOIN tits.daily_r_line ON daily_r_line.id = daily_reports.id_line
INNER JOIN tits.daily_r_loc ON daily_r_loc.id = daily_reports.id_location
INNER JOIN tits.daily_r_pos ON daily_r_pos.id = daily_reports.id_position 
WHERE date_created BETWEEN '$date_from' AND '$date_to' $techSQL ORDER BY date_created DESC";

$query = mysqli_query($conn, $sql);

}

//users list
$sql3 = "SELECT * FROM tits.users";
$query3 = mysqli_query($conn, $sql3);

?>

<div class="container animate-bottom" id="container">

            <form method="GET" action="admin_dnevniIzvestaji_novi?loc=<?php echo htmlspecialchars(strip_tags($loc)); ?>">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">
                            <label class="btn btn-light border-primary text-primary">
                              <input type="radio" name="loc" id="option1"  value="NS1" autocomplete="off" required onchange="check('option1')"> NS-1
                            </label>
                            <label class="btn btn-light border-primary text-primary">
                              <input type="radio" name="loc" id="option2"  value="NS2" autocomplete="off" required onchange="check('option2')"> NS-2
                            </label>          
            </div>
            <button type="submit" class="btn btn-primary button-p" id="btn_new" disabled><i class="bi bi-plus-circle"></i> Novi Izveštaj</button>
           
            </form>
            <br><br>
            <a href="http://10.239.172.30/TIC_export_ctrl" class="btn btn-success button-s">Export u EXCEL <i class="bi bi-download"></i></a>&nbsp;&nbsp;
            <a href="admin_dnevniIzvestaj_genMail" class="btn btn-success button-s">Generiši Smenski Izveštaj <i class="bi bi-envelope"></i></a>
            <br>
            <table class="table table-hover text-light bg-dark text-center table-shadow"><br>
               <thead>
                 <tr>
                    <th colspan="9">
                        <h3 class="text-center h3f">DNEVNI IZVEŠTAJI <small>(zadnjih 20+)</small></h3>
                    </th>
                 </tr>
                 <tr>
                    <th colspan="9">
                        <form action="" method="POST">
                            <small class="text-primary"><b>FILTER</b></small>&nbsp;&nbsp;&nbsp;
                            <label class="text-primary">OD:</label>
                            <input type="datetime-local" name="dateFrom" class="border-primary text-primary rounded">&nbsp;
                            <label class="text-primary">DO:</label>
                            <input type="datetime-local" name="dateTo" value="<?php echo date('Y-m-d\TH:i'); ?>" class="border-primary text-primary rounded">&nbsp;
                            &nbsp;
                            <select name="techID" class="border-primary text-primary rounded">
                                <option value="ALL" selected>Svi tehničari</option>
                                <?php while($res3 = mysqli_fetch_assoc($query3)) echo "<option value='".$res3['id']."' class='text-primary'>".$res3['name']." ".$res3['surname']."</option>"; ?>
                            </select>
                            &nbsp;
                            <button type="submit" name="btn_filter" class="btn btn-sm btn-secondary">Pretraži <i class="bi bi-search"></i></button>
                        </form>
                    </th>
                 </tr>
                 <tr>
                   <th scope="col">#</th>
                   <th scope="col">NS Lokacija</th>
                   <th scope="col">Lokacija</th>
                   <th scope="col">Linija</th>
                   <th scope="col">Ime i Prezime Tehničara</th>
                   <th scope="col">Datum kreiranja</th>
                   <th scope="col">ID Izveštaja</th>
                   <th scope="col">Show</th>
                   <th scope="col">Update</th>
                   <?php if($_SESSION['role'] == "RootAdmin"){ echo "<th scope='col'>Delete</th>";} ?>
                 </tr>
               </thead>
               <tbody>
               <?php 
                $i = 1;
                while($res = mysqli_fetch_assoc($query)):
                  echo "<tr>";
                  echo "<td>".$i++."</td>";
                  echo "<td>".$res['company_loc']."</td>";
                  echo "<td>".$res['loc']."</td>";
                  echo "<td>".$res['line']."</td>";
                  echo "<td>".roleBadge($res['role'])."</span> ".$res['name']." ".$res['surname']."</td>";
                  echo "<td>".convertDateTime($res['date_created'])."</td>";
                  echo "<td>".$res['id_rep']."</td>";
                  echo  "<td><a href='admin_dnevniIzvestaji_show?repID=".$res['id_rep']."&loc=".$res['company_loc']."' class='btn btn-success button-s'>Prikaži <i class='bi bi-file-earmark-text'></i></a></td>";
                  if($res['id_card_tech'] == $_SESSION['user_id'] OR  $_SESSION['role'] == "RootAdmin"): 
                    echo "<td><a href='admin_dnevniIzvestaji_update?repID=".$res['id_rep']."&loc=".$res['company_loc']."' class='btn btn-primary button-p'>Izmeni <i class='bi bi-pencil-square'></i></a></td>";
                  endif;
                  if($_SESSION['role'] == "RootAdmin"): ?>
                   <td><a href='admin_dnevniIzvestaji_delete?repID=<?php echo $res['id_rep']; ?>' class='btn btn-danger button-d' onclick='return confirm("Da li ste sigurni da želite da obrišete ovaj izvestaj? \nPotvrdite na OK dugme.");'>Obriši <i class="bi bi-trash"></i></a></td>
               <?php  endif; ?>
                  </tr>
              <?php  endwhile; ?>
                 <tr>
                 </tr>
                 <?php if(mysqli_num_rows($query_ifEmp) == 0): ?>
                 <tr>
                    <th colspan="9">
                        <h3 class="text-center text-danger">Nema trenutno ni jednog izveštaja &#128579;</h3>
                    </th>
                 </tr>
                 <?php endif; ?>
               </tbody>
            </table>
            
</div>

<script>

function check(id){
  var check = document.getElementById(id).checked;

  if(check){
    document.getElementById('btn_new').disabled = false;
  }
}
</script>
