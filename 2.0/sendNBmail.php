<?php

/* 
 * file with  NBFMC Template and send function
 */

function sendthis($header,$greeting,$body,$closing){
    
    //convert to html
    $message = '<html><body>';
    $message .='<table border="0" cellpadding="0" cellspacing="0" width="100%">'.
            '<tr>'.
                '<td width="50px" style="background-image: url("http://www.nbfmc.org/img/email_column.png");background-repeat: repeat-y; background-size: 100% auto; max-height: 180px">
                    
                </td>
                <td>
                    <table  width="100%">
                        <tr>
                            <td style="padding: 10px 0 10px 0;">
                             <img src="http://www.nbfmc.org/img/bg_img_email.png" alt="Header Image" style="display: block; width:80%; height: auto" />
                            </td>
                        </tr>
                        <tr >
                            <td style="padding: 10px 0 10px 0;color:#8C001A">
                                <table border="0" width="80%" style="border-spacing: 1em">
                                 <tr>
                                  <td>
                                   '.$greeting.'
                                  </td>
                                 </tr>
                                 <tr>
                                  <td>   
                                   '.$body.'
                                  </td>
                                 </tr>
                                 <tr>
                                  <td>
                                   '.$closing.'
                                  </td>
                                 </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td style="color:#8C001A">
                                <table width="80%" style="border-spacing: 1em">
                                     <tr>
                                      <td  style="padding: 5px 0 5px 0">
                                          Apostle James  &amp; Venus Ponder </br>
                                          New Beginning Faith Ministry Church </br>
                                          Highway 29 S</br>
                                          Newnan,GA 30263</br>
                                          <a href="http://www.nbfmc.org">www.nbfmc.org</a>
                                      </td>
                                     </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </body>
</html>';
    
    if(mail($to, $subject, $message, $headers)){
        echo ('Message sent successfully'); 
    }
    else{
        echo('Not Sent: Server Error');
    }
    
}

function getRecipiant($header){
    
}


?>