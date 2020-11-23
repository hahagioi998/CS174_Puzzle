<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('src/controllers/PresentController.php');
require_once('src/controllers/UpdateController.php');
$MPC = new \zihaolinqianhuifan\hw3\controllers\PresentController();
$ACC = new \zihaolinqianhuifan\hw3\controllers\UpdateController();

$m=isset($_REQUEST['m'])?$_REQUEST['m']:'';
switch($c=isset($_REQUEST['c'])?$_REQUEST['c']:''){
    case 'PresentController':
        $MPC->$m($_REQUEST['arg1']);
        break;
    case 'UpdateController':
        $ACC->$m($_REQUEST['arg1']);
    default:
	    header("Location: index.php?c=PresentController&m=display&arg1=default");
	    exit;
}











