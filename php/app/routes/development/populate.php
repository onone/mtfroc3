<?php

use RedBean_Facade as R;


$app->get('/populate/all', function () use ($app) {
    /*
    clients groups rates grates ptype locations
    
    */
    
});

$app->get('/populate/grates', function () use ($app) {

/*Atletica     |
| Pesisti      |
| Standard     |
| Ghetto       |
| Non Standard*/



    $rows = array(    
        array(  
            'group_name' => 'Atletica',
            'rate_name' => 'Massoterapia',
            'rate_number' => '1',
            'amount' => '25'
        ),
        array(  
            'group_name' => 'Atletica',
            'rate_name' => 'Massoterapia',
            'rate_number' => '5',
            'amount' => '100'
        ),
        array(  
            'group_name' => 'Atletica',
            'rate_name' => 'Kinesio Tape',
            'rate_number' => '1',
            'amount' => '5'
        ),
        array(  
            'group_name' => 'Pesisti',
            'rate_name' => 'Massoterapia',
            'rate_number' => '1',
            'amount' => '25'
        ),
        array(  
            'group_name' => 'Pesisti',
            'rate_name' => 'Massoterapia',
            'rate_number' => '5',
            'amount' => '100'
        ),
        array(  
            'group_name' => 'Pesisti',
            'rate_name' => 'Kinesio Tape',
            'rate_number' => '1',
            'amount' => '5'
        ),
        array(  
            'group_name' => 'Standard',
            'rate_name' => 'Massoterapia',
            'rate_number' => '1',
            'amount' => '30'
        ),
        array(  
            'group_name' => 'Standard',
            'rate_name' => 'Massoterapia',
            'rate_number' => '5',
            'amount' => '120'
        ),
        array(  
            'group_name' => 'Standard',
            'rate_name' => 'Kinesio Tape',
            'rate_number' => '1',
            'amount' => '10'
        ),
        array(  
            'group_name' => 'Ghetto',
            'rate_name' => 'Massoterapia',
            'rate_number' => '1',
            'amount' => '20'
        ),
        array(  
            'group_name' => 'Ghetto',
            'rate_name' => 'Massoterapia',
            'rate_number' => '5',
            'amount' => '80'
        ),
        array(  
            'group_name' => 'Ghetto',
            'rate_name' => 'Kinesio Tape',
            'rate_number' => '1',
            'amount' => '5'
        )
    );
    
    foreach($rows as $value){
       $group = R::findOne('group',
                    ' name = ? ', 
                    array( $value['group_name'] )
                );
                
                
       $rate = R::findOne('rate',
                    ' name = ? AND performance_number = ?', 
                    array( $value['rate_name'], $value['rate_number'])
                );
                
        
        $res = $group->link('group_rate', array('amount' => $value['amount']))->rate = $rate;
        R::store($group); 
        
    }
    die('finito');
})->name('Pgrates');

$app->get('/populate/rates', function () use ($app) {

    $rows = array(    
        array(  
            'name' => 'Massoterapia',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Massoterapia',
            'performance_number' => '5',
        ),
        array(  
            'name' => 'Kinesio Tape',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Kinesio Tape',
            'performance_number' => '5',
        ),
        array(  
            'name' => 'Elettrostimolazione',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Elettrostimolazione',
            'performance_number' => '5',
        ),
        array(  
            'name' => 'Valutazione Posturale',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Personal Training',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Ginnastica Posturale',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Completo',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Spirotiger',
            'performance_number' => '1',
        ),
        array(  
            'name' => 'Spirotiger',
            'performance_number' => '5',
        ),
        array(  
            'name' => 'Spirotiger',
            'performance_number' => '10',
        ),
    );
    
    foreach($rows as $value){
        
        $pl = R::dispense('rate');
        
        $pl->setAttr('name',$value['name']);
        $pl->setAttr('performance_number',$value['performance_number']);
        
        R::store($pl); 
        
    }
    die('finito');
})->name('Prates');

$app->get('/populate/groups', function () use ($app) {
    
    $rows = array(    
        'Atletica',
        'Pesisti',
        'Standard',
        'Ghetto',
        'Thriathlon',
        'Cipriani'
    );
    
    foreach($rows as $value){
        
        $pl = R::dispense('group');
        
        $pl->setAttr('active',1);
        $pl->setAttr('name',$value);
        $pl->setAttr('creation_datetime',date( 'Y-m-d H:i:s'));
        
        if($value == 'Standard'){
            $clients = R::find('client');
            $pl->ownClient = $clients;
        }
        
        R::store($pl); 
        
    }
    die('finito');
})->name('Pgroups');

$app->get('/populate/ptype', function () use ($app) {
    
    $rows = array(    
        'Massoterapia',
        'Mobilizzazione',
        'Digitopressione',
        'Kinesio Tape',
        'Elettrostimolazione',
        'Spray&Stretch',
        'Esercizio',
        'Valutazione Posturale',
        'Personal Training',
        'Drenaggio Manuale',
        'Stretching Posturale',
        'Rieducazione Funzionale',
        'Spirotiger'
    );
    
    foreach($rows as $value){
        
        $pl = R::dispense('performancetype');
        
        $pl->setAttr('name',$value);
        $pl->setAttr('creation_datetime',date( 'Y-m-d H:i:s'));
        
        R::store($pl); 
        
    }
    die('finito');
})->name('Pptype');

$app->get('/populate/locations', function () use ($app) {
    
    $rows = array(
        array(  
            'name' => 'Tripoli',
            'city' => 'Rimini',
            'address' => 'via Tripoli 98'
        ),
        array(  
            'name' => 'Pesisti',
            'city' => 'Rimini',
            'address' => 'via A. da Brescia 8'
        ),
        array(  
            'name' => 'Domiciliare',
            'city' => 'citta del cliente',
            'address' => 'via del cliente'
        )
    );
    
    foreach($rows as $row){
        
        $pl = R::dispense('performancelocation');
        
        foreach($row as $fieldName => $value){
            $pl->setAttr($fieldName,$value);
        }
        
        R::store($pl); 
        
    }
    die('finito');
})->name('Plocations');

$app->get('/populate/client/:id', function ($id) use ($app) {
    R::exec( 'delete from performance_performancetype' );
    R::exec( 'delete from performance' );
    R::exec( 'delete from payment' );
    R::exec( 'delete from memo' );

    $client = R::findOne('client', 'id=?', array($id));
    
    $anamnesis = R::dispense('anamnesis');
        $anamnesis->value = 'pew ifh ehdf sdfh sdfh sdufhsdhfisdhf uisdhisdhcvjhsdu vsdufvh sdufhusdhf usdhf usdhfu sdfhsdcvhdufh vsdufh usdhfu sdhfu hsdufhsd ufhusdh fusdhfu sdhfu sdhf usdf ';
        $anamnesis->creation_datetime = date( 'Y-m-d H:i:s');
    $client->ownAnamnesis = array($anamnesis);
    
    $memos = R::dispense('memo',3);
    $counter = 0;
    foreach ($memos as $memo) {
        $memo->value = 'memo';
        $memo->type = $counter;
        $memo->creation_datetime = date( 'Y-m-d H:i:s');
        $counter++;
    }
    $client->ownMemo = $memos;
    
    
    $payment = R::dispense('payment');
    $payment->amount = rand(30,150);
    $payment->creation_datetime = date( 'Y-m-d H:i:s');
    $payment->paymentgroup_nominal_number_of_performance = 3;
    $payment->paymentgroup_nominal_start_datetime = date( 'Y-m-d H:i:s',time()-86400);
    $payment->paymentgroup_nominal_end_datetime = date( 'Y-m-d H:i:s',time()-86400*45);
    $payment->client_id = $id;
    
    $performances = R::dispense('performance',3);
    
    foreach ($performances as $performance) {
    
    
    
        $performance->creation_datetime = date( 'Y-m-d H:i:s');
        $performance->datetime = date( 'Y-m-d H:i:s');
        $performance->duration = rand( 20,60);
        $performance->performancelocation_id = rand( 1,4);
        $performance->executed = rand( 0,1);
        $performance->reason = 'ragione del trattamento ragione del trattamento ragione del trattamento ragione del trattamento ragione del trattamento ';
        $performance->note = 'note del trattamento note del trattamentonote del trattamentonote del trattamentonote del trattamentonote del trattamento';
        
        
        for($i=1;$i<rand(2,5);$i++){
            $performancetype = R::findOne('performancetype', 'id=?', array($i));
        
            $performance->link('performance_performancetype', array('note' => 'testo di prova'))->performancetype = $performancetype;
        }
        
       
        $performance->client_id = $id;
        R::store($performance); 
         /*
        */
        //$client->ownPerformance = $performance;
    }
    /*
    $client->ownPerformance = $performance;
    R::store($client); 
    */
    
    
    $payment->ownPerformance = $performances;
    R::store($payment); 
    
    echo 'Finito';
    
});

$app->get('/populate/groups-with-clients', function () use ($app) {
    
    $stopwords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
    
    
    $numero_gruppi = rand(3,10);
    $group_rand_keys = array_rand($stopwords, $numero_gruppi);
    
    for($i=0;$i<$numero_gruppi;$i++){
        $group = R::dispense('group');
        $group->name = $stopwords[$group_rand_keys[$i]];
        
        
        $numero_clienti = 2;
        $client_rand_keys = array_rand($stopwords, $numero_clienti);
        
        
        $clients = R::dispense('client',$numero_clienti);
        
        $count = 0;
        foreach ($clients as $client) {
            $client->name = $stopwords[$client_rand_keys[$count]];
            $count++;
        }
        
        $group->ownClient = $clients;
        R::store($group); 
    
    }
    
});

$app->get('/populate/anamnesis', function () use ($app) {
    
    $stopwords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
    
    
    $entities = R::find('client');  
    foreach ($entities as $entity) {
        $numero_anamnesi = rand(3,10);
        
        
        $anamnesis = R::dispense('anamnesis',$numero_anamnesi);
        foreach ($anamnesis as $anamnesi) {
            $value = '';
            for($i=0;$i<rand(20,50);$i++){
                $value .= $stopwords[$i] . ' ';
            }
            $anamnesi->value = $value;
            $anamnesi->creation_datetime = date( 'Y-m-d H:i:s');
        }
        
        $entity->ownAnamnesis = $anamnesis;
        
        R::store($entity); 
    }
    
})->name('Panamnesis');

$app->get('/populate/memo', function () use ($app) {
    
    $stopwords = array('a', 'about', 'above', 'above', 'across', 'after', 'afterwards', 'again', 'against', 'all', 'almost', 'alone', 'along', 'already', 'also','although','always','am','among', 'amongst', 'amoungst', 'amount',  'an', 'and', 'another', 'any','anyhow','anyone','anything','anyway', 'anywhere', 'are', 'around', 'as',  'at', 'back','be','became', 'because','become','becomes', 'becoming', 'been', 'before', 'beforehand', 'behind', 'being', 'below', 'beside', 'besides', 'between', 'beyond', 'bill', 'both', 'bottom','but', 'by', 'call', 'can', 'cannot', 'cant', 'co', 'con', 'could', 'couldnt', 'cry', 'de', 'describe', 'detail', 'do', 'done', 'down', 'due', 'during', 'each', 'eg', 'eight', 'either', 'eleven','else', 'elsewhere', 'empty', 'enough', 'etc', 'even', 'ever', 'every', 'everyone', 'everything', 'everywhere', 'except', 'few', 'fifteen', 'fify', 'fill', 'find', 'fire', 'first', 'five', 'for', 'former', 'formerly', 'forty', 'found', 'four', 'from', 'front', 'full', 'further', 'get', 'give', 'go', 'had', 'has', 'hasnt', 'have', 'he', 'hence', 'her', 'here', 'hereafter', 'hereby', 'herein', 'hereupon', 'hers', 'herself', 'him', 'himself', 'his', 'how', 'however', 'hundred', 'ie', 'if', 'in', 'inc', 'indeed', 'interest', 'into', 'is', 'it', 'its', 'itself', 'keep', 'last', 'latter', 'latterly', 'least', 'less', 'ltd', 'made', 'many', 'may', 'me', 'meanwhile', 'might', 'mill', 'mine', 'more', 'moreover', 'most', 'mostly', 'move', 'much', 'must', 'my', 'myself', 'name', 'namely', 'neither', 'never', 'nevertheless', 'next', 'nine', 'no', 'nobody', 'none', 'noone', 'nor', 'not', 'nothing', 'now', 'nowhere', 'of', 'off', 'often', 'on', 'once', 'one', 'only', 'onto', 'or', 'other', 'others', 'otherwise', 'our', 'ours', 'ourselves', 'out', 'over', 'own','part', 'per', 'perhaps', 'please', 'put', 'rather', 're', 'same', 'see', 'seem', 'seemed', 'seeming', 'seems', 'serious', 'several', 'she', 'should', 'show', 'side', 'since', 'sincere', 'six', 'sixty', 'so', 'some', 'somehow', 'someone', 'something', 'sometime', 'sometimes', 'somewhere', 'still', 'such', 'system', 'take', 'ten', 'than', 'that', 'the', 'their', 'them', 'themselves', 'then', 'thence', 'there', 'thereafter', 'thereby', 'therefore', 'therein', 'thereupon', 'these', 'they', 'thickv', 'thin', 'third', 'this', 'those', 'though', 'three', 'through', 'throughout', 'thru', 'thus', 'to', 'together', 'too', 'top', 'toward', 'towards', 'twelve', 'twenty', 'two', 'un', 'under', 'until', 'up', 'upon', 'us', 'very', 'via', 'was', 'we', 'well', 'were', 'what', 'whatever', 'when', 'whence', 'whenever', 'where', 'whereafter', 'whereas', 'whereby', 'wherein', 'whereupon', 'wherever', 'whether', 'which', 'while', 'whither', 'who', 'whoever', 'whole', 'whom', 'whose', 'why', 'will', 'with', 'within', 'without', 'would', 'yet', 'you', 'your', 'yours', 'yourself', 'yourselves', 'the');
    
    
    $entities = R::find('client');  
    foreach ($entities as $entity) {
        $numero_anamnesi = rand(1,3);
        
        
        $anamnesis = R::dispense('memo',$numero_anamnesi);
        foreach ($anamnesis as $anamnesi) {
            $value = '';
            for($i=0;$i<rand(20,50);$i++){
                $value .= $stopwords[$i] . ' ';
            }
            $anamnesi->value = $value;
        }
        
        $entity->ownAnamnesis = $anamnesis;
        
        R::store($entity); 
    }
    
})->name('Pmemo');

$app->get('/populate/clients', function () use ($app) {
    try{
   $row = 1;
    $handle = fopen(__DIR__ . "/csv/soggetti.csv","r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $num = count($data);
        /*echo "<p> $num campi sulla linea $row: <br /></p>\n";
        $row++;
        for ($c=0; $c < $num; $c++) {
            echo $data[$c] . "<br>\n";
        }*/
        
        if($data[0] != '' && $data[0] != 'Nome'){
            $client = R::dispense('client');
            
            $client->name = $data[0];
            $client->surname = $data[1];
            
        /*
            if($data[6] != ''){
                
                $anamnesis = R::dispense('anamnesis');
                    $anamnesis->value = utf8_encode($data[6]);
                    $anamnesis->creation_datetime = date( 'Y-m-d H:i:s');
                $client->ownAnamnesis = array($anamnesis);
                
            }
            */
            R::store($client); 
        }
    }
    fclose($handle);
    echo 'Finito';
    } catch (Exception $e) {
        $app->response()->status(400);
        echo '<pre>';
        print_r($e);
        $app->response()->header('X-Status-Reason', $e->getMessage());
      }
})->name('Pclients');

?>