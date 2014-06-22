<?php

use RedBean_Facade as R;

$app->get('/logout', $authAdmin('admin'), function () use ($app) {

    $app->setEncryptedCookie('loginUrl',null);
    $app->setEncryptedCookie('pwd','');
    $app->flash('info', 'Log out effettuato');
    $app->redirect($app->urlFor('login'));

})->name('logout');

$app->get('/login', function () use ($app) {
    
    $app->setEncryptedCookie('login get',date("H:i:s u"));
    // render login
    $app->render('login.html');
})->name('login');

$app->post('/login', function () use ($app) {
    $request = $app->request;
    
    $app->setEncryptedCookie('login post',date("H:i:s u"));
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

});

?>