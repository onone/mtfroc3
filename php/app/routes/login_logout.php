<?php

use RedBean_Facade as R;

$app->get('/logout', $authAdmin('admin'), function () use ($app) {

    $app->setEncryptedCookie('loginUrl',null);
    $app->setEncryptedCookie('pwd','');
    $app->flash('info', 'Log out effettuato');
    $app->redirect($app->urlFor('login'));

})->name('logout');

$app->map('/login', function () use ($app) {
    // Test for Post & make a cheap security check, to get avoid from bots
    
    //$isPost = $app->request()->isPost();
    $request = $app->request;
    $method = $request->getMethod();
    
    $app->setEncryptedCookie('time',time());
    //if($isPost === TRUE)
    if($method == 'POST'){
        
        // Don't forget to set the correct attributes in your form (name="user" + name="password")
        
        $post = $request->post();
        
        if(isset($post['user']) && isset($post['password']))
        {
            $app->setEncryptedCookie('user',$post['user']);
            $app->setEncryptedCookie('pwd',$post['password']);
            
            $loginUrl = $app->getEncryptedCookie('loginUrl',false);
            if($loginUrl){
                if($loginUrl == 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login')){
                    $loginUrl = 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client'));
                }
                $app->redirect($loginUrl);
            }else{
                $app->redirect('https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client')));
            }
            
            
        } 
        else
        {
            $app->redirect('https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login'));
        }
    }
    // render login
    $app->render('login.html');

})->via('GET','POST')->name('login');

?>