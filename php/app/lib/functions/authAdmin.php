<?php

$authAdmin = function  ( $role_requested = 'all') {
    return function () use ( $role ) {
        
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
        $allPostVars = $request->post();
        // Check for password in the cookie
        $userSended = $app->getEncryptedCookie('user',false);
        $pwdSended = $app->getEncryptedCookie('pwd',false);
        $app->setEncryptedCookie('loginUrl','https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
/*
        echo "<pre>";
        var_dump($_REQUEST);
        var_dump($userSended);

echo 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        die($_SERVER['REQUEST_URI']);
        */
        
        switch($userSended){
            case 'lux':
                    $role = 'admin';
                    $pwd = 'lux';
                break;
            case 'fede':
                    $role = 'superadmin';
                    $pwd = 'slfe';
                break;
            default:
                if(is_null($userSended)){
                    $app->flash('info', 'Dati inseriti incorretti');
                }
                $app->redirect($app->urlFor('login'));
        }
        /*
        echo "<pre>";
        var_dump($app->getEncryptedCookie('pwd',false));
        */
        
        if($pwdSended != $pwd){
            $app->setEncryptedCookie('loginUrl','https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
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