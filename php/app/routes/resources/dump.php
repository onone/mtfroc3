<?php

use RedBean_Facade as R;

$app->get('/dump', $authAdmin('admin'), function () use ($app) {  
    
    try {
        echo 'dump';
        /*
        $r = exec('echo "cron  works sotto dir" | mail -s "a subject" fslepko@gmail.com');
        echo "<pre>";
        var_dump($r);
        */
        
        exec('mysqldump -e --user=langeli php',$o);
        //https://api.sendgrid.com/api/mail.send.json?api_user=l904&api_key=sgp904&to=fslepko@gmail.com&toname=Destination&subject=Example_Subject&text=testingtextbody&from=l122124x@gmail.com
        print_r($o);
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