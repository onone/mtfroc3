<?php

use RedBean_Facade as R;

$app->get('/stats/revenues', $authAdmin('admin'), function () use ($app) {  
    
    try{
      
      
        /****************************************************************** 
        Payment
        ******************************************************************/

        
        $dataWithoutDate['payed'] = 0;
        $dataWithoutDate['notPayed'] = 0;
        
        $RBPaymentEntity = R::find('payment','amount > 0'); 
        
        if(is_array($RBPaymentEntity) && !empty($RBPaymentEntity)){
            foreach ($RBPaymentEntity as $e) {
                $p = $e->export();
                $payments[$p['id']] = $p;
                
                $months = range(1,12);
    
                if(!is_null($p['collection_date'])){
                    $timestampPay = strtotime($p['collection_date']);
                    $phpDatetimePay = new DateTime($p['collection_date']);
                    $year = $phpDatetimePay->format('Y');
                    $month = $phpDatetimePay->format('n');
                    $monthIndex = (intval($month)-1);
                    
                    $val = intval($p['amount']);
                    
                    if($val > 0){
                        
                        $payed = FALSE;
                        $payedkey = 'notPayed';
                        $name = $year . ' non pagato';
                        if($p['paymentstate_id'] == 1){
                            $payed = TRUE;
                            $payedkey = 'payed';
                             $name = $year;
                        }
                        
                        if(!isset($data[$name])){
                            $data[$name]['name'] = $name;
                            $data[$name]['data'] = array_fill(0, 12, 0);
                        }
                        $data[$name]['data'][$monthIndex] += $val;
                    }
                }else{
                    $val = intval($p['amount']);
                    if($val > 0){
                         if($p['paymentstate_id'] == 1){
                            $dataWithoutDate['payed'] += $val;
                         }else{
                             
                            $dataWithoutDate['notPayed'] += $val;
                         }
                    }
                }
                
                
            }
            
            
            
            $app->view->appendData(array(
                'dataWithoutDate' => $dataWithoutDate,
                'data' => $data,
                'months' => $months
                ));
        }
        /*echo "<pre>ff";
        print(json_encode($data));
        print_r($data);
        print_r($dataWithoutDate);
        die();*/
      
        $app->view()->setData(array(
            //'entityConfiguration' => $entityConfiguration
        ));
        
        $app->render('/stats/revenuesStat.html');

      } catch (Exception $e) {
        $app->response()->status(400);
        $app->response()->header('X-Status-Reason', $e->getMessage());
        echo "<pre>";
        print_r($e->getMessage());
        print_r($e->getCode());
        print_r($e->getTrace());
      }
  
})->name('revenuesStat');

?>