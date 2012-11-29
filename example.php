<?php
require_once("class.solusvm.php");

$svapi = new SolusVM_API('manage.buyvm.net', 'ZZZZZ-YYYYY-XXXXX', '5169ascacf6dd6ce3951e9a64b19c188a9jfrt84');
// $svapi->debug();
echo $svapi->info();