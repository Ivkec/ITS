<?php 
//CONVERT TO LIKE THIS 25.03.2021. 07:15
 function convertDateTime($dateTime){
    //convert date
 $date_y = substr($dateTime, 0, 4); //get year
 $date_m = substr($dateTime, 5, 2); //get month
 $date_d = substr($dateTime, 8, 2); //get day
 $date_time = substr($dateTime, -8, 5); //get TIME
 $fommat = $date_d.".".$date_m.".".$date_y.". ". $date_time;

 return $fommat;
 }

 //CONVERT TO LIKE THIS 25.03.2021.
 function convertDate($date){
   //convert date
$date_y = substr($date, 0, 4); //get year
$date_m = substr($date, 5, 2); //get month
$date_d = substr($date, 8, 2); //get day

$fommat = $date_d.".".$date_m.".".$date_y.".";

return $fommat;
}


 //CONVERT TO LIKE THIS 2021-03-24 07:39:02
 function convertDateTime2($dateTime){
    //convert date
 $date_y = substr($dateTime, 6, 4); //get year
 $date_m = substr($dateTime, 3, 2); //get month
 $date_d = substr($dateTime, 0, 2); //get day
 $date_time = substr($dateTime, 11, 8); //get TIME
 $fommat = $date_y."-".$date_m."-".$date_d." ". $date_time;

 return $fommat;
 }

 function roleBadge($role){
    switch($role){
       case 'RootAdmin':
       return "<span class='badge badge-danger'>".$role."</span> ";
       break;
       case 'Technician':
         return "<span class='badge badge-success'>".$role."</span> ";
         break;
         case 'Supervisor':
            return "<span class='badge badge-success' style='background:#1a75ff;'>".$role."</span> ";
            break;
      default:
      return "<span class='badge badge-secondary'>".$role."</span> ";
      break;
    }
 }







?>