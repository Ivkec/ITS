<?php

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/home');
    exit;
}
?>

<?php 
session_start();
session_unset();
session_destroy();
header("location: /ITS/admin_logIn");
?>

