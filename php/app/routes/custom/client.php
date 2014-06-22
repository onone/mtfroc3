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
        
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
        
        $entity = NULL;
        $RBEntity = R::findOne($entityName, $entityConfiguration['primary_key'] . '=?', array($pk)); 

        $entity = $RBEntity->export(); 
        
        
        
        /****************************************************************** 
        PType
        ******************************************************************/
        $RBPTypeEntity = R::find('performancetype');
        if(is_array($RBPTypeEntity) && !empty($RBPTypeEntity)){
            foreach ($RBPTypeEntity as $e) {
                $pt = $e->export();
                $performancetypes[$pt['id']] = $pt['name'];
            }
        }
        
    
        /*
        $jsXEditableEntityConfiguration = array(
            'entityName' => $entityName,
            'editableCSSClass' => $entityName . 'Editable',
            'fields' => $entityConfiguration['fields']
        );
        */
            
        
        /****************************************************************** 
        Group
        ******************************************************************/
        $RBGroupEntity = R::findOne('group',  'id =?', array($entity['group_id'])); 
        $group = $RBGroupEntity->export();
            
        $RBRatesEntity = R::related($RBGroupEntity,'rate');
        if(is_array($RBRatesEntity) && !empty($RBRatesEntity)){
        foreach ($RBRatesEntity as $e) {
            
                $p = $e->export();
                $rates[$p['id']] = $p;
            }
            $RBGroupRateEntity = R::find('group_rate',  'group_id =?', array($entity['group_id'])); 
            $groupRate = R::beansToArray($RBGroupRateEntity);
            if(is_array($groupRate) && !empty($RBRatesEntity)){
                foreach ($groupRate as $r) {
                    if(array_key_exists($r['rate_id'],$rates)){
                        $rates[$r['rate_id']]['amount'] = $r['amount'];
                    }
                }
            }
        }
        
        if(isset($rates)){
            $app->view->appendData(array('rates' => $rates));
        }
        

        
        /****************************************************************** 
        STATS
        ******************************************************************/
        $year = date('Y');
        $timestampNow = time();
        $year_in_seconds = 365 * 24 * 3600;
        
        $statData['all']['payed_number'] = 0;
        $statData['all']['payed'] = 0;
        $statData['all']['performance_number'] = 0;
        $statData['this_year']['payed'] = 0;
        $statData['this_year']['payed_number'] = 0;
        $statData['this_year']['performance_number'] = 0;
        $statData['last_365']['payed'] = 0;
        $statData['last_365']['payed_number'] = 0;
        $statData['last_365']['performance_number'] = 0;
        $statData['all']['not_payed_number'] = 0;
        $statData['all']['not_payed'] = 0;
        $statData['this_year']['not_payed'] = 0;
        $statData['this_year']['not_payed_number'] = 0;
        $statData['last_365']['not_payed'] = 0;
        $statData['last_365']['not_payed_number'] = 0;
        
        
        /****************************************************************** 
        Payment
        ******************************************************************/

        
        
        $RBPaymentEntity = R::find('payment',  'client_id =?', array($pk)); 
        
        if(is_array($RBPaymentEntity) && !empty($RBPaymentEntity)){
            foreach ($RBPaymentEntity as $e) {
                $p = $e->export();
                $payments[$p['id']] = $p;
                
                if(!is_null($p['collection_date'])){
                    
                    $timestampPay = strtotime($p['collection_date']);
                    $phpDatetimePay = new DateTime($p['collection_date']);
                }
                    
                    // DATI STATISTICI
                    if($p['paymentstate_id'] == 1){
                        
                        if(!is_null($p['collection_date'])){
                            if($year == $phpDatetimePay->format('Y')){
                                $statData['this_year']['payed'] += $p['amount'];
                                $statData['this_year']['payed_number']++;
                            }
                            
                            if(($timestampNow - $timestampPay) <= $year_in_seconds){
                                $statData['last_365']['payed'] += $p['amount'];
                                $statData['last_365']['payed_number']++;
                            }
                        }
                        
                        $statData['all']['payed'] += $p['amount'];
                        $statData['all']['payed_number']++;
                        
                    }else{
                       if(!is_null($p['collection_date'])){
                            if($year == $phpDatetimePay->format('Y')){
                                $statData['this_year']['not_payed'] += $p['amount'];
                                $statData['this_year']['not_payed_number']++;
                            }
                            
                            if(($timestampNow - $timestampPay) <= $year_in_seconds){
                                $statData['last_365']['not_payed'] += $p['amount'];
                                $statData['last_365']['not_payed_number']++;
                            }
                       }
                        
                        $statData['all']['not_payed'] += $p['amount'];
                        $statData['all']['not_payed_number']++;
                        
                    }
                
            }
            $app->view->appendData(array('payments' => $payments));
        }
            
        /****************************************************************** 
        PaymentFormula
        ******************************************************************/
        $RBPaymentFormulaEntity = R::find('paymentformula');
        if(is_array($RBPaymentFormulaEntity) && !empty($RBPaymentFormulaEntity)){
             foreach ($RBPaymentFormulaEntity as $e) {
                $pf = $e->export();
                $paymentformulas[$pf['id']] = $pf;
            }
        }
            
        /****************************************************************** 
        PaymentForm
        ******************************************************************/
        $RBPaymentFormEntity = R::find('paymentform');
        if(is_array($RBPaymentFormEntity) && !empty($RBPaymentFormEntity)){
             foreach ($RBPaymentFormEntity as $e) {
                $pf = $e->export();
                $paymentforms[$pf['id']] = $pf;
            }
        }
            
        /****************************************************************** 
        PaymentState
        ******************************************************************/
        $RBPaymentStateEntity = R::find('paymentstate');
        if(is_array($RBPaymentStateEntity) && !empty($RBPaymentStateEntity)){
             foreach ($RBPaymentStateEntity as $e) {
                $ps = $e->export();
                $paymentstates[$ps['id']] = $ps;
            }
        }
        
        /****************************************************************** 
        Performance
        ******************************************************************/
        $performanceRB = R::find('performance',
        ' client_id = ? ORDER BY payment_id,id', 
            array(
                $pk 
            )
        );
        
        if(is_array($performanceRB) && !empty($performanceRB)){
            
            $paymentId = NULL;
            $counter = 1;
            foreach ($performanceRB as $e) {
                
                
                
                $t = $e->ownPerformancePerformancetype;
                if(is_array($t) && !empty($t)){ 
                    $t  = R::beansToArray($t);
                    uasort($t, function($a,$b){
                        if($a['position'] == ""){
                            $a['position'] = 0;
                        }
                        if($b['position'] == ""){
                            $b['position'] = 0;
                        }
                        if($a['position'] != $b['position']) {
                            return ($a['position'] >= $b['position']?1:-1);
                        }
                        return 0;
                    });
                    
                    $performancePT[$e->id] = $t;
                }
                
                
                $f = $e->export();
                
                
                
                $performance[$f['id']] = $f;
                if($paymentId != $f['payment_id']){
                    $paymentId = $f['payment_id'];
                    $counter = 1;
                }else{
                    $counter++;
                }
                $performanceNUmber[$f['id']] = $counter;
                $totalNumOfPerformance[$paymentId] = $counter;
                
                
                if(!is_null($f['datetime'])){
                    
                    $timestampPay = strtotime($f['datetime']);
                    $phpDatetimePay = new DateTime($f['datetime']);
                }
                    
                    // DATI STATISTICI
                    if($f['executed'] == 1){
                        
                        if(!is_null($f['datetime'])){
                            if($year == $phpDatetimePay->format('Y')){
                                $statData['this_year']['performance_number']++;
                            }
                            
                            if(($timestampNow - $timestampPay) <= $year_in_seconds){
                                $statData['last_365']['performance_number']++;
                            }
                        }
                        $statData['all']['performance_number']++;
                        
                    }
                
                
            }
            
            
            if(isset($performancePT) && is_array($performancePT) && !empty($performancePT)){ 
                $app->view->appendData(array(
                    'performancePT'       => $performancePT
                ));
            }
            /*echo "<pre>";
                print_r($performancePT);
            die('ciao');*/
            
            $app->view->appendData(array(
                'performance'       => $performance,
                'performanceNUmber' => $performanceNUmber,
                'totalNumOfPerformance' => $totalNumOfPerformance,
            ));
        }
        
        
        
        $app->view->appendData(array('statData' => $statData));
            
        
        
        /****************************************************************** 
        Anamnesis
        ******************************************************************/
        $anamnesisRB = R::find('anamnesis',
        ' client_id = ? ', 
            array(
                $pk 
            )
        );
        
        if(is_array($anamnesisRB) && !empty($anamnesisRB)){
            foreach ($anamnesisRB as $e) {
                $ps = $e->export();
                $anamnesis[$ps['id']] = $ps;
            }
            $app->view->appendData(array(
                'anamnesis' => $anamnesis,
            ));
        }
        
        /****************************************************************** 
        Memo
        ******************************************************************/
        $memoRB = R::find('memo',
        ' client_id = ? ', 
            array(
                $pk 
            )
        );
        
        if(is_array($memoRB) && !empty($memoRB)){
            
            foreach ($memoRB as $e) {
                $ps = $e->export();
                $memo[$ps['id']] = $ps;
            }
            $app->view->appendData(array(
                'memo' => $memo,
            ));
        }
        
        
        /*
        echo "<pre>";
        print_r('<br>isset($entityConfiguration) ' . (isset($entityConfiguration)?'si':'no'));
        print_r('<br>isset($group) ' . (isset($group)?'si':'no'));
        print_r('<br>isset($entity) ' . (isset($entity)?'si':'no'));
        print_r('<br>isset($rates) ' . (isset($rates)?'si':'no'));
        print_r('<br>isset($performancetypes) ' . (isset($performancetypes)?'si':'no'));
        print_r('<br>isset($paymentformulas) ' . (isset($paymentformulas)?'si':'no'));
        print_r('<br>isset($paymentforms) ' . (isset($paymentforms)?'si':'no'));
        print_r('<br>isset($paymentstates) ' . (isset($paymentstates)?'si':'no'));
        
        die();
        */
        
        
        $app->view->appendData(array(
            'entityConfiguration' => $entityConfiguration,
            'group' => $group,
            'entity' => $entity,
            //'rates' => $rates,
            'performancetypes' => $performancetypes,
            'paymentformulas' => $paymentformulas,
            'paymentforms' => $paymentforms,
            'paymentstates' => $paymentstates
            
        ));
        
        
        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
        
        $app->render('custom/client.html');

        $GLOBALS['timings'][] = array('Line: ' . __LINE__ => (microtime(TRUE)-START_TIME));
      } catch (EntitiesNotInConfigException $e) {
        $app->response()->status(404);
        echo 'EntitiesNotInConfigException';
        
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      } catch (Exception $e) {
        $app->response()->status(400);
        echo '<pre>';
        print_r($e);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }

  
})->name('clientCustom');


?>