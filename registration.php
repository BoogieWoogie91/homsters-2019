<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
include 'phpqrcode/qrlib.php'; 
     
    // outputs image directly into browser, as PNG stream 
echo  QRcode::png('code data text', 'filename.png'); // creates file 


?>