## php-SolusVM-class

PHP5 SolusVM Wrapper class (project moved from http://code.google.com/p/php-solusvm-class/)

### Introduction =

*php-solusvm* is a PHP wrapper class around the SolusVM Client API (http://wiki.solusvm.com/index.php/API:Client) for reading individual VPS status.

### How to use?

    <?php
    // include required files
    require_once("class.solusvm.php");
    
    // instantiate class
    $svapi = new SolusVM_API($host, $api_secret, $api_key);
    
    // set username
    $svapi->info();
    
    // reboot VPS
    $svapi->reboot();
    
    // VPS status
    $svapi->status();
    
    // similarly $obj-boot() and $obj-shutdown()
    ?>