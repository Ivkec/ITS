<?php
require_once "db_conn.php";
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


$sql1 = "SELECT assignments.*, users.* FROM tits.assignments 
INNER JOIN tits.users ON users.id = assignments.id_tech
ORDER BY date_created DESC LIMIT 20";

$query1 = mysqli_query($conn, $sql1);


//IF TABLE IS EMPTY
$sql_ifEmp = "SELECT * FROM tits.assignments";
$query_ifEmp = mysqli_query($conn, $sql_ifEmp);

//data post
$date_from = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dateFrom']));
$date_to = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dateTo']));
$techID = mysqli_real_escape_string($conn, htmlspecialchars($_POST['techID']));
$techSQL = ""; 


if(isset($_POST['btn_filter'])){
    if($techID == "ALL"){ //IF SELECTED ALL TECH, NOT NEED ID IT TECH
      $techSQL ="";
    }
    else{
      $techSQL = "AND id_tech='$techID'";
    }
  
  //filter od - do odredjenog datuma
  $sql1 = "SELECT assignments.*, users.* FROM tits.assignments 
           INNER JOIN tits.users ON users.id = assignments.id_tech
           WHERE date_created BETWEEN '$date_from' AND '$date_to' $techSQL ORDER BY date_created DESC";

  $query1 = mysqli_query($conn, $sql1);
  
  }

  //users list
  $sql3 = "SELECT * FROM tits.users WHERE role='Technician' OR role='Technician'";
  $query3 = mysqli_query($conn, $sql3);

?>

<div class="container animate-bottom" id="container">

           <a class="btn btn-primary button-p" href="admin_assignment_new"><i class="bi bi-plus-circle"></i> Novi Zadatak</a>

            <table class="table table-hover text-light bg-dark text-center table-shadow"><br><br>
               <thead>
                 <tr>
                    <th colspan="9">
                        <h3 class="text-center h3f">PREGLED ZADATAKA - <i style='color:#1a75ff;'>Supervisors</i></h3>
                    </th>
                 </tr>
                 <tr>
                    <th colspan="9">
                        <form action="" method="POST">
                            <small class="text-primary"><b>FILTER</b></small>&nbsp;&nbsp;&nbsp;
                            <label class="text-primary">OD:</label>
                            <input type="date" name="dateFrom" class="border-primary text-primary rounded">&nbsp;
                            <label class="text-primary">DO:</label>
                            <input type="date" name="dateTo" value="<?php echo date('Y-m-d\TH:i'); ?>" class="border-primary text-primary rounded">&nbsp;
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
                   <th scope="col">ID</th>
                   <th scope="col">IT Tehničar</th>
                   <th scope="col">Zadatak dodelio</th>
                   <th scope="col">Datum kreiranja zadatka</th>
                   <th scope="col">Status</th>
                   <th scope="col">Update</th>
                   <?php if($_SESSION['role'] == "RootAdmin"){ echo "<th scope='col'>Delete</th>";} ?>
                 </tr>
               </thead>
               <tbody>
               <?php 
                $i = 1;
                while($res = mysqli_fetch_assoc($query1)):
                  $supervisor = $res['assignmented_uID'];
                  $sql2 = "SELECT * FROM users WHERE id='$supervisor'";
                  $query2 = mysqli_query($conn, $sql2);
                  while($res2 = mysqli_fetch_assoc($query2)){
                    $sName = roleBadge($res2['role'])."</span> ".$res2['name']." ".$res2['surname'];
                  }
                  echo "<tr>";
                  echo "<td>".$i++."</td>";
                  echo "<td>".$res['id_assignment']."</td>";
                  echo "<td>".roleBadge($res['role'])."</span> ".$res['name']." ".$res['surname']."</td>";
                  echo "<td>$sName</td>";
                  echo "<td>".convertDateTime($res['date_created'])."</td>";
                  if($res['finished']){
                    echo "<td class='text-success'><b>Zadatak završen ✔</b></td>";
                  }
                  else{
                    echo "<td class='text-danger'><b>Zadatak nije završen ❌</b></td>";
                  }
                  if($_SESSION['role'] == "Supervisor" OR  $_SESSION['role'] == "RootAdmin"): 
                    echo "<td><a href='admin_assignments_supervisor_action?aID=".$res['id_assignment']."' class='btn btn-primary button-p'>Pregled / Potvrda <i class='bi bi-pencil-square'></i></a></td>";
                  endif;
                  if($_SESSION['role'] == "RootAdmin"): ?>
                   <td><a href='admin_assignment_delete?aID=<?php echo $res['id_assignment']; ?>' class='btn btn-danger button-d' onclick='return confirm("Da li ste sigurni da želite da obrišete ovaj zadatak? \nPotvrdite na OK dugme.");'>Obriši <i class="bi bi-trash"></i></a></td>
               <?php  endif; ?>
                  </tr>
              <?php  endwhile; ?>
                 <tr>
                 </tr>
                 <?php if(mysqli_num_rows($query_ifEmp) == 0): ?>
                 <tr>
                    <th colspan="9">
                        <h3 class="text-center text-danger">Nema trenutno ni jednog zadatka za prikaz &#128579;</h3>
                    </th>
                 </tr>
                 <?php endif; ?>
               </tbody>
            </table>
            
</div>