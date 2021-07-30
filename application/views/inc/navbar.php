<?php
require "scripts/main-functions.php";
error_reporting(0);
session_start();
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark nav_marg fixed-top">
  <a class="navbar-brand text-danger" href="home"><img src="images/ticket.png" windt="40px" height="40px"><b>IT Ticketing System</b></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarText">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item text-center active">
        <a class="nav-link text-center" href="home">Početna <span class="sr-only">(current)</span></a>
     </li>
     <li class="nav-item text-center active">
        <a class="nav-link text-center" href="about">Uputstvo <span class="sr-only">(current)</span></a>
      </li>
      <?php 
      if($_SESSION['logged_in'] == 1){
      echo "
           <li class='nav-item dropdown' style='list-style-type: none !important'>
           <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
           Administracija <i class='bi bi-shield-check'></i>
           </a>
           <div class='dropdown-menu bg-dark' aria-labelledby='navbarDropdown'>
           <a class='dropdown-item nav-link text-center'  href='admin_main' style='width: auto; border-radius:0;'>Statistika Tiketa <i class='bi bi-card-checklist'></i></a>
           <a class='dropdown-item nav-link text-center'  href='admin_vremeOdziva_tic' style='width: auto; border-radius:0;'>Vreme Odziva Tiketa <i class='bi bi-clock'></i></a>
           <a class='dropdown-item nav-link text-center'  href='admin_dnevniIzvestaji' style='width: auto; border-radius:0;'>Dnevni Izveštaji <i class='bi bi-file-earmark-check'></i></a>
           ";
           if($_SESSION['role'] == "Supervisor" OR $_SESSION['role'] == "RootAdmin"){
             echo   "<a class='dropdown-item nav-link text-center'  href='admin_assignments_supervisor' style='width: auto; border-radius:0;'>Zadaci <i class='bi bi-file-earmark-check'></i></a>";
           }
           else{
            echo  "<a class='dropdown-item nav-link text-center'  href='admin_assignments_tech' style='width: auto; border-radius:0;'>Zadaci <i class='bi bi-file-earmark-check'></i></a>";
           }
             echo " </div>
              </li>";

            if($_SESSION['role'] == "RootAdmin"){
                 echo "
                  <li class='nav-item dropdown' style='list-style-type: none !important'>
                  <a class='nav-link dropdown-toggle text-danger' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                  Root Administrator <i class='bi bi-code-slash'></i>
                  </a>
                  <div class='dropdown-menu bg-dark' aria-labelledby='navbarDropdown'>
                  <a class='dropdown-item nav-link text-center text-danger'  href='rootAdmin' style='width: auto; border-radius:0;'>Main <i class='bi bi-code-slash'></i></a>
                  <a class='dropdown-item nav-link text-center text-danger'  href='rootAdmin_tickets' style='width: auto; border-radius:0;'>Tickets <i class='bi bi-calendar-week'></i></a>
                  <a class='dropdown-item nav-link text-center text-danger'  href='rootUsers' style='width: auto; border-radius:0;'>Users <i class='bi bi-person-bounding-box'></i></a>
                  </div>
                  </li>";
            }      
      }
    ?>
    </ul>
    <?php
    if($_SESSION['logged_in'] == 1)
    {
        echo "
           <li class='nav-item dropdown' style='list-style-type: none !important'>
           <a class='nav-link dropdown-toggle' href='#' id='navbarDropdown' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
           <img src='Slike/account.png'> ".roleBadge($_SESSION['role']) .$_SESSION['ime']." ".$_SESSION['prezime']." <small class='text-info'>[ PROFIL ]</small>
           </a>
           <div class='dropdown-menu bg-dark' aria-labelledby='navbarDropdown'>
           <a class='dropdown-item nav-link text-center'  href='/ITS/admin_account' style='width: auto; border-radius:0;'>Vaš Nalog <i class='bi bi-person'></i></i></a>
             <a class='dropdown-item nav-link text-center bg-danger'  href='/ITS/admin_logOut' style='width: auto; border-radius:0'>Odjavite se <i class='bi bi-person-x'></i></a>
           </div>
           </li>
     ";
    }
    else{
      echo "<span class='navbar-text btn_log'><a class='nav-link text-center' href='admin_logIn'>LogIn</a></span>";
    }
    ?>
    <span class="navbar-text aptiv-logo">
       <img src="aptiv.svg" alt="APTIV">
    </span>
  </div>
</nav>
