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

$msg_echo = null;

// Was there a reCAPTCHA response?
if (isset($_POST["g-recaptcha-response"])) {
    $msg_echo.='capcha posted\n';
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
        
        $err_string = 'Not Set';
        //check for POST info from form
        $first = isset($_POST['first']) ? check_input($_POST['first']) : $err_string;
        $last = isset($_POST['last']) ? check_input($_POST['last']):$err_string;    
        $teleNum = isset($_POST['telephone']) ? check_input($_POST['telephone']):$err_string;
        $reply = isset($_POST['email']) ? check_input($_POST['email']):$err_string;
        
        $record = array($first,$last,$reply,$teleNum);
        //write to file
        $fp = fopen('../docs/Women retreat sign-up 2015.csv', 'a');
        fputcsv($fp, $record);
        fclose($fp);
        // Send
//        if(strlen($sword) == 0) {
//            if(mail($to, $subject, $message, $headers)){
//                echo ('Message sent successfully'); 
//            }
//            else{
//                echo('Not Sent: Server Error');
//            }
//        }
        /*test string
         * ?test=success&name=chris&email=christopher.ford@verizon.com&message=this+is+a+test
         */
        echo ('Registration Step: Directing you to payment'); 
}
else { 
  // Insert your code for showing an error message here 
  if($resp == null){
      echo('Response is null');
  }else{  
    echo ('Error: Security fail, Registration incomplete');
  }
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