<?php

use RedBean_Facade as R;

$app->get('/entity/:entityName/:pk', $authAdmin('admin'), function ($entityName,$pk) use ($app) {  
    
    try{
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new EntitiesNotInConfigException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new EntityNotConfiguredException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        $isANewEntity = ($pk == 'new'?true:false);
        
        
        $entity = NULL;
        
        if(!$isANewEntity){
            
            $RBEntity = R::findOne($entityName, $entityConfiguration['primary_key'] . '=?', array($pk)); 
            
             /*if(isset($entityConfiguration['relations'])){
                foreach($entityConfiguration['relations'] as $relEntityName => $relationData){
                    $relations[$relEntityName]  = call_user_func(array($RBEntity, 'own' . ucfirst($relEntityName)));
                    $property = 'own' . ucfirst($relEntityName);
                    $relations[$relEntityName]  = $RBEntity->$property;
                }
            }*/
            /*
            $preloadEntities =  array();
            foreach($entityConfiguration['fields'] as $fieldName => $d){
                if(strpos($fieldName,"_id") !== false) $preloadEntities[] = str_replace("_id","",$fieldName);
            }
            R::preload($RBEntity,$preloadEntities);
            */
            
            $entity = R::exportAll( $RBEntity );
            $entity = reset($entity);
            
            
            // FARE FOREACH che se un campo ha sdfasdas_id tiro fuori la sua rappresentazione con url
            foreach ($entity as $fieldName => $fieldData) {
                if(strpos($fieldName,'own') !== false){
                    
                    $counter = 0;        
                    foreach ($fieldData as $id1 => $fieldData1) {
                        
                        $counter++;        
                        foreach ($fieldData1 as $fieldName2 => $fieldData2) {
                            if(strpos($fieldName2,'_id') !== false){
                                
                                $relatedEntityName = strtolower(str_replace('_id','',$fieldName2));
                                
                                if(is_array($fieldData2) && !empty($fieldData2)){
                                    if($relatedEntityName != $entityName){
                                        if($counter == 1){
                                            $RBRelatedEntities = R::find($relatedEntityName);
                                            
                                            
                                            $representationsOf[$relatedEntityName] = entityRepresentation($RBRelatedEntities,$entitiesConfiguration[$relatedEntityName]);
                                        }
                                        $entity[$fieldName][$id1][$relatedEntityName] = $representationsOf[$relatedEntityName][$fieldData2[$fieldName2]]['representation'];
                                    }
                                }
                              }
                        }
                    }
                }
            }
            
            $app->view->appendData(array(
                'RBEntity' => $RBEntity,
                'entity' => $entity
            ));
        }else{
            $get = $app->request()->get();
            if(isset($get['ED'])){
                $app->view->appendData(array(
                    'ED' => $get['ED']
                ));
            }
            
        }
        $app->view()->setData(array(
            'entityConfiguration' => $entityConfiguration
        ));
        
        $app->render('/entities/DefaultEntityUI.html');

      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
  
})->conditions(array(
    'entityName' => '[a-zA-Z]{3,}',
    'pk' => '(new|\d{1,4})',
    ))->name('entityUI');;

$app->get('/entity/:entityName', $authAdmin('admin'), function ($entityName) use ($app) {  
    
    try{
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new EntitiesNotInConfigException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new EntityNotConfiguredException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        $RBEntities = R::find($entityName);
        
        //  PRELOAD
        $preloadEntities =  array();
        foreach($entityConfiguration['fields'] as $fieldName => $d){
            if(strpos($fieldName,"_id") !== false) $preloadEntities[] = str_replace("_id","",$fieldName);
        }
        
        
        
        R::preload($RBEntities,$preloadEntities);
               foreach($RBEntities as $RBEntity){
        $entities[] = $RBEntity->export();
                             
        }
        //$entities = R::exportAll($RBEntities);
        
        $app->view()->setData(array(
            'entityConfiguration' => $entityConfiguration,
            'entities' => $entities,
            'RBEntities' => $RBEntities
        ));
        $app->render('/entities/DefaultEntityUIList.html');

      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
  
})->conditions(array(
    'entityName' => '[a-zA-Z]{3,}',
    ))->name('entityListUI');;

?>