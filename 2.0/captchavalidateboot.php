<?php

/**
 * Sample PHP code to use reCAPTCHA V2.
 *
 * @copyright Copyright (c) 2014, Google Inc.
 * @link      http://www.google.com/recaptcha
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
date_default_timezone_set("America/New_York");

require_once "recaptchalib.php";

// Register API keys at https://www.google.com/recaptcha/admin
$secret = "6LfyswATAAAAAGkhZY4UFnSZJTLP3UD-6jFVzBml"; 
// The response from reCAPTCHA
$resp = null;
// The error code from reCAPTCHA, if any
$error = null;

$reCaptcha = new ReCaptcha($secret);

// Was there a reCAPTCHA response?
if (isset($_POST["g-recaptcha-response"])) {
    $resp = $reCaptcha->verifyResponse(
            $_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]
    );
} else {
    if (isset($_GET["test"])) {
        $resp = new ReCaptchaResponse();
        $resp->success = true;
    }
}

if($resp != null && $resp->success ) { 
        
        /* Set e-mail recipient */
        $to = 'nbfministries@gmail.com';
        //check for POST info from form
        $name = isset($_POST['name']) ? check_input($_POST['name']) : 'Illegel Name';
        $subject = isset($_POST['subject']) ? check_input($_POST['subject']):'New Message from NBFMC.org' ;    
        $text = isset($_POST['message']) ? check_input($_POST['message']):'Illegal Message Contact Developer';
        $reply = isset($_POST['email']) ? check_input($_POST['email']):'nbfministries@gmail.com';
        
        //email header
        $headers = 'From: webform@nbfmc.org' . "\r\n" .
            'Reply-To: '.$reply. "\r\n" .
            'Bcc: vbponder@bellsouth.net'. "\r\n".
            'MIME-Version: 1.0' . "\r\n".
            'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        
        //Spam check
        $sword = '';//check_input($_POST['my_secur']);
        
        // The text
        $text = str_replace("\n.", "\n..", $text);

        // In case any of our lines are larger than 70 characters, we should use wordwrap()
        $text = wordwrap($text, 70, "\r\n");
        
        //convert to html
        $message = '<html><body>';
        //$message .= '<div style="background: #F6F6F6 url(www.nbfmc.org/img/Blue-sky-cross_1005_1024x569.jpg) no-repeat center center fixed">';
        $message .= '<div">';
        $message .= '<img src=http://www.nbfmc.org/img/NBFMLogo.gif style="width:192px; height:195px; padding:2px 50px" alt="NBFMC Crest" />';
        //$message .= '</div><div>';
        $message .= '<h2 align="center" style="color:#8C001A">You have a message from a visitor of NBFMC.org!</h2>';
        $message .= '<table cellpadding="10" align="center">';
        $message .= '<tr><td style="color:#8C001A"> Name: </td><td> '.$name.' </td></tr>';
        $message .= '<tr><td style="color:#8C001A"> Subject: </td><td> '.$subject.' </td></tr></table>';
        $message .= '<p align="center">'.$text.'</p>';//</div>';
        $message .= '<p align="center" style="color:#8C001A"> Sent '.date("F j, Y \a\t g:i a").' EST</p>'
                . '<p align="center" style="color:#8C001A">'
                . 'This message was sent because a visitor filled out the contact form on NBFMC.org.</p>';
        $message .= '<p align="center" style="color:#8C001A">'
                . 'If you have any question please contact web developer 1-678-457-4175 .</p></div>';
        $message .= '</body></html>';

        // Send
        if(strlen($sword) == 0) {
            if(mail($to, $subject, $message, $headers)){
                echo ('Message sent successfully'); 
            }
            else{
                echo('Not Sent: Server Error');
            }
        }
        /*test string
         * ?test=success&name=chris&email=christopher.ford@verizon.com&message=this+is+a+test
         */
        
}
else { 
  // Insert your code for showing an error message here 
    
  echo ('Error: Security fail, Message not sent');
      
}
//header("Location: ".$_REQUEST["returnUrl"]);
/* Functions we used */
function check_input($data, $problem='')
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>