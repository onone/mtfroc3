<?php
use RedBean_Facade as R;

$app->get('/resources/performancepaymentlist/:clientId(/:paymentId)', $authAdmin('admin'), function ($clientId,$paymentId = NULL) use ($app) {  

    $entityName = 'payment';
    try {
        
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new ResourceNotFoundException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        //$REntity = R::find($entityName);  
        
        
        //$entities = R::exportAll($REntity);
            
        /*foreach ($REntity as $e) {
            $p = $e->export();
            $entities[$p['id']] = $p;
        }*/
        
        
        $q = "select * from payment where client_id = " . $clientId . " and (
            (paymentgroup_nominal_number_of_performance > 1  OR id not in (select distinct(payment_id) from performance where client_id = " . $clientId . "))
            ";
            
        if(!is_null($paymentId)){
        $q .= " OR id = " . $paymentId;
        }
        $q .= "  )";
        
        
        $res = R::getAll( $q );
            
            
            
        if(is_array($res) && !empty($res)){
            foreach ($res as $e) {
            $entities[$e['id']] = $e;
            }
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

?>