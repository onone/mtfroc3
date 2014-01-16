<?php
error_reporting(E_ALL);
require 'vendor/autoload.php';


// set up database connection
/*
use RedBean_Facade as R;
try {
    //R::setup("mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}",$config['username'], $config['password']);
    if(strpos($_SERVER['SCRIPT_FILENAME'],'stickshift') !== FLASE){
        R::setup("mysql:host=" . $_SERVER['SERVER_ADDR'] . ";dbname=php;port=3306",'langeli','');
    }else{
        R::setup("mysql:host=" . $OPENSHIFT_MYSQL_DB_HOST . ";dbname=php;port=$OPENSHIFT_MYSQL_DB_PORT",'adminUyLCLuc','JWmWpswfQHYg'); 
    }
    
    R::freeze(true);
} catch (Exception $e ) {
    echo $e->getMessage();
}
*/

echo phpversion();

echo '<pre>';
print_r($_SERVER);
echo '</pre>';

// initialize app
/*
$app = new \Slim\Slim();

// handle GET requests for /articles
$app->get('/client', function () use ($app) {  
    echo 'client';
  
  
  // query database for all client
  $client = R::find('client'); 
  
  // send response header for JSON content type
  $app->response()->header('Content-Type', 'application/json');
  
  // return JSON-encoded response body with query results
  echo json_encode(R::exportAll($client));
  
});

// run
$app->run();*/
