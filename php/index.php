<?php
require 'vendor/autoload.php';


// set up database connection

use RedBean_Facade as R;
try {
    //R::setup("mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}",$config['username'], $config['password']);
    //R::setup("mysql:host=" . $_SERVER['SERVER_ADDR'] . ";dbname=php;port=3306",'langeli','');
    R::setup("mysql:host=" . $OPENSHIFT_MYSQL_DB_HOST . ";dbname=php;port=$OPENSHIFT_MYSQL_DB_PORT",'adminUyLCLuc','JWmWpswfQHYg');
    R::freeze(true);
} catch (Exception $e ) {
    echo $e->getMessage();
}




echo '<pre>';
print_r(R::find('client'));
echo '</pre>';

$connessione = mysql_connect("127.9.24.129:3306", 'langeli')
        or die("Connessione non riuscita: " . mysql_error());
    print ("Connesso con successo");
    mysql_close($connessione);



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
