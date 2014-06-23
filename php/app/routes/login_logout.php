<?php

use RedBean_Facade as R;

$app->get('/logout', $authAdmin('admin'), function () use ($app) {
/*
    $app->setEncryptedCookie('loginUrl',null);
    $app->setEncryptedCookie('pwd','');
    */
    
    unset($_SESSION['user']);
    unset($_SESSION['pwd']);
    unset($_SESSION['loginUrl']);
    
    
    $app->flash('info', 'Log out effettuato');
    $app->redirect($app->urlFor('login'));

})->name('logout');

$app->get('/login', function () use ($app) {
    // render login
    $app->render('login.html');
})->name('login');

$app->post('/login', function () use ($app) {
    $request = $app->request;
    $post = $request->post();
        
    if(isset($post['user']) && isset($post['password']))
    {
        /*
        $app->setEncryptedCookie('user',$post['user']);
        $app->setEncryptedCookie('pwd',$post['password']);
        */
        $_SESSION['user'] = $post['user'];
        $_SESSION['pwd'] = $post['password'];
        
        //$loginUrl = $app->getEncryptedCookie('loginUrl',false);
        $loginUrl = '';
        if(isset($_SESSION['loginUrl'])){
            $loginUrl = $_SESSION['loginUrl'];
        }
        
        
        if($loginUrl){
            if($loginUrl == 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login')){
                $loginUrl = 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client'));
            }
            $app->redirect($loginUrl,302);
        }else{
            $app->redirect('https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client')),302);
        }
        
    } 
    else
    {
        $app->redirect('https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login'),302);
    }

});

?>