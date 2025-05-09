<?php

// check if running on local host - if not assume running on infinityfree server
if ($_SERVER['HTTP_HOST'] === 'localhost')
{
    // info for localhost DB
    //echo ("<script>console.log('THIS IS LOCAL HOST');</script>");
    $serverName = 'localhost';
    $username = 'postalAxolotl';
    $password = 'StandardBoxRateAxolotl';
    $databaseName = 'stampcollection';
    $ISLOCAL = 1;
} 
else
{
    // info for infinityfree DB
    //echo ("<script>console.log('THIS IS NOT LOCAL HOST');</script>");
    $serverName = 'sql100.infinityfree.com';
    $username = 'if0_38272035';
    $password = 'remoteAxolotl';
    $databaseName = 'if0_38272035_stamp_app';
    $ISLOCAL = 0;
}

?>