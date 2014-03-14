<?php

use RedBean_Facade as R;

$app->get('/entityModal/:entityName', $authAdmin('admin'), function ($entityName) use ($app) {  
    
    try{
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new EntitiesNotInConfigException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new EntityNotConfiguredException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        $app->view()->setData(array(
            'entityConfiguration' => $entityConfiguration
        ));
        
        $get = $app->request()->get();
        if(isset($get['ED'])){
            $app->view->appendData(array(
                'ED' => $get['ED']
            ));
        }
        
        $app->render('/entities/DefaultEntityModal.html');

      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
  
})->conditions(array(
    'entityName' => '[a-zA-Z_]{3,}',
    ))->name('entityModal');

?>