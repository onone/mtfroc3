<?php

use RedBean_Facade as R;

$app->get('/resources/xeditable/select/:entityName', $authAdmin('admin'), function ($entityName) use ($app) {  
    
    try {
        
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        $REntity = R::find($entityName);  
        
        
        //$entities = R::exportAll($REntity);
            
        foreach ($REntity as $e) {
            $p = $e->export();
            $entities[$p['id']] = $p;
        }
            
        $outputArray = array();
        foreach ($entities as $entity) {
            
            $representationString = $entity['id'];
            if(isset($entityConfiguration['representation'])){
                $representationString = $entityConfiguration['representation'];
                foreach ($entityConfiguration['fields'] as $fieldName => $fieldData) {
                    //if(isset($entity[$fieldName])){
                        $representationString =  str_replace("<<$fieldName>>",$entity[$fieldName],$representationString);
                    //}
                }
            }
            
            $outputArray[] = array(
                'value' => $entity[$entityConfiguration['primary_key']],
                'text'  => $representationString
                );
        }
      
      // send response header for JSON content type
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode($outputArray);
     
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
    $app->response()->header('X-Status-Reason', $e->getMessage());
    
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
  
});

$app->get('/resources/xeditable/select2/:entityName', $authAdmin('admin'), function ($entityName) use ($app) {  
    
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
        $query = $request->get('query');
        
        if(!is_null($query)){
            $prepare = "";
            if(preg_match_all('/<<([a-zA-Z1-9]*)>>/',$entityConfiguration['representation'],$match)){
                foreach ($match[1] as $index => $field) {
                    if($index != 0){
                        $prepare .= " OR ";
                    }
                    $prepare .= "$field like ? ";
                    $prepareData[] = "%$query%";
                }
            }
        
            $REntity = R::find( $entityName,
                                $prepare,
                                $prepareData
            );
        }else{
            $REntity = R::find( $entityName);
        }
        
        
        //$entities = R::exportAll($REntity);
              
        foreach ($REntity as $e) {
            $p = $e->export();
            $entities[$p['id']] = $p;
        }
            
        $outputArray = array();
        foreach ($entities as $entity) {
            
            $representationString = $entity['id'];
            if(isset($entityConfiguration['representation'])){
                $representationString = $entityConfiguration['representation'];
                foreach ($entityConfiguration['fields'] as $fieldName => $fieldData) {
                    //if(isset($entity[$fieldName])){
                        $representationString =  str_replace("<<$fieldName>>",$entity[$fieldName],$representationString);
                    //}
                }
            }
            
            $outputArray[] = array(
                'id' => $entity[$entityConfiguration['primary_key']],
                'text'  => $representationString
                );
        }
      
      // send response header for JSON content type
      $app->response()->header('Content-Type', 'application/json');
      echo json_encode($outputArray);
     
  } catch (ResourceNotFoundException $e) {
    $app->response()->status(404);
    $app->response()->header('X-Status-Reason', $e->getMessage());
    
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
  
});

?>