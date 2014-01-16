<?php
require 'vendor/autoload.php';


// set up database connection
use RedBean_Facade as R;
R::setup("mysql:host=mysql://$OPENSHIFT_MYSQL_DB_HOST:$OPENSHIFT_MYSQL_DB_PORT/;dbname=php",'adminUyLCLuc','JWmWpswfQHYg');
R::freeze(true);





var_dump(class_exists('\Slim\Slim'));
var_dump(class_exists('R'));
var_dump(class_exists('\RedBean\Facade'));
var_dump(class_exists('\RedBean\RedBean_Facade'));
var_dump(class_exists('RedBean_Facade'));


echo 'hello';

// initialize app
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
$app->run();
