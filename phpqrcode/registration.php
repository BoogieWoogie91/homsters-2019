<?php

include 'qrlib.php'; 
     
    // outputs image directly into browser, as PNG stream 
QRcode::png('code data text', 'filename.png'); // creates file 