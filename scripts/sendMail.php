<?php 
//Send Email Using PHPMailer
function sendMail($mailSubject, $mailBody, $user_email, $user_email_admin, $user_email_admin_change, $allow_sentALL, $alertInfo, $redirect){
   
     require 'phpmailer/PHPMailerAutoload.php';
     $mail= new PHPMailer;
     $mail->Host = 'mail.delphiauto.com';
     $mail->Port = 25;
     $mail->isSMTP(false);
     $mail->SMTPAuth = false;
     $mail->Username = 'Ticketing';//Your Email Address
     $mail->setFrom('ns.ticketing@aptiv.com','Ticketing IT');
     $mail->addAddress($user_email);//Receiver Email
     $mail->addAddress($user_email_admin);//Receiver Email
     $mail->addAddress($user_email_admin_change);//Receiver Email

     //AKO ZELIMO DA POSALJEMO SVIM TEHNICARIMA MAIL, KAO PARAMETAR U FUNKCIJI MORA BITI TRUE 
     if($allow_sentALL){
/*
              $mail->addAddress('milan.karolic@aptiv.com');
              $mail->addAddress('sinisa.vas@aptiv.com');
              $mail->addAddress('miodrag.bogicevic@aptiv.com');
              $mail->addAddress('branislav.dolovac@aptiv.com');
              $mail->addAddress('milorad.bilkic@aptiv.com');
              $mail->addAddress('nikola.hrnjak@aptiv.com');
              $mail->addAddress('nebojsa.davidovac@aptiv.com');
              $mail->addAddress('bekir.maroli@aptiv.com');
              */
         }
     

     $mail->addReplyTo($user_email);
     $mail->isHTML(true);
     $mail->Subject = $mailSubject;
     $mail->Body = $mailBody;
     $mail->CharSet="UTF-8";
     
     if(!$mail->send())
     {
     // echo "<script>alert('Something went wrong, mail not sent :(');</script>";
     echo "<script>alert('Nešto nije u redu, mejl tiketa neuspešno poslat :( ERROR: $mail->ErrorInfo');</script>";

     echo "<script>alert('Ukoliko dobijete ovu poruku ne brinite, Vaš tiket je uspešno prosleđen u bazu iako mejl ne radi, nema potrebe da kreirate duple tikete!');
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