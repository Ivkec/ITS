<?php 
include_once "main-functions.php";

 $mailSubject_closed_tic = "Ticketing - Rešen tiket";
 $mailBody_closed_tic = "
 <h1 style='text-align:center; color: #002080'>>> APTIV NS TICKETING SYSTEM <<</h1><br>
 <p>Poštovani/a,</p>
 <p>Tiket sa brojem ID-a: <span style='font-weight:bold;'>$tic_ID</span> je uspešno rešen od strane IT tehničara $ITime $ITprezime.</p>
 <br>
 <p><b>Komentari IT tehničara: </b></p>
 <p><b>$desc_solution</b></p><br>
 <center>
 <table style='border: 2px solid #002080; text-align:center; width: 60%; background: white;'>
 <tr>
   <th colspan='2' style='color: #002080; background: #809fff'><h3>Podaci o tiketu</h3></th>
 </tr>
 <tr style='border: 2px solid #002080; padding: 10px'>
   <th style='border: 2px solid #002080;'>Broj/ID tiketa:</th>
   <th style='border: 2px solid #002080;'>$br_tic</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Ime i prezime:</th>
   <th style='border: 2px solid #002080;'>$ime $prezime</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>E-mail:</th>
   <th style='border: 2px solid #002080;'>$email</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Lokacija:</th>
   <th style='border: 2px solid #002080;'>$lokacija</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Departman:</th>
   <th style='border: 2px solid #002080;'>$departman</th>
 </tr>
 <tr style='border: 2px solid #002080; '>
   <th style='border: 2px solid #002080;'>Vrsta tiketa:</th>
   <th style='border: 2px solid #002080;'>$tip_tiketa</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Tip problema:</th>
   <th style='border: 2px solid #002080;'>$tip_problema</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Opis:</th>
   <th style='border: 2px solid #002080;'>$opis_problema</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Datum rešavanja tiketa:</th>
   <th style='border: 2px solid #002080;'>".convertDateTime($date_closed)."</th>
 </tr>
</table>
 </center><br>
 <hr>
 <p>- Pozdrav, IT tim.</p>
 <img src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.aptiv.com%2Fimages%2Fdefault-source%2Femail-campaigns%2Faptiv_logo_color_rgb.png&f=1&nofb=1' alt='APTIV' style='width: auto; height: 20px; float: right;'>
 </div>
 ";

 $mailSubject_insert_tic = "Ticketing - Novi tiket";
 $mailBody_insert_tic = "
 <h1 style='text-align:center; color: #002080'>>> APTIV NS TICKETING SYSTEM <<</h1><br>
 <p>Poštovani/a $ime,</p>
 <p>Tiket $vrsta_tiketa-a sa ID-om: <span style='font-weight:bold;'>$tic_number</span> je uspešno poslat. IT tehničar će tiket preuzeti u najkraćem mogućem roku.</p>
 <br><center>
 <table style='border: 2px solid #002080; text-align:center; width: 60%; background: white;'>
 <tr>
   <th colspan='2' style='color: #002080; background: #809fff'><h3>Podaci o tiketu</h3></th>
 </tr>
 <tr style='border: 2px solid #002080; padding: 10px'>
   <th style='border: 2px solid #002080;'>Broj/ID tiketa:</th>
   <th style='border: 2px solid #002080;'>$tic_number</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Ime i prezime:</th>
   <th style='border: 2px solid #002080;'>$ime $prezime</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>E-mail:</th>
   <th style='border: 2px solid #002080;'>$email</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Lokacija:</th>
   <th style='border: 2px solid #002080;'>$lokacija</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Departman:</th>
   <th style='border: 2px solid #002080;'>$departman</th>
 </tr>
 <tr style='border: 2px solid #002080; '>
   <th style='border: 2px solid #002080;'>Vrsta tiketa:</th>
   <th style='border: 2px solid #002080;'>$vrsta_tiketa</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Tip problema:</th>
   <th style='border: 2px solid #002080;'>$tip_problema</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Opis:</th>
   <th style='border: 2px solid #002080;'>$opis</th>
 </tr>
 <tr style='border: 2px solid #002080;'>
   <th style='border: 2px solid #002080;'>Datum:</th>
   <th style='border: 2px solid #002080;'>".convertDateTime($date)."</th>
 </tr>
</table>
 </center><br>
 <hr>
 <p>- Pozdrav, IT tim.</p>
 <img src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.aptiv.com%2Fimages%2Fdefault-source%2Femail-campaigns%2Faptiv_logo_color_rgb.png&f=1&nofb=1' alt='APTIV' style='width: auto; height: 20px; float: right;'>
 </div>
 ";

 $mailSubject_reklama_tic = "Ticketing - Reklamacija tiketa";
 $mailBody_reklama_tic = "
       <h1 style='text-align:center; color: #002080'>>> APTIV NS TICKETING SYSTEM <<</h1><br>
       <p>Poštovani/a,</p>
       <p>Reklamacija za tiket sa ID-om: <span style='font-weight:bold;'>$broj_tic</span> je uspešno poslata.</p>
       <br>
       <p><b>Komentar reklamacije: </b></p>
       <p><b>$komentar</b></p><br>
       <center>
       <table style='border: 2px solid #002080; text-align:center; width: 60%; background: white;'>
       <tr>
         <th colspan='2' style='color: #002080; background: #809fff'><h3>Podaci o tiketu</h3></th>
       </tr>
       <tr style='border: 2px solid #002080; padding: 10px'>
         <th style='border: 2px solid #002080;'>Broj/ID tiketa:</th>
         <th style='border: 2px solid #002080;'>$broj_tic</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>Ime i prezime:</th>
         <th style='border: 2px solid #002080;'>$ime $prezime</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>E-mail:</th>
         <th style='border: 2px solid #002080;'>$email</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>Lokacija:</th>
         <th style='border: 2px solid #002080;'>$lokacija</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>Departman:</th>
         <th style='border: 2px solid #002080;'>$departman</th>
       </tr>
       <tr style='border: 2px solid #002080; '>
         <th style='border: 2px solid #002080;'>Vrsta tiketa:</th>
         <th style='border: 2px solid #002080;'>$vrsta_tiketa</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>Tip problema:</th>
         <th style='border: 2px solid #002080;'>$tip_problema</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>Opis:</th>
         <th style='border: 2px solid #002080;'>$opis</th>
       </tr>
       <tr style='border: 2px solid #002080;'>
         <th style='border: 2px solid #002080;'>Datum reklamacije:</th>
         <th style='border: 2px solid #002080;'>".convertDateTime($date)."</th>
       </tr>
     </table>
       </center><br>
       <hr>
       <p>- Pozdrav, IT tim.</p>
       <img src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.aptiv.com%2Fimages%2Fdefault-source%2Femail-campaigns%2Faptiv_logo_color_rgb.png&f=1&nofb=1' alt='APTIV' style='width: auto; height: 20px; float: right;'>
       </div>
  ";

    $mailSubject_changeTECH_tic = "Ticketing - Promena IT tehničara";
    $mailBody_changeTECH_tic = "
    <h1 style='text-align:center; color: #002080'>>> APTIV NS TICKETING SYSTEM <<</h1><br>
    <p>Poštovani/a,</p>
    <h3 style='color:#ff6666;'>OBAVEŠTENJE: Tiket sa ID-om: <span style='font-weight:bold;'>$tic_ID</span> je <span style='color: #e60000; text-decoration: underline; font-size: 24px;'>PROSLEĐEN</span> IT tehničaru <span style='color:#ffcc00;'>$tech_USERNAME</span> od strane tehničara <span style='color:#ffcc00;'>$ITime $ITprezime.</span></h3>
    <br>
    <center>
    <table style='border: 2px solid #002080; text-align:center; width: 60%; background: white;'>
    <tr>
      <th colspan='2' style='color: #002080; background: #809fff'><h3>Podaci o tiketu</h3></th>
    </tr>
    <tr style='border: 2px solid #002080; padding: 10px'>
      <th style='border: 2px solid #002080;'>Broj/ID tiketa:</th>
      <th style='border: 2px solid #002080;'>$br_tic</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>Ime i prezime:</th>
      <th style='border: 2px solid #002080;'>$ime $prezime</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>E-mail:</th>
      <th style='border: 2px solid #002080;'>$email</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>Lokacija:</th>
      <th style='border: 2px solid #002080;'>$lokacija</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>Departman:</th>
      <th style='border: 2px solid #002080;'>$departman</th>
    </tr>
    <tr style='border: 2px solid #002080; '>
      <th style='border: 2px solid #002080;'>Vrsta tiketa:</th>
      <th style='border: 2px solid #002080;'>$tip_tiketa</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>Tip problema:</th>
      <th style='border: 2px solid #002080;'>$tip_problema</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>Opis:</th>
      <th style='border: 2px solid #002080;'>$opis_problema</th>
    </tr>
    <tr style='border: 2px solid #002080;'>
      <th style='border: 2px solid #002080;'>Datum prosleđivanja tiketa:</th>
      <th style='border: 2px solid #002080;'>".convertDateTime($date_changeTECH)."</th>
    </tr>
   </table>
    </center><br>
    <hr>
    <p>- Pozdrav, IT tim.</p>
    <img src='https://external-content.duckduckgo.com/iu/?u=http%3A%2F%2Fwww.aptiv.com%2Fimages%2Fdefault-source%2Femail-campaigns%2Faptiv_logo_color_rgb.png&f=1&nofb=1' alt='APTIV' style='width: auto; height: 20px; float: right;'>
    </div>
    ";

  //ZA JS ALERT BOX
  $alertInfo_insert = "Uspešno ste kreirali nov tiket. Podatke o tiketu možete proveriti na Vašem mejlu.";
  $alertInfo_closed = $_SESSION['ime'].", ušpesno ste zatvorili tiket broj $br_tic";
  $alertInfo_reklama = "Uspešno ste poslali reklamaciju tiketa. Podatke o tiketu možete proveriti na Vašem mejlu.";
  $alertInfo_changeTECH = "Uspešno ste prosledili svoj tiket drugom IT tehnicaru.";

  //REDIRECT LINKOVI
  $redirect_insert = "/ITS/home";
  $redirect_closed = "/ITS/admin_oneTic_select?ticID=$br_tic";
  $redirect_reklama = "/ITS/home";
  $redirect__changeTECH = "/ITS/admin_oneTic_select?ticID=$br_tic";
/* ------------------------------------------------------------- */

//NIZOVI ZA MEJLOVE
  $mailBody = array('closed' => $mailBody_closed_tic, 'insert' => $mailBody_insert_tic, 'reklama' => $mailBody_reklama_tic, 'chengeTECH' => $mailBody_changeTECH_tic);
  $mailSubject = array('closed' => $mailSubject_closed_tic, 'insert' => $mailSubject_insert_tic, 'reklama' => $mailSubject_reklama_tic, 'chengeTECH' => $mailSubject_changeTECH_tic);
  $mailAlert = array('insert' => $alertInfo_insert, 'closed' => $alertInfo_closed, 'reklama' => $alertInfo_reklama, 'chengeTECH' => $alertInfo_changeTECH);
  $mailRedirect = array('insert' => $redirect_insert, 'closed' => $redirect_closed, 'reklama' => $redirect_reklama, 'chengeTECH' => $redirect__changeTECH);
?>
