<?php
require_once "db_conn.php";
session_start();
error_reporting(0);

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

if($_SESSION['role'] != "RootAdmin"){
    header('Location: /ITS/home');
    exit;
}

$aID = $_GET['aID'];
$sql = "DELETE FROM tits.assignments WHERE id_assignment='$aID';";
$query = mysqli_query($conn, $sql) OR die('Error: can not been deleted :(');

header("Location: admin_assignments_supervisor");


?>

