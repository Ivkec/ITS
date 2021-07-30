<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="images/ticket.ico" type="image/x-icon" />
        <meta name="author" content="Ivan Funcik">
        <title>IT Ticketing System | <?php echo $title; ?></title>
        <!--bootstrap external-->
        <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <script src="bootstrap-4.0.0-dist/js/bootstrap.min.js"></script>
        
        <!--bootstrap-->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" 
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <!--icon library -->
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet"/>
        <!--custom CSS-->
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/style.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/dark_mode.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/welcome_animation.css">
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/btnModal.css">
        
        <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    </head>
    <body>
        <?php include "navbar.php";?>

        <!-- FUNNY ANIMATION DARK MODE-->
        <div id="darkside" style="display:none"> 
        <img src="Slike/DV.jpeg" alt="darkside" class="darkside">
        <h2 class="text-white">Welcome to the dark side!</h2>
        </div>
         <!-- FUNNY ANIMATION WHITE MODE-->
         <div id="whiteside" style="display:none"> 
        <img src="Slike/gandalf-helms-deep.jpg" alt="whiteside" class="darkside">
        <h2 class="text-dark">Look to my coming on the <br>first light on the 5th day!</h2>
        </div>