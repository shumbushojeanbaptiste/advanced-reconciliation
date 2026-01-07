<?php 
$mont1=$_POST['month1'];
$siteq=$_POST['ste1'];
$email_n=$email222a;
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;
    use PHPMailer\PHPMailer\Exception;
    
require 'phpMail/PHPMailer/src/Exception.php';
require 'phpMail/PHPMailer/src/PHPMailer.php';
require 'phpMail/PHPMailer/src/SMTP.php';

    $email=$email_n;
    
    $mail = new PHPMailer(true);
 
    try
    {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'xode.rw';
        $mail->SMTPAuth = true;
        $mail->Username = 'info@itec.rw';
        $mail->Password = 'M00dle!!';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
 
        $mail->setFrom('info@itec.rw', 'CHIC Ltd');
        $mail->addAddress('shumbushojean1@gmail.com'); // Add a recipient
        $mail->addReplyTo('info@itec.rw', 'CHIC');
 
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Payment Request';
 $clickHere="View Invoice";
        // mention download link in the email
        $email_content = "<b>.$msg.</b><br>click the link below to download your files: <br />";
        $base_url = "https://xode.rw/xode-invoice?code=$rnd".$fetch['ten_ref_no']."&xode=".$xxodee;
        // $email_content .= "<a href='" . $base_url . "' id=link3 >" .$clickHere. "</a>";
        $mail->Body = $email_content;
 
        $mail->send();
       //echo '<p>Link to download files has been sent to your email address: ' . $email . '</p>';
    }
    catch (Exception $e)
    {
        die("Message could not be sent. Mailer Error: " . $mail->ErrorInfo);
    }

echo 1;      






?>
