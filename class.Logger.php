<?php

class Logger {

  private $hLogFile;
  private $logLevel;

  //Log Levels.  The higher the number, the less severe the message
  //Gaps are left in the numbering to allow for other levels
  //to be added later
  public $DEBUG     = 100;
  public $INFO      = 75;
  public $NOTICE    = 50;
  public $WARNING   = 25;
  public $ERROR     = 10;
  public $CRITICAL  = 5;
  
  //Note: private constructor.  Class uses the singleton pattern
   public function __construct($c) {
    
    //This is pseudo code that fetches a hash of configuration information
    //Implementation of this is left to the reader, but should hopefully
    //be quite straight-forward.



    $cfg = $c->getConfigFile();  
    $aaa = $c->getConfigLevel();
    /* If the config establishes a level, use that level,
       otherwise, default to INFO
    */
    $this->logLevel = isset($aaa) ? 
                          $aaa : 
                          $this->INFO;

    //We must specify a log file in the config
    if(! ( isset($cfg) && strlen($cfg)) ) {
      throw new Exception('No log file path was specified ' .
                          'in the system configuration.');
    }
    
    $logFilePath = $cfg;
    
    //Open a handle to the log file.  Suppress PHP error messages.
    //We'll deal with those ourselves by throwing an exception.
    $this->hLogFile = @fopen($logFilePath, 'a+');
    
    if(! is_resource($this->hLogFile)) {
      throw new Exception("The specified log file $logFilePath " .
                   'could not be opened or created for ' .
                   'writing.  Check file permissions.');
    }
    
    //Set encoding type to ISO-8859-1
   //() stream_encoding($this->hLogFile, 'iso-8859-1');
  }
  
  public function __destruct() {
    if(is_resource($this->hLogFile)) {
      fclose($this->hLogFile);
    }
  }

  public static function getInstance() {
  
    static $objLog;
    
    if(!isset($objLog)) {
      $objLog = new Logger();
    }
    
    return $objLog;
  }

  public function logMessage($msg, $logLevel = 75, $module = null) {

    if($logLevel > $this->logLevel) {
      return;
    }

    /* If you haven't specifed your timezone using the 
       date.timezone value in php.ini, be sure to include
       a line like the following.  This can be omitted otherwise.
    */
    date_default_timezone_set('America/New_York');

    $time = strftime('%x %X', time());
    $msg = str_replace("\t", '    ', $msg);
    $msg = str_replace("\n", ' ', $msg);
  
    $strLogLevel = $this->levelToString($logLevel);
    
    if(isset($module)) {
      $module = str_replace("\t", '    ', $module);
      $module = str_replace("\n", ' ', $module);
    }
    
    //logs: date/time loglevel message modulename
    //separated by tabs, new line delimited
    $logLine = "$time\t$strLogLevel\t$msg\t$module\n";
    fwrite($this->hLogFile, $logLine);
  }
  
  public static function levelToString($logLevel) {
    switch ($logLevel) {
      case 100:
        return 'Logger::DEBUG';
        break;
      case 75:
        return 'Logger::INFO';
        break;
      case 50:
        return 'Logger::NOTICE';
        break;
      case 25:
        return 'Logger::WARNING';
        break;
      case 10:
        return 'Logger::ERROR';
        break;
      case 5:
        return 'Logger::CRITICAL';
        break;
      default:
        return '[unknown]';
    }
  }
}
?>
