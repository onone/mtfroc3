15/01/2014 6: 1 db layout + 5 configurazione cloud9 + openshift
16/01/2014 5: ricognizione php framewokr + slim framework documentazione,test,configurazione + connessione e test redbean orm
17/01/2014 4: bower + config e sistamazione directory slim + ricerca ed analisi dei plugin di bootstrap utilizzabili
18/01/2014 2.5: 1 twig documentazione e test + 1.5 FETCHING ONE CLIP
19/01/2014 1: 1 FETCHING ONE CLIP
20/01/2014 1: 1 STUDIO datatables e x-editable
21/01/2014 2.5: 1 STUDIO x-editable + 0.5 creazione directory app e cambio configurazione + 1 interfaccia inserimento entita
22/01/2014 6: 6 configurazione, interfaccia e risorse per gestione universale entita
23/01/2014 : 2 configurazione, interfaccia e risorse per gestione universale entita
24/01/2014 4: 
25/01/2014 3: 
27/01/2014 1: 1 db refactoring
28/01/2014 1: 1 db refactoring post riunione
29/01/2014 2: default entity mods
30/01/2014 1: default entity mods
31/01/2014 2: default entity mods
03/01/2014 1: "
04/01/2014 3: "
05/01/2014 1: "
06/01/2014 2: "
11/02/2014 5: "
16/02/2014 1: "
03/03/2014 2: custom client
04/03/2014 1: custom client
05/03/2014 1: custom client
10/03/2014 1: custom client
13/03/2014 1: custom client
15/04/2014 2: custom client
16/04/2014 1: custom client
29/04/2014 1: 
30/04/2014 1: 
16/05/2014 2
16/05/2014 3


OPEN SHIFT
http://php-mftr.rhcloud.com/
l12...@gmail.com
manga..

phpmyadmin: https://php-mftr.rhcloud.com/phpmyadmin/

GIT:
ADD: git add .
COMMIT: git commit -m 'first release'
DEPLOY: usa tasto di C9
URL DI ESEMPIO: http://php-mftr.rhcloud.com/test
http://php-mftr.rhcloud.com/entity/client

********************************************************************************
!!! BUGS
********************************************************************************
https://mftr3-c9-langeli.c9.io/php/entity/memo

PERFOMRANCE REDBEAN FATTO 90%


https://mftr3-c9-langeli.c9.io/php/entity/rate/10

$app->response()->status(400);
$app->response()->header('X-Status-Reason', $e->getMessage());
!! SE DEBUG print_r ERRO





********************************************************************************
!!! BUGS RISOLTI
********************************************************************************

https://mftr3-c9-langeli.c9.io/php/entity/rate/3
Tabella di relazione tariffe pacchetto manca il nome del group


https://mftr3-c9-langeli.c9.io/php/entity/client/10
https://mftr3-c9-langeli.c9.io/php/entity/client/5
dati tabelle relazionate

********************************************************************************
!!! IMPLEMENTAZIONI
********************************************************************************
editing in line
https://datatables.net/release-datatables/examples/api/highlight.html





FATTO

opzioni js x-editable -> OK
utilizzo less js (icone -> OK)
testare utilizzo less js (icone ->  e file main.less)
preparazione di macro e template per twig - copiandoli da http://www.cliptheme.com/demo/clip-one/table_responsive.html -> FATTO IN PARTE
http://www.cliptheme.com/demo/clip-one/form_x_editable.html http://vitalets.github.io/x-editable/docs.html http://jsfiddle.net/xBB5x/62/
FARE UN POST!!! PER New client id e testarlo -> FATTO
validate -> FATTO
A fianco ad campi correlati one-to-many fornire link all'altra entita -> FATTO
vista tabella in caso di listato entita di default
cancellazione entita



UTILS
echo "<pre>";
print_r();
echo "</pre>";
die();

`group

<pre>
{{ dump(entityConfiguration) }}
</pre>