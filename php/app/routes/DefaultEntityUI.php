<?php

use RedBean_Facade as R;

$app->get('/entity/:entityName/:pk', $authAdmin('admin'), function ($entityName,$pk) use ($app) {  
    
    //$app->etag("/entity/{$entityName}/{$pk}");
    //$app->expires('+1 hour');
    
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

            $entity = $RBEntity->export();
     
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
            if(isset($entityConfiguration['relations'])  && is_array($entityConfiguration['relations'])){
                 foreach($entityConfiguration['relations'] as $rEntityName => $rData){
                   // if(strpos($fieldName,"_id") !== false) $preloadEntities[] = str_replace("_id","",$fieldName);
                   
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
                   switch($rData['type']){
                       case 'many-to-one':
                           
                                $RBRE = R::find($rEntityName,  $entityName . '_id =?', array($RBEntity->id)); 
                                $REA = R::beansToArray($RBRE);
                                $entity['own' . ucfirst($rEntityName)] = $REA;
                                /*
                                echo "<pre>";
                                print_r($REA);
                                */
                           break;
                       case 'many-to-many':
                                $RBRE = R::related($RBEntity,$rEntityName);
                                if(is_array($RBRE)){
                                    $t = reset($RBRE);
                                    if(is_object($t)){
                                        $linkTable = $t->ownGroupRate;
                                        /*
                                        echo "<pre>";
                                        print_r(R::beansToArray($linkTable));
                                        */
                                        
                                        $REA = R::beansToArray($RBRE);
                                        
                                        $entity['own' . ucfirst($rEntityName)] = $REA;
                                        /*
                                        echo "<pre>";
                                        print_r($REA);
                                        */
                                        /*foreach ($RBRatesEntity as $e) {
                                        
                                            $p = $e->export();
                                            $rates[$p['id']] = $p;
                                        }*/
                                    }
                                }
                           break;
                   
                   }
                      
                }
            }
            
            
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
            // FARE FOREACH che se un campo ha ..._id tiro fuori la sua rappresentazione con url
            foreach ($entity as $fieldName => $fieldData) {
                
                if(strpos($fieldName,'_id') !== false){
                    $counter = 0;  
                    //echo $fieldName . '<br>';
                    
                    if(is_array($fieldData) &&  !empty($fieldData)){
                        foreach ($fieldData as $id1 => $fieldData1) {
                            /*
                            echo "<pre>";
                            print_r($entity);
                            */
                            $counter++;     
                            if(is_array($fieldData1) &&  !empty($fieldData1)){
                                foreach ($fieldData1 as $fieldName2 => $fieldData2) {
                                    if(strpos($fieldName2,'_id') !== false){
                                        
                                        $relatedEntityName = strtolower(str_replace('_id','',$fieldName2));
                                        
                                        if(is_array($fieldData2) && !empty($fieldData2)){
                                            if($relatedEntityName != $entityName){
                                                //echo '<br>relatedEntityName : ' . $relatedEntityName;
                                                if($counter == 1){
                                                    $RBRelatedEntities = R::find($relatedEntityName);
                                                    
                                                    
                                                    $representationsOf[$relatedEntityName] = entityRepresentation($RBRelatedEntities,$entitiesConfiguration[$relatedEntityName]);
                                                }
                                                
                                                 $entity[$fieldName][$id1][$relatedEntityName] = '';
                                                 if(isset($fieldData2[$fieldName2]) && $fieldData2[$fieldName2] != '' && isset($representationsOf[$relatedEntityName][$fieldData2[$fieldName2]])){
                                                    $entity[$fieldName][$id1][$relatedEntityName] = $representationsOf[$relatedEntityName][$fieldData2[$fieldName2]]['representation'];
                                                 }
                                            }
                                        }
                                      }
                                }
                            }
                        }
                    }
                }
                
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
                if(strpos($fieldName,'own') === 0){
                    $counter = 0;  
                    //echo $fieldName . '<br>';
                    
                    if(is_array($fieldData) &&  !empty($fieldData)){
                        foreach ($fieldData as $id1 => $fieldData1) {
                        
                            if(is_array($fieldData1) &&  !empty($fieldData1)){
                                $counter++;    
                                foreach ($fieldData1 as $fieldName2 => $fieldData2) {
                                    if(strpos($fieldName2,'_id') !== false){
                                        
                                        $relatedEntityName = strtolower(str_replace('_id','',$fieldName2));
                                        if($relatedEntityName != $entityName){
                                            //echo '<br>fieldName2: ' . $fieldName2 . '<br>';
                                            if($counter == 1){ // CARICO LE RAPPRESENTAZIONI DELL ENTITA
                                                $RBRelatedEntities = R::find($relatedEntityName);
                                                if(is_array($RBRelatedEntities) &&  !empty($RBRelatedEntities)){
                                                    $representationsOf[$relatedEntityName] = entityRepresentation($RBRelatedEntities,$entitiesConfiguration[$relatedEntityName]);
                                                    //print_r(entityRepresentation($RBRelatedEntities,$entitiesConfiguration[$relatedEntityName]));
                                                    
                                                }
                                            }
                                            
                                             $entity[$fieldName][$id1][$relatedEntityName] = '';
                                             if(isset($fieldData2[$fieldName2]) && $fieldData2[$fieldName2] != '' && isset($representationsOf[$relatedEntityName][$fieldData2[$fieldName2]])){
                                                $entity[$fieldName][$id1][$relatedEntityName] = $representationsOf[$relatedEntityName][$fieldData2[$fieldName2]]['representation'];
                                             }
                                             
                                             
                                        }
                                    }
                                }
                            }
                          
                        }
                    }
                }
            }
            
            /*
            echo "<pre>";
            print_r($entity);
            die();
            
            */
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
            /*
            echo '<pre>';
            var_dump($representationsOf);
            echo '<pre>';
            //print_r($entity);
            die();
            */
            
            
            
            $app->view->appendData(array(
                'RBEntity' => $RBEntity,
                'entity' => $entity
            ));
            
        }else{
            $get = $app->request()->get();
            if(isset($get['ED'])){
                $app->view->appendData(array(
                    'ED' => $get['ED'],
                    'isANewEntity' => $isANewEntity
                ));
            }
            
        }
        $app->view()->setData(array(
            'entityConfiguration' => $entityConfiguration
        ));
        
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ . ' Begin render' => (microtime(TRUE)-START_TIME));
        $app->render('/entities/DefaultEntityUI.html');
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ . ' End render' => (microtime(TRUE)-START_TIME));

      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
        echo "<pre>";
        print_r($e->getMessage());
        print_r($e->getCode());
        print_r($e->getTrace());
      }
  
})->conditions(array(
    'entityName' => '[a-zA-Z]{3,}',
    'pk' => '(new|\d{1,4})',
    ))->name('entityUI');

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
        
        if(is_array($RBEntities)){
            //  PRELOAD
            $preloadEntities =  array();
            foreach($entityConfiguration['fields'] as $fieldName => $d){
                if(strpos($fieldName,"_id") !== false) $preloadEntities[] = str_replace("_id","",$fieldName);
            }
            
            
            $entities = array();
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
        }

      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
        echo "<pre>";
        print_r($e->getMessage());
        print_r($e->getCode());
        print_r($e->getTrace());
      }
  
})->conditions(array(
    'entityName' => '[a-zA-Z]{3,}',
    ))->name('entityListUI');;

?>