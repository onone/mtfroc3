<?php

/*
// error handler function
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }

    switch ($errno) {
    case E_USER_ERROR:
        echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
        echo "  Fatal error on line $errline in file $errfile";
        echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
        echo "Aborting...<br />\n";
        exit(1);
        break;

    case E_USER_WARNING:
        echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;

    case E_USER_NOTICE:
        echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;

    default:
        echo "Unknown error type: [$errno] $errstr<br />\n";
        break;
    }

    // Don't execute PHP internal error handler 
    return true;
}

// function to test the error handling
function scale_by_log($vect, $scale)
{
    if (!is_numeric($scale) || $scale <= 0) {
        trigger_error("log(x) for x <= 0 is undefined, you used: scale = $scale", E_USER_ERROR);
    }

    if (!is_array($vect)) {
        trigger_error("Incorrect input vector, array of values expected", E_USER_WARNING);
        return null;
    }

    $temp = array();
    foreach($vect as $pos => $value) {
        if (!is_numeric($value)) {
            trigger_error("Value at position $pos is not a number, using 0 (zero)", E_USER_NOTICE);
            $value = 0;
        }
        $temp[$pos] = log($scale) * $value;
    }

    return $temp;
}

// set to the user defined error handler
$old_error_handler = set_error_handler("myErrorHandler");*/


define('START_TIME',microtime(TRUE));
$GLOBALS['timings'][] = array('start' => microtime(TRUE));

// AUTOLOAD DI COMPOSER
if(file_exists(__DIR__ . '/vendor/autoload.php')){
    try {
        require __DIR__ . '/vendor/autoload.php';
    } catch (Exception $e ) {
        echo $e->getMessage();
    }
}

use RedBean_Facade as R;



// MODALITA DI ESECUZIONE
$app_mode = 'production';
if(strpos($_SERVER['SCRIPT_FILENAME'],'stickshift') !== FALSE){ // VARIABILE PRESENTE SU CLOUD9
    $app_mode = 'development';
}

// INIZIALIZZO  APP
$app = new \Slim\Slim(array(
    'mode' => $app_mode
));

// CONFIGURAZIONE
include __DIR__ . '/app/config/config.php';

// EXCEPTIONS
require APP_PATH . '/lib/exceptions.php';

// FUNZIONI
require APP_PATH . '/lib/functions/authAdmin.php';
require APP_PATH . '/lib/functions/entityRepresentation.php';

// ROTTE
require APP_PATH . '/routes/login_logout.php';

require APP_PATH . '/routes/custom/client.php';
require APP_PATH . '/routes/DefaultEntityUI.php';
require APP_PATH . '/routes/DefaultEntityModal.php';
require APP_PATH . '/routes/resources/DefaultEntity.php';


require APP_PATH . '/routes/resources/XEditable.php';


require APP_PATH . '/routes/development/test.php'; // ROTTE DI TEST
require APP_PATH . '/routes/development/populate.php'; // POPOLAZIONE DB

// ESEGUO APP
$app->run();

$GLOBALS['timings'][] = array('End' . __LINE__ => (microtime(TRUE)-START_TIME));

if( isset($_REQUEST['print_bm']) ){ //$app_mode == 'development' && ( strpos($_SERVER['REQUEST_URI'],'resources') === FALSE || )
    foreach($GLOBALS['timings'] as $data){
        foreach($data as $k => $v){
            echo '<br>' . $k . ':' . $v;
        }
    }
}

?>