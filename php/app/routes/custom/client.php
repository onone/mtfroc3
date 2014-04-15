<?php

use RedBean_Facade as R;

$app->get('/client/:pk', $authAdmin('admin'), function ($pk) use ($app) {  
    $entityName = 'client';
    try{
        $entitiesConfiguration = $app->config('entities');
        if(is_null($entitiesConfiguration)){
            throw new EntitiesNotInConfigException(); 
        }
        
        if(!array_key_exists($entityName,$entitiesConfiguration)){
            throw new EntityNotConfiguredException(); 
        }
        
        $entityConfiguration = $entitiesConfiguration[$entityName];
        
        
        $entity = NULL;
        $RBEntity = R::findOne($entityName, $entityConfiguration['primary_key'] . '=?', array($pk)); 
        /*
        $entity = R::exportAll( $RBEntity );
        $entity = reset($entity);
        */
        $entity = $RBEntity->export(  ); 
        
        $RBPTypeEntity = R::find('performancetype');
        /*$performancetype = R::exportAll( $RBPTypeEntity );         
        foreach ($performancetype as $pt) {
            $performancetypes[$pt['id']] = $pt['name'];
        } */
        
        foreach ($RBPTypeEntity as $e) {
            $pt = $e->export();
            $performancetypes[$pt['id']] = $pt['name'];
        }
        

        /*
        $jsXEditableEntityConfiguration = array(
            'entityName' => $entityName,
            'editableCSSClass' => $entityName . 'Editable',
            'fields' => $entityConfiguration['fields']
        );
        */
        
        $RBGroupEntity = R::findOne('group',  'id =?', array($entity['group_id'])); 
        $group = R::exportAll( $RBGroupEntity );
        $group = reset($group);
        foreach ($group['sharedRate'] as $rate) {
            $rates[$rate['id']] = $rate;
        }
        
        $RBPaymentEntity = R::find('payment',  'client_id =?', array($pk)); 
        if(!empty($RBPaymentEntity)){
            $pp = R::exportAll( $RBPaymentEntity );
            foreach ($pp as $payment) {
                $payments[$payment['id']] = $payment;
            }
            $app->view->appendData(array('payments' => $payments));
        }
        
        /*$pf = R::exportAll( R::find('paymentformula') ); 
    $GLOBALS['timings'][] = array('Post exportAll' . __LINE__ => (microtime(TRUE)-START_TIME));
        foreach ($pf as $f) {
            $paymentformulas[$f['id']] = $f;
        } */
        
        $RBPaymentFormulaEntity = R::find('paymentformula');
         foreach ($RBPaymentFormulaEntity as $e) {
            $pf = $e->export();
            $paymentformulas[$pf['id']] = $pf;
        }
        
        /*
        $pf = R::exportAll( R::find('paymentform') );   
    $GLOBALS['timings'][] = array('Post exportAll' . __LINE__ => (microtime(TRUE)-START_TIME));
        foreach ($pf as $f) {
            $paymentforms[$f['id']] = $f;
        }
        */
        $RBPaymentFormEntity = R::find('paymentform');
         foreach ($RBPaymentFormEntity as $e) {
            $pf = $e->export();
            $paymentforms[$pf['id']] = $pf;
        }
        
        /*
        $pf = R::exportAll( R::find('paymentstate') ); 
    $GLOBALS['timings'][] = array('Post exportAll' . __LINE__ => (microtime(TRUE)-START_TIME));
        foreach ($pf as $f) {
            $paymentstates[$f['id']] = $f;
        }
        */   
        $RBPaymentStateEntity = R::find('paymentstate');
         foreach ($RBPaymentStateEntity as $e) {
            $ps = $e->export();
            $paymentstates[$ps['id']] = $ps;
        }
        
        
        $performanceRB = R::find('performance',
        ' client_id = ? ORDER BY payment_id,id', 
            array(
                $pk 
            )
        );
        
        if(!empty($performanceRB)){
            $pf = R::exportAll( $performanceRB );
            $paymentId = NULL;
            $counter = 1;
            foreach ($pf as $f) {
                if($paymentId != $f['payment_id']){
                    $paymentId = $f['payment_id'];
                    $counter = 1;
                }else{
                    $counter++;
                }
                $performanceNUmber[$f['id']] = $counter;
                
                $totalNumOfPerformance[$paymentId] = $counter;
                
            }
            
            $app->view->appendData(array(
                'performanceNUmber' => $performanceNUmber,
                'totalNumOfPerformance' => $totalNumOfPerformance,
            ));
        }

        
        
       /* 
echo "<pre>";
print_r($group);
echo "</pre>";
die();
        */
        
        
            $app->view->appendData(array(
                'entityConfiguration' => $entityConfiguration,
                'group' => $group,
                'entity' => $entity,
                'rates' => $rates,
                'performancetypes' => $performancetypes,
                'paymentformulas' => $paymentformulas,
                'paymentforms' => $paymentforms,
                'paymentstates' => $paymentstates,
                
            ));
        
        
        $app->render('custom/client.html');

      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }

  
});


?>