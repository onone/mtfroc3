<?php
session_cache_limiter(false);
session_start();

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
//if(strpos($_SERVER['SCRIPT_FILENAME'],'stickshift') !== FALSE){ // VARIABILE PRESENTE SU CLOUD9
if(strpos($_SERVER['HTTP_HOST'],'mftr3-c9-langeli.c9.io') !== FALSE){ // VARIABILE PRESENTE SU CLOUD9
    $app_mode = 'development';
}

// INIZIALIZZO  APP
$app = new \Slim\Slim(array(
    'mode' => $app_mode
));

$app->response->headers->set('Content-Type', 'text/html; charset=utf-8');

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
require APP_PATH . '/routes/resources/performancePaymentList.php';
require APP_PATH . '/routes/resources/DefaultEntity.php';
require APP_PATH . '/routes/resources/dump.php';

// STATS
require APP_PATH . '/routes/stats/revenuesStat.php';

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