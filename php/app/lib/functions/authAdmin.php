<?php

$authAdmin = function  ( $role_requested = 'all') {
    return function () use ( $role ) {
        
        $app = \Slim\Slim::getInstance();
        // Check for password in the cookie
        
        $user = $app->getEncryptedCookie('user',false);
        $app->setEncryptedCookie('loginUrl','http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);

        switch($user){
            case 'lux':
                    $role = 'admin';
                    $pwd = 'lux';
                break;
            case 'fede':
                    $role = 'superadmin';
                    $pwd = 'slfe';
                break;
            default:
                if(is_null($user)){
                    $app->flash('info', 'Dati inseriti incorretti');
                }
                $app->redirect($app->urlFor('login'));
        }
        
        if($app->getEncryptedCookie('pwd',false) != $pwd){
            $app->setEncryptedCookie('loginUrl','http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
            $app->flash('info', 'Dati inseriti incorretti');
            $app->redirect($app->urlFor('login'));
        }else{
            $app->setEncryptedCookie('role',$role);
            $app->view()->getEnvironment()->addGlobal('role', $role);
            /*
            if($app->getEncryptedCookie('role',false) != $role_requested){
                $app->flash('info', 'Non hai i permessi per accedere alla risorsa');
                $app->redirect('login');
            }
            */
        }
        
        
        
    };
};

?>