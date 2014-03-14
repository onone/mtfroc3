<?php

use RedBean_Facade as R;

$app->get('/resources/client(/:pk)', $authAdmin('admin'), function ($pk = NULL) use ($app) {  
    
    try {
       if(is_null($pk)){
           // query database for all entities
            $entity = R::find('client');  
       }else{
            $entity = R::findOne('client', 'id=?', array($pk)); 
       }
      
      
      // send response header for JSON content type
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($entity));
      
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
    echo "{status: 'error', msg: 'field cannot be empty!'}";
    
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
  
});

$app->post('/resources/client', $authAdmin('admin'), function () use ($app) {  
    
    try {
        
        $request = $app->request();
        $allPostVars = $request->post();
        
        if(isset($allPostVars['_METHOD'])){
            unset($allPostVars['_METHOD']);
        }
        if(isset($allPostVars['id'])){
            unset($allPostVars['id']);
        }
        
        $fields = R::getColumns('client');
        
        $entity = R::dispense('client');
  
  
        // Controllo che il campo che si vuole inserire esista
        foreach ($allPostVars as $field_name => $field_value) {
            if(array_key_exists($field_name,$fields)){
                $entity->setAttr($field_name,(string)$field_value);
            }
        }
           
        $id = R::store($entity);  
      
      // send response header for JSON content type
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(array('id' => $id));
      
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
    
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
  
});



/*

//Get root URI
$rootUri = $request->getRootUri();
 echo "<pre>";
 print_r($rootUri);
 echo "</pre>";

//Get resource URI
$resourceUri = $request->getResourceUri();
 echo "<pre>";
 print_r($resourceUri);
 echo "</pre>";
 
 */
 


$app->put('/resources/client(/:pk)', $authAdmin('admin'), function ($pk = NULL) use ($app) {  
    try {
        $request = $app->request();
        $allPostVars = $request->post();
        
        if(isset($allPostVars['_METHOD'])){
            unset($allPostVars['_METHOD']);
        }
        
        if(isset($allPostVars['pk'])){
            $pk = intval($allPostVars['pk']);
        }
        
        
            
        if(is_null($pk) || $pk == 0){
            throw new ResourceNotFoundException(); 
        }
        
        $field_name = $allPostVars['name'];
        $field_value = $allPostVars['value'];
  
  
            
        // Controllo che il campo che si vuole aggiornare esista
        $fields = R::getColumns('client');
        
        if(!array_key_exists($field_name,$fields)){
            throw new ResourceNotFoundException(); 
        }
  
        // query database for single entity
        $entity = R::findOne('client', 'id=?', array($pk));  
        
        
        // store modified entity
        // return JSON-encoded response body
         if ($entity) {
            $entity->setAttr($field_name,(string)$field_value);
            R::store($entity);    
            
            $app->response()->header('Content-Type', 'application/json');
            //echo json_encode(R::exportAll($entity));
            echo json_encode('OK');
         }else {
          throw new ResourceNotFoundException();    
        }

  
      } catch (ResourceNotFoundException $e) {
        $app->response()->status(404);
        echo "{status: 'error', msg: 'field cannot be empty!'}";
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
});

$app->delete('/resources/client/:pk', $authAdmin('admin'), function ($pk) use ($app) {  
    try {
        $request = $app->request();
        $allPostVars = $request->post();
        
        if(isset($allPostVars['_METHOD'])){
            unset($allPostVars['_METHOD']);
        }
        
        if(isset($allPostVars['pk'])){
            $pk = intval($allPostVars['pk']);
        }
        
        
            
        if(is_null($pk) || $pk == 0){
            throw new ResourceNotFoundException(); 
        }
        
  
        // query database for single entity
        $entity = R::findOne('client', 'id=?', array($pk));  
        
        
        // delete entity
        // return JSON-encoded response body
         if ($entity) {
             
            R::trash($entity);  
            
            $app->response()->header('Content-Type', 'application/json');
            //echo json_encode(R::exportAll($entity));
            echo json_encode('OK');
         }else {
          throw new ResourceNotFoundException();    
        }

  
      } catch (ResourceNotFoundException $e) {
        $app->response()->status(404);
        echo "{status: 'error', msg: 'field cannot be empty!'}";
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
});

 

?>