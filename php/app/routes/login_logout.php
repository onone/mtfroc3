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
    var_dump('$app->request()->isPost():' . $app->request()->isPost());
    if($app->request()->isPost())// && sizeof($app->request()->post()) > 2
    {
        // Don't forget to set the correct attributes in your form (name="user" + name="password")
        $post = (object)$app->request()->post();

        var_dump('$app->request()->isPost():' . $app->request()->isPost());

        if(isset($post->user) && isset($post->password))
        {
            $app->setEncryptedCookie('user',$post->user);
            $app->setEncryptedCookie('pwd',$post->password);
            
            $loginUrl = $app->getEncryptedCookie('loginUrl',false);
            if($loginUrl){
                if($loginUrl == 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login')){
                    $loginUrl = 'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client'));
                }
                echo 'redirect:' . $loginUrl;
                die();
                //$app->redirect($loginUrl);
            }else{
                echo 'redirect:' .  'https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client'));
                die();
                //$app->redirect('https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('entityListUI',array('entityName' => 'client')));
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