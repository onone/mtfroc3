<?php

if(file_exists(__DIR__ . '/vendor/autoload.php')){
    try {
        require __DIR__ . '/vendor/autoload.php';
    } catch (Exception $e ) {
        echo $e->getMessage();
    }
}

// set up database connection
use RedBean_Facade as R;
try {
    if(strpos($_SERVER['SCRIPT_FILENAME'],'stickshift') !== FALSE){
        R::setup("mysql:host=" . $_SERVER['SERVER_ADDR'] . ";dbname=php;port=3306",'langeli','');
        //R::setup('sqlite:/tmp/dbfile.txt','user','password');
    }else{
        R::setup("mysql:host=" . $_SERVER['OPENSHIFT_MYSQL_DB_HOST'] . ";dbname=php;port=" . $_SERVER['OPENSHIFT_MYSQL_DB_PORT'] . ",        $_SERVER['OPENSHIFT_MYSQL_DB_USERNAME'],        $_SERVER['OPENSHIFT_MYSQL_DB_PASSWORD']); 
    }
    
    R::freeze(true);
} catch (Exception $e ) {
    echo $e->getMessage();
}


echo '<pre>';
print_r(R::find('client'));
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
$app->run();
*/
?>