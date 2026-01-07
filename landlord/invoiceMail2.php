<?php // DO any thing here
$email=$email222a;
        $to =$email222a;
        $subject = "Paymeny Request";
        $from = 'camaradeo@gmail.com';
        
        // To send HTML mail, the Content-type header must be set
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
        'Reply-To: '.$from."\r\n" .
        'X-Mailer: PHP/' . phpversion();
        
        // Compose a simple HTML email message
        $message = '<html><head>';
        $message = ' <meta name="viewport" content="width=device-width, initial-scale=1.0" />';
        $message = ' <meta name="x-apple-disable-message-reformatting" />';
        $message = ' <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        $message = ' <meta name="color-scheme" content="light dark" />';
        $message = ' <meta name="supported-color-schemes" content="light dark" />';
        $message = ' <title>CHIC Ltd</title>';
        $message = '<body">';
        $message .= '<div style="padding: 10px;border: 1px solid lightgray; text-align: center;width:500px;">
   <table>
    
       <tbody>
           
           <tr>
               <td>
                <p class="f-fallback sub"><b>'.$msg.'</b></p>
                <p class="f-fallback sub">Click on link following up to download Invoice</p>
               </td>
           </tr>
           <tr>
            <td>
             <p>For security reason , if you did not reserve, please ignore this email or <a href="mailto:info@itec.rw">contact support</a> if you have questions.</p>
             <p><a href="tel:250 788-620-612">Call us</a> </p>
             <p>Thanks,
             
             <!-- Sub copy -->
            </td>
        </tr>
           <tr>
               <td>
                   <hr>
                &copy; '.$year.'. All rights reserved.</br>
                ITEC Ltd<br>
                KN 1 Rd, Kigali-Rwanda.<br>
                Phone (+250) 788620612
               </td>
           </tr>
       </tbody>
   </table> 
   </div>';
        $message .= '</body></html>';

        // Sending email
        if(mail($to, $subject, $message, $headers)){
        $msg=" Check your E-mail $email ";
        }
?>


