<?php 
require_once "db_conn.php";
$repID = $_GET['repID'];

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

$sql = "DELETE FROM tits.daily_reports WHERE id_rep='$repID';";
$query = mysqli_query($conn, $sql) OR die('Error: can not been deleted :(');

header("Location: admin_dnevniIzvestaji");
?>