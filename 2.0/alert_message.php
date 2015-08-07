<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

if(isset($_GET['auth']) &&  $_GET['auth'] == 'nbfmc_message'){
    $message = array('alert' => '&#000;');
    echo json_encode($message,JSON_FORCE_OBJECT);
}else{
    echo 'Authentication Failed';
}
?>