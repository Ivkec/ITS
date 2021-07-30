<?php
//all tickets -----------------------------------------------------------
$sql1 = "SELECT * FROM tits.tickets WHERE status='NOV' ORDER BY date_created DESC";
$sql2 = "SELECT * FROM tits.tickets WHERE status='OTVOREN' ORDER BY date_created DESC";
$sql3 = "SELECT * FROM tits.tickets WHERE status='ZATVOREN' ORDER BY date_created DESC";
$sql4 = "SELECT * FROM tits.tickets WHERE status='REKLAMACIJA' ORDER BY date_created DESC";

$sql5 = "SELECT * FROM tits.tickets WHERE status='NOV' AND location='NS1' ORDER BY date_created DESC";
$sql6 = "SELECT * FROM tits.tickets WHERE status='NOV' AND location='NS2' ORDER BY date_created DESC";
$sql7 = "SELECT * FROM tits.tickets WHERE status='OTVOREN' AND location='NS1' ORDER BY date_created DESC";
$sql8 = "SELECT * FROM tits.tickets WHERE status='OTVOREN' AND location='NS2' ORDER BY date_created DESC";
$sql9 = "SELECT * FROM tits.tickets WHERE status='ZATVOREN' AND location='NS1' ORDER BY date_created DESC";
$sql10 = "SELECT * FROM tits.tickets WHERE status='ZATVOREN' AND location='NS2' ORDER BY date_created DESC";
$sql11 = "SELECT * FROM tits.tickets WHERE status='REKLAMACIJA' AND location='NS1' ORDER BY date_created DESC";
$sql12 = "SELECT * FROM tits.tickets WHERE status='REKLAMACIJA' AND location='NS2' ORDER BY date_created DESC";

$sql13 = "SELECT * FROM tits.tickets WHERE status='NOV' AND type_tic='INCIDENT' ORDER BY date_created DESC";
$sql14 = "SELECT * FROM tits.tickets WHERE status='NOV' AND type_tic='ZAHTEV' ORDER BY date_created DESC";
$sql15 = "SELECT * FROM tits.tickets WHERE status='OTVOREN' AND type_tic='INCIDENT' ORDER BY date_created DESC";
$sql16 = "SELECT * FROM tits.tickets WHERE status='OTVOREN' AND type_tic='ZAHTEV' ORDER BY date_created DESC";
$sql17 = "SELECT * FROM tits.tickets WHERE status='ZATVOREN' AND type_tic='INCIDENT' ORDER BY date_created DESC";
$sql18 = "SELECT * FROM tits.tickets WHERE status='ZATVOREN' AND type_tic='ZAHTEV' ORDER BY date_created DESC";
$sql19 = "SELECT * FROM tits.tickets WHERE status='REKLAMACIJA' AND type_tic='INCIDENT' ORDER BY date_created DESC";
$sql20 = "SELECT * FROM tits.tickets WHERE status='REKLAMACIJA' AND type_tic='ZAHTEV' ORDER BY date_created DESC";


//TIKETI JEDNOG KORISNIKA (MOJI TIKETI)
$user_id =  $_SESSION['user_id'];
$sql_u_o = "SELECT * FROM tits.tickets WHERE status='OTVOREN' AND IT_tech_ID='$user_id' ORDER BY date_created DESC";
$sql_u_z = "SELECT * FROM tits.tickets WHERE status='ZATVOREN' AND IT_tech_ID='$user_id' ORDER BY date_created DESC";
$sql_u_r = "SELECT * FROM tits.tickets WHERE status='REKLAMACIJA' AND IT_tech_ID='$user_id' ORDER BY date_created DESC";


//ALL TICKETS 
$sqlALL = "SELECT * FROM tits.tickets";

$result1 = mysqli_query($conn, $sql1);
$result2 = mysqli_query($conn, $sql2);
$result3 = mysqli_query($conn, $sql3);
$result4 = mysqli_query($conn, $sql4);
$result5 = mysqli_query($conn, $sql5);
$result6 = mysqli_query($conn, $sql6);
$result7 = mysqli_query($conn, $sql7);
$result8 = mysqli_query($conn, $sql8);
$result9 = mysqli_query($conn, $sql9);
$result10 = mysqli_query($conn, $sql10);
$result11 = mysqli_query($conn, $sql11);
$result12 = mysqli_query($conn, $sql12);
$result13 = mysqli_query($conn, $sql13);
$result14 = mysqli_query($conn, $sql14);
$result15 = mysqli_query($conn, $sql15);
$result16 = mysqli_query($conn, $sql16);
$result17 = mysqli_query($conn, $sql17);
$result18 = mysqli_query($conn, $sql18);
$result19 = mysqli_query($conn, $sql19);
$result20 = mysqli_query($conn, $sql20);

$res1 =  mysqli_num_rows($result1);
$res2 =  mysqli_num_rows($result2);
$res3 =  mysqli_num_rows($result3);
$res4 =  mysqli_num_rows($result4);
$res5 =  mysqli_num_rows($result5);
$res6 =  mysqli_num_rows($result6);
$res7 =  mysqli_num_rows($result7);
$res8 =  mysqli_num_rows($result8);
$res9 =  mysqli_num_rows($result9);
$res10 =  mysqli_num_rows($result10);
$res11 =  mysqli_num_rows($result11);
$res12 =  mysqli_num_rows($result12);
$res13 =  mysqli_num_rows($result13);
$res14 =  mysqli_num_rows($result14);
$res15 =  mysqli_num_rows($result15);
$res16 =  mysqli_num_rows($result16);
$res17 =  mysqli_num_rows($result17);
$res18 =  mysqli_num_rows($result18);
$res19 =  mysqli_num_rows($result19);
$res20 =  mysqli_num_rows($result20);

//ALL
$resultALL = mysqli_query($conn, $sqlALL);
$res_ALL =  mysqli_num_rows($resultALL);

/* end sql tickets ---------------------------------*/

//TIKETI JEDNOG KORISNIKA (MOJI TIKETI)
$result_u_o = mysqli_query($conn, $sql_u_o);
$result_u_z = mysqli_query($conn, $sql_u_z);
$result_u_r = mysqli_query($conn, $sql_u_r);
//TIKETI JEDNOG KORISNIKA (MOJI TIKETI)
$res_u1 =  mysqli_num_rows($result_u_o);
$res_u2 =  mysqli_num_rows($result_u_z);
$res_u3 =  mysqli_num_rows($result_u_r);




?>