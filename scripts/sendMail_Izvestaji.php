<?php 
//Send Email Using PHPMailer
function sendMail($mailSubject, $mailBody, $user_email, $alertInfo, $redirect){
   
     require 'phpmailer/PHPMailerAutoload.php';
     $mail= new PHPMailer;
     $mail->Host = 'mail.delphiauto.com';
     $mail->Port = 25;
     $mail->isSMTP(false);
     $mail->SMTPAuth = false;
     $mail->Username = 'Ticketing';//Your Email Address
     $mail->setFrom('ns.ticketing@aptiv.com','Dnevni Izvestaji');
     $mail->addAddress($user_email);//Receiver Email
     //AKO ZELIMO DA POSALJEMO SVIM TEHNICARIMA MAIL, KAO PARAMETAR U FUNKCIJI MORA BITI TRUE 
    
     
     /*
              $mail->addAddress('rsnvslocalitns@Aptiv.com');
              
     */
     

     $mail->addReplyTo($user_email);
     $mail->isHTML(true);
     $mail->Subject = $mailSubject;
     $mail->Body = $mailBody;
     $mail->CharSet="UTF-8";
     
     if(!$mail->send())
     {
     // echo "<script>alert('Something went wrong, mail not sent :(');</script>";
     echo "<script>alert('Nešto nije u redu, mejl neuspešno poslat :( ERROR: $mail->ErrorInfo');</script>";

     echo "<script>alert('Ukoliko dobijete ovu poruku ne brinite, Vaš izvestaj/zadatak je uspešno prosleđen u bazu iako mejl ne radi, nema potrebe da kreirate duple!');
            location='home'
            </script>";
     
     //echo "Something went wrong :(";
     //echo $mail->ErrorInfo;
     }
     else
     {
		echo "<script>
		       alert('$alertInfo');
		       location='$redirect';
	      	</script>";
     	// echo "Email sent successfully";
     }
}

?>