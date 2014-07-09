<?php
///usr/bin/php /var/lib/openshift/52d70be5e0b8cdd7ca000208/app-root/repo/php/app/cron/dump.php
// MODALITA DI ESECUZIONE
$app_mode = 'production';
if(getenv ( 'OPENSHIFT_MYSQL_DB_HOST' ) === false){
    $app_mode = 'development';
}

// AUTOLOAD DI COMPOSER
if(file_exists(__DIR__ . '/../../vendor/autoload.php')){
    try {
        require __DIR__ . '/../../vendor/autoload.php';
    } catch (Exception $e ) {
        echo $e->getMessage();
    }
}

use RedBean_Facade as R;


// DB CONNECTION
try {
    if($app_mode == 'development'){
        //R::setup("mysql:host=172.17.0.51;dbname=php;port=3306",'langeli','');
        $command="/sbin/ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{ print $1}'";
        $localIP = exec ($command);
        R::setup("mysql:host=$localIP;dbname=php;port=3306",'langeli','');
        R::freeze(true);
    }else{
        R::setup("mysql:host=" . getenv ( 'OPENSHIFT_MYSQL_DB_HOST' ). ";dbname=php;port=" . getenv ( 'OPENSHIFT_MYSQL_DB_PORT'),        getenv ( 'OPENSHIFT_MYSQL_DB_USERNAME'),        getenv ( 'OPENSHIFT_MYSQL_DB_PASSWORD')); 
        R::freeze(true);
    }
    
    
} catch (Exception $e ) {
    echo $e->getMessage();
}

        //echo 'dump<pre>';
        
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
        
        $write = true;
        
        if($write) $fp = fopen('/tmp/dump.csv', 'w');
        $performanceRB = R::find('performance');
        
        $a =  array(
            'Trattamento ID',
            'Data',
            'Durata',
            'Soggetto',
            'Bilancio PRE',
            'Trattamenti',
            'Bilancio POST',
            'Eseguita',
            'Tariffa',
            'Pagato',
            'Data pagamento',
            'Id pagamento',
            'Info pacchetto'
            );
            
        if($write) fputcsv($fp, $a);
        foreach($performanceRB as $id => $p){
            $pa = $p->export();
            
            $testo = '';
        
            $t = $p->ownPerformancePerformancetype;
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
                if(is_array($pt) && !empty($pt)){ 
                    foreach($t as $pt){
                        $ptname = '';
                        if(isset($performancetypes[$pt['performancetype_id']]) && $performancetypes[$pt['performancetype_id']] != '') $ptname = $performancetypes[$pt['performancetype_id']] . chr (10);
                        $testo .= $ptname . $pt['note'] . ($pt['note'] != ''?chr (10):'') . chr (10);
                    }
                }
            }
            
                
            /****************************************************************** 
            Payment
            ******************************************************************/
    
            $tariffa = '';
            $pagato = 'No';
            $info_pacchetto = '';
            $data_pagamento = '';
            $id_pagamento = '';
            if($p->payment_id != ''){
                $id_pagamento = $p->payment_id;
                $RBPaymentEntity = R::find('payment',  'id =?', array($p->payment_id)); 
                
                if(is_array($RBPaymentEntity) && !empty($RBPaymentEntity)){
                    
                    foreach ($RBPaymentEntity as $epay) {
                        $pay = $epay->export();
                        
                        $tariffa = $epay->amount;
                        if($epay->paymentgroup_nominal_number_of_performance > 1){
                            $info_pacchetto = 'Pacchetto da ' . $epay->paymentgroup_nominal_number_of_performance;
                        }
                        if($epay->paymentstate_id == 1){
                            $pagato = 'Si';
                        }
                        if(!is_null($epay->collection_date)){
                            $data_pagamento = $epay->collection_date;
                        }
                    }         
                }
                
            }
               
            
            $a = array(
                $p->id,
                $p->datetime,
                $p->duration,
                $p->client->name . ' ' . $p->client->surname,
                $p->pre_note,
                $testo . '',
                $p->post_note,
                ($p->executed?'Si':'No'),
                $tariffa,
                $pagato,
                $data_pagamento,
                $id_pagamento,
                $info_pacchetto
            );
        
            if($write) fputcsv($fp, $a);
        }
        
        if($write) fclose($fp);
?>