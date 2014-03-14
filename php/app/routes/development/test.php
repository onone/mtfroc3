<?php

use RedBean_Facade as R;

$app->get('/test', function () use ($app) {
    // send response header for JSON content type
    
    
     //$app->flash('info', 'devi essere loggato');
            //$app->flashNow('info', 'devi essere loggato');
           // $app->flashKeep();
            //$app->redirect('login');
    /*$app->view()->setData(array('title' => 'sprout', 'body' => 'cippa'));
    
    $app->flash('info', 'devi essere loggato');*/
    echo 'cococo';
    //print_r( $app->config('entities'));
    
    
    $request = $app->request();
    
    $url = $app->urlFor('test');
    echo 'cococo';
    //print_r($request->getRootUri() . $url);
    
   // $app->render('test.twig');
})->name('test');



$app->get('/test_protected', $authAdmin('admin'), function () use ($app) {

    $app->render('home.html');

})->name('test_protected');

$app->get('/test_protected/te', $authAdmin('admin'), function () use ($app) {

    
                $app->redirect($app->urlFor('test'));

})->name('ttt');

?>