<?php

use RedBean_Facade as R;

if($app_mode == 'development'){
    define('BASE_URL','/php');
}else{
    define('BASE_URL','');
}

define('APP_PATH',dirname(__DIR__));


// Only invoked if mode is "production"
$app->configureMode('production', function () use ($app) {
    
    include 'entities.php';
    $app->config(array(
        'log.enable' => false,
        'debug' => true,
        'view' => new \Slim\Views\Twig(),
        'cookies.secret_key'  => 'LUX_PEPPER',
        'cookies.lifetime' => time() + (1 * 24 * 60 * 60), // = 1 day
        'cookies.cipher' => MCRYPT_RIJNDAEL_256,
        'cookies.cipher_mode' => MCRYPT_MODE_CBC,
        'templates.path' => APP_PATH .  '/views'
        ));
    /*
    $app->config(array(
        'log.enable' => true,
        'debug' => false,
        'view' => new \Slim\Views\Twig(),
        'cookies.secret_key'  => 'LUX_PEPPER_PROD',
        'cookies.lifetime' => time() + (1 * 24 * 60 * 60), // = 1 day
        'cookies.cipher' => MCRYPT_RIJNDAEL_256,
        'cookies.cipher_mode' => MCRYPT_MODE_CBC,
        'templates.path' => APP_PATH . '/views'
    ));
    */
});

// Only invoked if mode is "development"
$app->configureMode('development', function () use ($app) {
    
    include 'entities.php';
    
    $app->config(array(
        'log.enable' => false,
        'debug' => true,
        'view' => new \Slim\Views\Twig(),
        'cookies.secret_key'  => 'LUX_PEPPER',
        'cookies.lifetime' => time() + (1 * 24 * 60 * 60), // = 1 day
        'cookies.cipher' => MCRYPT_RIJNDAEL_256,
        'cookies.cipher_mode' => MCRYPT_MODE_CBC,
        'templates.path' => APP_PATH .  '/views',
        //'locales.path' => APP_PA TH .'/i18n/',
        //'entities' => $entities
        ));
});


$app->add(new \Slim\Middleware\SessionCookie(array(
    'expires' => '20 minutes',
    'path' => '/',
    'domain' => null,
    'secure' => false,
    'httponly' => false,
    'name' => 'slim_session',
    'secret' => 'CHANGE_ME',
    'cipher' => MCRYPT_RIJNDAEL_256,
    'cipher_mode' => MCRYPT_MODE_CBC
)));

// DB CONNECTION
try {
    if($app_mode == 'development'){
        R::setup("mysql:host=" . $_SERVER['SERVER_ADDR'] . ";dbname=php;port=3306",'langeli','');
        R::freeze(true);
    }else{
        R::setup("mysql:host=" . $_SERVER['OPENSHIFT_MYSQL_DB_HOST'] . ";dbname=php;port=" . $_SERVER['OPENSHIFT_MYSQL_DB_PORT'],        $_SERVER['OPENSHIFT_MYSQL_DB_USERNAME'],        $_SERVER['OPENSHIFT_MYSQL_DB_PASSWORD']); 
        R::freeze(true);
    }
    
    
} catch (Exception $e ) {
    echo $e->getMessage();
}
/*
$twig = $app->view()->getEnvironment(); 
$twig->addGlobal('entitiesConfig', $app->config('entities'));
*/

date_default_timezone_set('Europe/Rome');

// TWIG CONFIG

$app->view()->parserOptions = array(
    'debug' => true,
    //'cache' => dirname(__FILE__) . '/cache'
);
$app->view()->parserExtensions = array(
    new \Slim\Views\TwigExtension(),
    new Twig_Extension_Debug()
);

foreach ($app->config('entities') as $entityName => $entityData) {
    if(isset($entityData['representation'])) $ERepresentation[$entityName] = $entityData['representation'];
}

$app->view()->getEnvironment()->addGlobal('entitiesConfiguration',  $app->config('entities'));
$app->view()->getEnvironment()->addGlobal('entitiesRepresentations',  $ERepresentation);




?>