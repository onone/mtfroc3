<?php

$authAdmin = function  ( $role = 'all') {
    return function () use ( $role ) {
        
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
        $allPostVars = $request->post();
        // Check for password in the cookie
        /*
        $sessionUser = $app->getEncryptedCookie('user',false);
        $pwdSended = $app->getEncryptedCookie('pwd',false);
        $app->setEncryptedCookie('loginUrl','https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
        */
        $_SESSION['loginUrl'] = 'https://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        
        $sessionUser = '';
        if(isset($_SESSION['user'])){
            $sessionUser = $_SESSION['user'];
            $sessionPassword = $_SESSION['pwd'];
        }

        
        switch($sessionUser){
            case 'lux':
                    $role = 'admin';
                    $pwd = 'lux';
                break;
            case 'fede':
                    $role = 'superadmin';
                    $pwd = 'slfe';
                break;
            default:
                if(is_null($sessionUser)){
                    $app->flash('info', 'Dati inseriti incorretti');
                }
                $app->redirect('https://' . $_SERVER['SERVER_NAME'] . $app->urlFor('login'),302);
        }

        
        if($sessionPassword != $pwd){
            $app->flash('info', 'Dati inseriti incorretti');
            $app->redirect($app->urlFor('login'));
        }else{
            //$app->setEncryptedCookie('role',$role);
            $_SESSION['role'] = $role;
            $app->view()->getEnvironment()->addGlobal('role', $role);
        }
        
        
        
    };
};

?>