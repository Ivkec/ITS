<?php 
require "vendor/autoload.php";;
require_once "db_conn.php";
session_start();

if(empty($_SESSION['logged_in']))
{
    header('Location: /ITS/admin_logIn');
    exit;
}

$sql = "SELECT daily_reports.*, users.*, daily_r_line.*, daily_r_loc.*, daily_r_pos.* FROM tits.daily_reports 
INNER JOIN tits.users ON daily_reports.id_card_tech = users.id
INNER JOIN tits.daily_r_line ON daily_r_line.id = daily_reports.id_line
INNER JOIN tits.daily_r_loc ON daily_r_loc.id = daily_reports.id_location
INNER JOIN tits.daily_r_pos ON daily_r_pos.id = daily_reports.id_position 
ORDER BY date_created DESC";
$query = mysqli_query($conn, $sql);


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet->getActiveSheet()->getDefaultColumnDimension()->setWidth(30);
$styleArray = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
    'borders' => [
        'outline' => [
            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        ],
    ],
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
        'rotation' => 90,
        'startColor' => [
            'argb' => 'ccccff',
        ],
        'endColor' => [
            'argb' => 'FFFFFFFF',
        ],
    ],
];
$styleArray2 = [
    'font' => [
        'bold' => true,
    ],
    'alignment' => [
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ],
];
$spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray($styleArray);

$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', '#');
$sheet->setCellValue('B1', 'ID Izveštaja');
$sheet->setCellValue('C1', 'IT Tehničar');
$sheet->setCellValue('D1', 'NS Lokacija');
$sheet->setCellValue('E1', 'Lokacija');
$sheet->setCellValue('F1', 'Linija');
$sheet->setCellValue('G1', 'Pozicija');
$sheet->setCellValue('H1', 'Kvar');
$sheet->setCellValue('I1', 'Način rešavanja');
$sheet->setCellValue('J1', 'Rešeno?');
$sheet->setCellValue('K1', 'Rešeno na');
$sheet->setCellValue('L1', 'Linija stala?');
$sheet->setCellValue('M1', 'Zastoj zapisan?');
$sheet->setCellValue('N1', 'Smena');
$sheet->setCellValue('O1', 'Vreme početka zastoja');
$sheet->setCellValue('O1', 'Vreme poziva');
$sheet->setCellValue('O1', 'Vreme početka rada IT');
$sheet->setCellValue('O1', 'Vreme kraja rada IT');
$sheet->setCellValue('O1', 'Vreme kraja zastoja');
$sheet->setCellValue('O1', 'Vreme trajanja naloga');
$sheet->setCellValue('O1', 'Vreme odziva');
$sheet->setCellValue('O1', 'Vreme rada IT');
$sheet->setCellValue('O1', 'Ako se radi VM da li su podaci uneseni u excel?');
$sheet->setCellValue('O1', 'Kontaktiran ME?');
$sheet->setCellValue('O1', 'Komentar');
$sheet->setCellValue('O1', 'Nabavka materijala');

$i = 2;
while($res = mysqli_fetch_assoc($query)){

  $rep_id = $res['id_rep'];
  $comp_loc = $res['company_loc'];
  $loc = $res['loc'];
  $name = $res['name'];
  $surname = $res['surname'];
  $role = $res['role'];
  $line = $res['line'];
  $pos = $res['position'];
  $glitch = $res['glitch'];
  $sd =  $res['short_description'];
  $solved =  $res['solved'];
  $solved_on =  $res['solved_on'];
  $ls =  $res['line_stopped'];
  $s_rc = $res['stagnation_recorded'];
  $shift = $res['shift'];
  $date_cr = $res['date_created'];
  $date_ed = $res['date_edited'];

  $vreme_pocetkaZ = $res['time_pz'];
  $vreme_poziva = $res['time_call'];
  $vreme_pocetkaIT = $res['time_pIT'];
  $vreme_krajaIT = $res['time_kIT'];
  $vreme_krajaZ = $res['time_kz'];
  $vreme_tr_naloga = $res['time_tn'];
  $vreme_odziva = $res['time_response'];
  $vreme_radaIT = $res['time_workIT'];
  $dataEXPexcel = $res['data_inserted_toEXC'];
  $ME_contact = $res['ME_contacted'];
  $komentar = $res['comment'];
  $materijal = $res['materijal'];
    

    $spreadsheet->getActiveSheet()->getStyle('A'.$i.':O'.$i)->applyFromArray($styleArray2);
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A'.$i , $i-1);
    $sheet->setCellValue('B'.$i, $id_izvestaja);
    $sheet->setCellValue('C'.$i, $it_tech);
    $sheet->setCellValue('D'.$i, $comp_loc);
    $sheet->setCellValue('E'.$i, $loc);
    $sheet->setCellValue('F'.$i, $line);
    $sheet->setCellValue('G'.$i, $pos);
    $sheet->setCellValue('H'.$i, $glitch);
    $sheet->setCellValue('I'.$i, $desc);
    $sheet->setCellValue('J'.$i, $solved);
    $sheet->setCellValue('K'.$i, $solved_on);
    $sheet->setCellValue('L'.$i, $line_stopped);
    $sheet->setCellValue('M'.$i, $stagnation_recorded);
    $sheet->setCellValue('N'.$i, $date_cr);
    $sheet->setCellValue('O'.$i, $shift);
    $i++;
}

$date = date('d-m-Y');
$file_name = "dnevniIzvestaji_".$date.".xlsx";
//SAVE FILE TO EXCEL
$writer = new Xlsx($spreadsheet);
$writer->save($file_name);


//DOWNLOAD FILE
       $f = $file_name;   

       $file = ("$f");

       $filetype=filetype($file);

       $filename=basename($file);

       header ("Content-Type: ".$filetype);

       header ("Content-Length: ".filesize($file));

       header ("Content-Disposition: attachment; filename=".$filename);

       readfile($file);

       //DELETE FILE FROM SERVER AFTER FILE HAS BEEN DOWNLOADED FROM USER
       unlink($file_name);      

       //REDIRECT BACK FROM SCRIPT 
       header("Location: admin_dnevniIzvestaji");
?>
