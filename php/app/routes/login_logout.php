<?php

use RedBean_Facade as R;

$app->get('/logout', $authAdmin('admin'), function () use ($app) {
/*
    $app->setEncryptedCookie('loginUrl',null);
    $app->setEncryptedCookie('pwd','');
    */
    
    session_unset();
    
    
    $app->flash('info', 'Log out effettuato');
    $app->redirect($app->urlFor('login'));

})->name('logout');

$app->get('/login', function () use ($app) {
    // render login
    /*echo "<pre>";
    print_r($_SESSION);
    echo "</pre>";*/
    $app->render('login.html');
})->name('login');

$app->get('/login/send', function () use ($app) {
    $request = $app->request;
    $post = $request->get();
        
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
        session_write_close();
        
        if($loginUrl){
            if($loginUrl == 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login')
            || $loginUrl == 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('logout')){
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

})->name('login-send');

?>