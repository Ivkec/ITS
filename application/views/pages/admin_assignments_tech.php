<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

if($_SESSION['role'] == "Supervisor" AND $_SESSION['role'] == "RootAdmin"){
  header('Location: /ITS/home');
  exit;
}

$getSessionUID = $_SESSION['user_id'];

$sql1 = "SELECT assignments.*, users.* FROM tits.assignments 
INNER JOIN tits.users ON users.id = assignments.id_tech
WHERE id_tech='$getSessionUID' ORDER BY date_created DESC";
$query1 = mysqli_query($conn, $sql1);

//IF TABLE IS EMPTY
$sql_ifEmp = "SELECT * FROM tits.assignments WHERE id_tech='$getSessionUID'";
$query_ifEmp = mysqli_query($conn, $sql_ifEmp);

//data post
$date_from = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dateFrom']));
$date_to = mysqli_real_escape_string($conn, htmlspecialchars($_POST['dateTo']));
$techID = mysqli_real_escape_string($conn, htmlspecialchars($_POST['techID']));
$techSQL = ""; 


if(isset($_POST['btn_filter'])){
  
  //filter od - do odredjenog datuma
  $sql1 = "SELECT assignments.*, users.* FROM tits.assignments 
           INNER JOIN tits.users ON users.id = assignments.id_tech
           WHERE date_created BETWEEN '$date_from' AND '$date_to'AND id_tech='$getSessionUID' ORDER BY date_created DESC";

  $query1 = mysqli_query($conn, $sql1);
  
  }

  //users list
  $sql3 = "SELECT * FROM tits.users WHERE role='Technician'";
  $query3 = mysqli_query($conn, $sql3);

?>

<div class="container animate-bottom" id="container">


            <table class="table table-hover text-light bg-dark text-center table-shadow"><br>
               <thead>
                 <tr>
                    <th colspan="9">
                        <h3 class="text-center h3f">MOJI ZADACI - <i class="text-success">Technician assignments</i></h3>
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
                            <button type="submit" name="btn_filter" class="btn btn-sm btn-secondary">Pretraži <i class="bi bi-search"></i></button>
                        </form>
                    </th>
                 </tr>
                 <tr>
                   <th scope="col">#</th>
                   <th scope="col">ID</th>
                   <th scope="col">IT Tehničar</th>
                   <th scope="col">Datum kreiranja zadatka</th>
                   <th scope="col">Status</th>
                   <th scope="col">Action</th>
                 </tr>
               </thead>
               <tbody>
               <?php 
                $i = 1;
                while($res = mysqli_fetch_assoc($query1)):
                  echo "<tr>";
                  echo "<td>".$i++."</td>";
                  echo "<td>".$res['id_assignment']."</td>";
                  echo "<td>".roleBadge($res['role'])."</span> ".$res['name']." ".$res['surname']."</td>";
                  echo "<td>".convertDateTime($res['date_created'])."</td>";
                  if($res['finished']){
                    echo "<td class='text-success'><b>Zadatak završen ✔</b></td>";
                  }
                  else{
                    echo "<td class='text-danger'><b>Zadatak nije završen ❌</b></td>";
                  }
                 
              echo   "<td><a href='admin_assignments_technician_action?aID=".$res['id_assignment']."' class='btn btn-primary button-p'>Pregled / Izmena <i class='bi bi-pencil-square'></i></a></td>";
                 ?>
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