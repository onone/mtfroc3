$app = new Slim(array
   (
        // Smarty
        'view' => new SmartyView(),
        'cookies.secret_key'  => 'MY_SALTY_PEPPER',
        'cookies.lifetime' => time() + (1 * 24 * 60 * 60), // = 1 day
        'cookies.cipher' => MCRYPT_RIJNDAEL_256,
        'cookies.cipher_mode' => MCRYPT_MODE_CBC
);

$app->map('/login', function () use ($app) {

            // Test for Post & make a cheap security check, to get avoid from bots
    if($app->request()->isPost() && sizeof($app->request()->post()) > 2)
    {
        // Don't forget to set the correct attributes in your form (name="user" + name="password")
        $post = (object)$app->request()->post();

        if(isset($post->user) && isset($post->passwort))
        {
            $app->setEncryptedCookie('my_cookie',$post->password);
            $app->redirect('admin');
        } 
        else
        {
            $app->redirect('login');
        }
    }
            // render login
    $app->render('default.tpl');

})->via('GET','POST')->name('login');

$authAdmin = function  ( $role = 'member') {

    return function () use ( $role ) {

        $app = Slim::getInstance('my_cookie');

        // Check for password in the cookie
        if($app->getEncryptedCookie('my_cookie',false) != 'YOUR_PASSWORD'){

            $app->redirect('/login');
        }
    };
};

$app->get('/admin', $authAdmin('admin'), function () use ($app) {

    $app->render('default.tpl', array
       (
           'siteTitle'   => $app->siteTitle,
           'pageTitle'   => 'Admin Control Panel',
           'mainTitle'   => 'Admin Control Panel',
           'subTemplate' => 'pages/admin.tpl'
       )
    );

})->name('admin');