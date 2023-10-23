<?php

include 'credentials.php';
// require 'vendor/autoload.php';
function custom_autoloader($class) {
    $Dir = dirname(__DIR__);
    include '/home/dh_uey5n8/imscrm.com/dbobjects/' . $class . '.php';
  }
  
  spl_autoload_register('custom_autoloader');
  $Aserv     = 'mysql.imscrm.com';
  $Auser     = 'imscrm';
  $Apass = 'Y2C7Q2iKQ{E#';
  $Aname      = 'imscrm';
  $DBConn = \dbi::sconnect($Aserv, $Auser, $Apass, $Aname);