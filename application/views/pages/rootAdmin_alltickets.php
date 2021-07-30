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

$sql2 = "SELECT * FROM tits.tickets ORDER BY date_created DESC";
$result2 = mysqli_query($conn, $sql2)  OR die();


?>

<div class="container-fluid animate-bottom" id="container">
    <div class="card card-admin bg-secondary text-white text-center">
            <h3 class="text-center h3f">ALL TICKETS</h3>
            <table class="table table-dark">
              <tbody>
                <tr>
                  <th>tic_number</th>
                  <th>date_created</th>
                  <th>name</th>
                  <th>surname</th>
                  <th>email</th>
                  <th>location</th>
                  <th>department</th>
                  <th>type_tic</th>
                  <th>type_problem</th>
                  <th>description</th>
                  <th>status</th>
                  <th>reklamacija_comment</th>
                  <th>reklama_status</th>
                  <th>date_reklama</th>
                  <th>IT_tech_ID</th>
                  <th>date_accepted_tic</th>
                  <th>date_closed_tic</th>
                  <th>hold_status</th>
                  <th>hold_comment</th>
                  <th>hold_date</th>
                  <th>tic_forwarded</th>
                  <th>tic_forwarded_date</th>
                </tr>
                
                  <?php while($resT = mysqli_fetch_assoc($result2)){
                  echo "<tr>";
                  
                  echo "<td>".$resT['tic_number']."</td>";

                  echo "</tr>";

                  } ?>
              
               
              </tbody>
         </table>
    </div>
    <br>
</div>

        </dv>
    </div>

