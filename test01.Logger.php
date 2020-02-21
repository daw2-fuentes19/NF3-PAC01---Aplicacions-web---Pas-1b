<?php
require_once('class.Config.php');
require_once('class.Logger.php');

//Again, the implementation of this class is left to the user, but an
//example of how it could work will be provided in the code download
//that accompanies the book on wrox.com


$C = new Config();

$C->addConfig('LOGGER_FILE', 'C:/Windows/Temp/myapplication.log');
$C->addConfig('LOGGER_LEVEL', 75);

$A = new Logger($C);
//$A->logMessage($log);


  if(isset($_GET['fooid'])) {
    //not written to the log - the log level is too high
    $A->logMessage('A fooid is present', 100);
     //LOG_INFO is the default so this would get printed
    $A->logMessage('The value of fooid is ' .  $_GET['fooid']);
  } else {
    //This will also be written, and includes a module name
    $A->logMessage('No fooid supplied', 5, "Foo Module");
    
    throw new Exception('No foo id!');
}
?>
