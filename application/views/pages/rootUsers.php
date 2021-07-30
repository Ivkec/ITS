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

$sql2 = "SELECT * FROM tits.users";
$query2 = mysqli_query($conn, $sql2);

?>


    <div class="container">
         <h2 class="text-danger text-center">ROOT ADMINISTRATOR - <em>USERS</em></h2>
         <br><br>
         <a href="rootUser_create" class="btn btn-primary">+ Dodaj Novog Korisnika</a><br><br>
         <table class="table table-hover table-dark table-sm text-center">
           <thead>
             <tr>
               <th scope="col">#</th>
               <th scope="col">User ID</th>
               <th scope="col">Name</th>
               <th scope="col">Surname</th>
               <th scope="col">Role</th>
               <th scope="col" colspan="3">ACTION</th>
             </tr>
           </thead>
           <tbody>
           <?php 
           $i = 0;
           while($res2 = mysqli_fetch_assoc($query2)):
               $i++;
                echo "<tr>";
                echo "<td>$i.</td>";
                echo "<td>".$res2['id']."</td>";
                echo "<td>".$res2['name']."</td>";
                echo "<td>".$res2['surname']."</td>";
                echo "<td>".$res2['role']."</td>";
                echo "<td><a href='rootUsers_edit?UID=".$res2['id']."' class='btn btn-primary btn-sm'>EDIT USER</a></td>";
                if($res2['id'] != $_SESSION['user_id']):
                ?>
                <td><a href='rooUserRESPASS?UID=<?php echo $res2['id']; ?>' class='btn btn-secondary btn-sm' onclick='return confirm("Da li ste sigurni da želite da resetujete šifru ovog korisnika?");' data-toggle="popover" 
                    data-trigger="hover" data-content="Biće postavljena generička (default) šifra: [admin]" data-placement="bottom">RESET PASSWORD</a></td>
                <td><a href='rooUserRM?UID=<?php echo $res2['id']; ?>' class='btn btn-danger btn-sm' onclick='return confirm("Da li ste sigurni da želite da obrišete ovog korisnika?");'>DELETE USER</a></td>
                </tr>
           <?php
            endif;
          endwhile; ?>
           </tbody>
         </table>
     </div>
     <script>
        $('[data-toggle="popover"]').popover();
    </script>

