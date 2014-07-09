<?php

use RedBean_Facade as R;

$app->get('/dump', $authAdmin('admin'), function () use ($app) {  
    
    try {
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
            'Info pacchetto'
            );
            
        print_r($a);
            
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
                        if(isset($performancetypes[$pt['performancetype_id']])) $ptname = '<strong>' .  $performancetypes[$pt['performancetype_id']] . '</strong><br>';
                        $testo .= $ptname . $pt['note'];
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
            if($p->payment_id != ''){
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
                $info_pacchetto,
                $data_pagamento
            );
        
        
            print_r($a);
        
            if($write) fputcsv($fp, $a);
        }
        
        if($write) fclose($fp);
        
        
        
        /*
        $r = exec('echo "cron  works sotto dir" | mail -s "a subject" fslepko@gmail.com');
        echo "<pre>";
        var_dump($r);
        */
        
        //exec('mysqldump -e --user=langeli php',$o);
        //https://api.sendgrid.com/api/mail.send.json?api_user=l904&api_key=sgp904&to=fslepko@gmail.com&toname=Destination&subject=Example_Subject&text=testingtextbody&from=l122124x@gmail.com
        //print_r($o);
        //passthru
        //mysqldump -e --user=$OPENSHIFT_MYSQL_DB_USERNAME --password=$OPENSHIFT_MYSQL_DB_PASSWORD --protocol=socket -S $OPENSHIFT_MYSQL_DB_SOCKET php


        //mysqldump -e --user=$OPENSHIFT_MYSQL_DB_USERNAME --password=$OPENSHIFT_MYSQL_DB_PASSWORD --protocol=socket -S $OPENSHIFT_MYSQL_DB_SOCKET php > /tmp/db_dump.sql
        //curl https://api.sendgrid.com/api/mail.send.json -F to=fslepko@gmail.com -F toname=Federico -F subject="DB Dump" -F text="DB DUMP" --form-string html="<strong>testing html body</strong>" -F from=l122124x@gmail.com -F api_user=l904 -F api_key=sgp904 -F files[db_dump.sql]=\/tmp/db_dump.sql https://api.sendgrid.com/api/mail.send.json

        //print_r(mail('federico.slepko@titanka.com', 'My Subject', 'uguuhuh'));
  } catch (Exception $e) {
    $app->response()->status(400);
    $app->response()->header('X-Status-Reason', $e->getMessage());
  }
  
});


?>