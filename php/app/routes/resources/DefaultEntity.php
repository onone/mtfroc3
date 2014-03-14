<?php

use RedBean_Facade as R;

$app->get('/resources/:entityName(/:pk)', $authAdmin('admin'), function ($entityName,$pk = NULL) use ($app) {  
    
    try {
       if(is_null($pk)){
           // query database for all entities
            $entity = R::find($entityName);  
       }else{
            $entity = R::findOne($entityName, 'id=?', array($pk)); 
       }
      
      // send response header for JSON content type
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode(R::exportAll($entity));
     
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
  
});

$app->post('/resources/:entityName', $authAdmin('admin'), function ($entityName) use ($app) {  
    
      // send response header for JSON content type
     $app->response()->header('Content-Type', 'application/json');
      
    try {
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        
        
        $request = $app->request();
        $allPostVars = $request->post();
        
        if(isset($allPostVars['_METHOD'])){
            unset($allPostVars['_METHOD']);
        }
        if(isset($allPostVars[$entityConfiguration['primary_key']])){
            unset($allPostVars[$entityConfiguration['primary_key']]);
        }
        
        $tableFields = R::getColumns($entityName);
        
        if(strpos($entityName,'_') !== FALSE){ // RELATION TABLE
            
            $relatedEntitiesArray = array();
            $relatedEntitiesNames = explode('_',$entityName);
            
            foreach ($relatedEntitiesNames as $relatedEntityName) {
                $fieldName = $relatedEntityName . '_id';
                if(!isset($allPostVars[$fieldName])) throw new DBFieldNotExistException(); 
                $relatedEntities[$relatedEntityName] = R::load($relatedEntityName,$allPostVars[$fieldName]);
                $relatedEntitiesArray[$fieldName] = $allPostVars[$fieldName];
                unset($allPostVars[$fieldName]);
            }
            
            // Dati addizionali
            $dataArray =  array();
            foreach ($entityConfiguration['fields'] as $fieldName => $fieldData) {
                
                if(!array_key_exists($fieldName,$tableFields)){
                    throw new DBFieldNotExistException(); 
                }
                
                if(isset($fieldData['onInsertValue']) && $fieldData['onInsertValue'] != ''){
                    $value = NULL;
                    switch($fieldData['onInsertValue']){
                        case 'DATETIME_NOW':
                            $value = date( 'Y-m-d H:i:s');
                        break;
                    }
                    if(!is_null($value)) $dataArray[$fieldName] = $value;
                }
                
                if(isset($fieldData['editable']) && $fieldData['editable'] === false) continue;
                
                if(array_key_exists($fieldName,$allPostVars)){
                    $dataArray[$fieldName] = $allPostVars[$fieldName];
                }
                
            }
            
            $res = $relatedEntities[$relatedEntitiesNames[0]]->link($entityName, $dataArray)->$relatedEntitiesNames[1] = $relatedEntities[$relatedEntitiesNames[1]];
            R::store($relatedEntities[$relatedEntitiesNames[0]]);
            
            $relatedEntity = R::findLast($entityName,
             $relatedEntitiesNames[0] . '_id  = ? AND ' . $relatedEntitiesNames[1] . '_id = ? ',
             array($relatedEntitiesArray[$relatedEntitiesNames[0] . '_id'],$relatedEntitiesArray[$relatedEntitiesNames[1] . '_id']));
            
            
            $export = R::exportAll($relatedEntity);
            $export = reset($export);
            
            
            foreach ($relatedEntitiesNames as $relatedEntityName) {
                $t = entityRepresentation($relatedEntities[$relatedEntityName],$entitiesConfiguration[$relatedEntityName]);
                $t = reset($t);
                $export[$relatedEntityName] = $t['representation'];
            }
            
            echo json_encode($export);
            
            
        }else{
            $entity = R::dispense($entityName);
      
            // Controllo che il campo che si vuole inserire esista
    
            $tableFields = R::getColumns($entityName);
            
            foreach ($entityConfiguration['fields'] as $fieldName => $fieldData) {
                
                if(!array_key_exists($fieldName,$tableFields)){
                    throw new DBFieldNotExistException(); 
                }
                
                if(isset($fieldData['onInsertValue']) && $fieldData['onInsertValue'] != ''){
                    $value = NULL;
                    switch($fieldData['onInsertValue']){
                        case 'DATETIME_NOW':
                            $value = date( 'Y-m-d H:i:s');
                        break;
                    }
                    if(!is_null($value)) $entity->setAttr($fieldName,$value);
                }
                
                if(isset($fieldData['editable']) && $fieldData['editable'] === false) continue;
                
                if(array_key_exists($fieldName,$allPostVars)){
                    $entity->setAttr($fieldName,$allPostVars[$fieldName]);
                }
            }
               
            $pk = R::store($entity); 
            
            //$export = R::exportAll($entity);
            $export = R::exportAll(R::find($entityName,' id = ? ', 
                array( $pk )));
            $export = reset($export);
            echo json_encode($export);
        }
      
      

      
  } catch (DBFieldNotExistException $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', 'Field not exists in table');
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
 


$app->put('/resources/:entityName(/:pk)', $authAdmin('admin'), function ($entityName, $pk = NULL) use ($app) {  
    try {
        $entitiesConfiguration = $app->config('entities');
        
        
        
        
        if(is_null($entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        
        $request = $app->request();
        $allPostVars = $request->post();
        
        // SLIMFRAMEWORK WORKAROUND
        if(isset($allPostVars['_METHOD'])){ 
            unset($allPostVars['_METHOD']);
        }
        
        // X-EDITABLE Primary Key
        if(isset($allPostVars['pk'])){ 
            $pk = intval($allPostVars['pk']);
            unset($allPostVars['pk']);
        }
        
        if(is_null($pk) || $pk == 0){
            throw new ResourceNotFoundException(); 
        }
        
            
        // Controllo che il campo che si vuole aggiornare esista
       
  
        // query database for single entity
        $entity = R::findOne($entityName, $entityConfiguration['primary_key'] . '=?', array($pk));  
        
        
        // store modified entity
        // return JSON-encoded response body
        
         if ($entity) {
            
            $tableFields = R::getColumns($entityName);
            
            foreach ($entityConfiguration['fields'] as $fieldName => $fieldData) {
        
                if(!array_key_exists($fieldName,$tableFields)){
                    throw new DBFieldNotExistException(); 
                }
                
                if(isset($fieldData['onUpdateValue']) && $fieldData['onUpdateValue'] != ''){
                    $value = NULL;
                    switch($fieldData['onUpdateValue']){
                        case 'DATETIME_NOW':
                            $value = date( 'Y-m-d H:i:s');
                        break;
                    }
                    if(!is_null($value)) $entity->setAttr($fieldName,$value);
                }
                
                if(isset($fieldData['editable']) && $fieldData['editable'] === false) continue;
                
                if(array_key_exists($fieldName,$allPostVars)){
                    $entity->setAttr($fieldName,$allPostVars[$fieldName]);
                }
            }
            
            R::store($entity);    
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode(R::exportAll($entity));
         }else {
          throw new ResourceNotFoundException();    
        }
        

  
      } catch (DBFieldNotExistException $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', 'Field not exists in table, check entities configuration');
      } catch (ResourceNotFoundException $e) {
        $app->response()->status(404);
        echo "{status: 'error', msg: 'field cannot be empty!'}";
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
});

$app->delete('/resources/:entityName(/:pk)', $authAdmin('admin'), function ($entityName, $pk = NULL) use ($app) {  
    try {
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
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
        $entity = R::findOne($entityName, $entityConfiguration['primary_key'] . '=?', array($pk));  
        
        
        // delete entity
        // return JSON-encoded response body
         if ($entity) {
             
            R::trash($entity);  
            
            $app->response()->header('Content-Type', 'application/json');
            echo json_encode('OK');
         }else {
          throw new ResourceNotFoundException();    
        }

      } catch (RedBean_Exception_SQL $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      } catch (ResourceNotFoundException $e) {
        $app->response()->status(404);
        echo "{status: 'error', msg: 'field cannot be empty!'}";
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
})->name('entityResource');

 

?>