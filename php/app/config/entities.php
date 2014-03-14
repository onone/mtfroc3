<?php 
$entities = array();
/* REDBEAN
RedBeanPHP generates a sane and readable database schema for you. Here are the schema conventions used by RedBeanPHP:
Field names:	Lowercase a-z, 0-9 and underscore (_)
Table name:	Should match bean type, a-z, 0-9
Primary key:	Each table should have a primary key named 'id' (int, auto-incr)
Foreign key:	Format: <TYPE>_id
Link table:	Format: <TYPE1>_<TYPE2> sorted alphabetically
*/

$entities['client'] =  array(
    'entityName' => 'client',
    'singular_label' => 'cliente',
    'plural_label' => 'clienti',
    'primary_key' => 'id',
    'viewParameter' => array(
        'iconClass' => 'fa fa-user'
        ),
    'representation' => '<<name>> <<surname>>',
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false,
            //'visible' => false,
            'visibleInList' => false,
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required' //  nomi di funzioni contenute in validation.js.  Es. 'required,required'
                )
            ),
            'RedBean' => array(
            )
        ),
        'surname' => array(
            'label' => 'cognome',
        ),
        'birthdate' => array(
            'label' => 'data_di_nascita',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                )
            )
        ),
        'indirizzo' => array(
            'label' => 'indirizzo',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required' //  nomi di funzioni contenute in validation.js.  Es. 'required,required'
                )
            ),
            'RedBean' => array(
            )
        ),
        'note' => array(
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'email'
                )
            )),
        'group_id' => array(
            'label' => 'gruppo',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/group'
                        )
                )
            )
        ),
        'email' => array(
            'label' => 'email',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'email'
                )
            )
        ),
        'mobile_phone' => array(
            'label' => 'cellulare'
            ),
        'phone' => array(
            'label' => 'telefono_fisso'
            ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        ),
        'update_datetime' => array(
            'label' => 'data_di_modifica',
            'editable' => false,
            'onUpdateValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'anamnesis' => array(
            'type' => 'many-to-one', // ex. MANY anamnesis belongs TO one client. Oppure client has many anamnesis
        ),
        'memo' => array(
            'type' => 'many-to-one', // ex. MANY client belongs TO one group. Oppure group has many client
        ),
        'performance' => array(
            'type' => 'many-to-one', // ex. MANY client belongs TO one group. Oppure group has many client
        )
    )
);


$entities['group'] =  array(
    'entityName' => 'group',
    'singular_label' => 'gruppo',
    'plural_label' => 'gruppi',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-group'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'active' => array(
            'label' => 'attivo',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'select',
                    'otherAttribute' => array(
                        'data-source' => array(array('text'=>'Si','value'=>1),array('text'=>'No','value'=>0)),
                    ),
                    'otherAttributeParam' => array(
                        'data-source' => array(
                            'toJson' => TRUE
                            ),
                    )
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'client' => array(
            'type' => 'many-to-one', // ex. MANY client belongs TO one group. Oppure group has many client
        ),
        'group_rate' => array(
            'type' => 'many-to-one', // ex. MANY group_rate belongs TO one group. Oppure group has many group_rate
        ),
    )
);

$entities['group_rate'] =  array(
    'entityName' => 'group_rate',
    'singular_label' => 'tariffa_pacchetto',
    'plural_label' => 'tariffe_pacchetto',
    'primary_key' => 'id',
    'representation' => '<<amount>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-money'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        
        'group_id' => array(
            'label' => 'gruppo',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/group'
                        )
                )
            )
        ),
        'rate_id' => array(
            'label' => 'tariffa',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/rate'
                        )
                )
            )
        ),
        'amount' => array(
            'label' => 'importo',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'number'
                )
            )
        )
    )
);

$entities['rate'] =  array(
    'entityName' => 'rate',
    'singular_label' => 'pacchetto',
    'plural_label' => 'pacchetti',
    'primary_key' => 'id',
    'representation' => '<<name>> <<performance_number>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-money'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'performance_number' => array(
            'label' => 'numero_di_trattamenti',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'number'
                )
            )
        )
    )
);

$entities['memo'] =  array(
    'entityName' => 'memo',
    'singular_label' => 'nota_cliente',
    'plural_label' => 'note_cliente',
    'primary_key' => 'id',
    'representation' => '<<value>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-clipboard'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'value' => array(
            'label' => 'nota',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea',
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'type' => array(
            'label' => 'tipo_di_avviso',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'select',
                    'otherAttribute' => array(
                        'data-source' => array(array('text'=>'Default','value'=>0),array('text'=>'Warning','value'=>1),array('text'=>'Alert','value'=>2)),
                    ),
                    'otherAttributeParam' => array(
                        'data-source' => array(
                            'toJson' => TRUE
                            ),
                    )
                )
            )
        ),
        'client_id' => array(
            'label' => 'cliente',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/client'
                        )
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    )
);

$entities['anamnesis'] =  array(
    'entityName' => 'anamnesis',
    'singular_label' => 'anamnesi',
    'plural_label' => 'anamnesi',
    'primary_key' => 'id',
    'representation' => '<<value>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-stethoscope'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'value' => array(
            'label' => 'nota',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea',
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'client_id' => array(
            'label' => 'cliente',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/client'
                        )
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    )
);


$entities['performance'] =  array(
    'entityName' => 'performance',
    'singular_label' => 'prestazione',
    'plural_label' => 'prestazioni',
    'primary_key' => 'id',
    'representation' => '<<reason>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-user-md'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'client_id' => array(
            'label' => 'cliente',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        /*'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/client'
                        ),*/
                        'type'     => 'select2',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select2/client'
                        ),
                        'jsoptions'=> array(
                            'select2' => array(
                                'minimumInputLength' => 4,
                                'allowClear' => true
                            )
                        )
                )
            )
        ),
        'payment_id' => array(
            'label' => 'pagamento',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/client'
                        ),
                        /*'type'     => 'select2',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select2/payment'
                        ),
                        'jsoptions'=> array(
                            'select2' => array(
                                'minimumInputLength' => 4,
                                'allowClear' => true
                            )
                        )*/
                )
            )
        ),
        'performancelocation_id' => array(
            'label' => 'location',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/performancelocation'
                        )
                )
            )
        ),
        'datetime' => array(
            'label' => 'data',
            'customConfig'=> array(
                'xeditable' => array(
                    'validationFunctionNames'     => 'required',
                    'type'     => 'combodate'
                )
            )
        ),
        'duration' => array(
            'label' => 'durata (min)',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'number'
                )
            )
        ),
        'reason' => array(
            'label' => 'motivazione',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea'
                )
            )
        ),
        'note' => array(
            'label' => 'annotazione',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea'
                )
            )
        ),
        'executed' => array(
            'label' => 'Eseguita',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'select',
                    'otherAttribute' => array(
                        'data-source' => array(array('text'=>'Si','value'=>1),array('text'=>'No','value'=>0)),
                    ),
                    'otherAttributeParam' => array(
                        'data-source' => array(
                            'toJson' => TRUE
                            ),
                    )
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'performance_performancetype' => array(
            'type' => 'many-to-one', // ex. MANY performance_performancetype belongs TO one performance. Oppure performance has many performance_performancetype
        ),
    )
);



$entities['paymentstate'] =  array(
    'entityName' => 'paymentstate',
    'singular_label' => 'stato_di_pagamento',
    'plural_label' => 'stati_di_pagamento',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-shopping-cart'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'payment' => array(
            'type' => 'many-to-one', // ex. MANY payment belongs TO one paymentstate. Oppure paymentstate has many payment
        )
    )
);

$entities['paymentformula'] =  array(
    'entityName' => 'paymentformula',
    'singular_label' => 'formula_di_pagamento',
    'plural_label' => 'formule_di_pagamento',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-calendar'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'payment' => array(
            'type' => 'many-to-one', // ex. MANY payment belongs TO one paymentstate. Oppure paymentstate has many payment
        )
    )
);


$entities['paymentform'] =  array(
    'entityName' => 'paymentform',
    'singular_label' => 'forma_di_pagamento',
    'plural_label' => 'forme_di_pagamento',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-credit-card'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'payment' => array(
            'type' => 'many-to-one', // ex. MANY payment belongs TO one paymentstate. Oppure paymentstate has many payment
        )
    )
);

$entities['performancelocation'] =  array(
    'entityName' => 'performancelocation',
    'singular_label' => 'location',
    'plural_label' => 'location',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-map-marker'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'address' => array(
            'label' => 'indirizzo'
        ),
        'city' => array(
            'label' => 'citta'
        ),
    ),
    'relations' => array(
        'payment' => array(
            'type' => 'many-to-one', // ex. MANY payment belongs TO one paymentstate. Oppure paymentstate has many payment
        )
    )
);

$entities['performancetype'] =  array(
    'entityName' => 'performancetype',
    'singular_label' => 'tipo_di_trattamento',
    'plural_label' => 'tipi_di_trattamento',
    'primary_key' => 'id',
    'representation' => '<<name>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-crosshairs'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'name' => array(
            'label' => 'nome',
            'customConfig'=> array(
                'xeditable' => array(
                     'validationFunctionNames'     => 'required' // da implementare
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'payment' => array(
            'type' => 'many-to-one', // ex. MANY payment belongs TO one paymentstate. Oppure paymentstate has many payment
        )
    )
);


$entities['performance_performancetype'] =  array(
    'entityName' => 'performance_performancetype',
    'singular_label' => 'trattamento',
    'plural_label' => 'trattamenti',
    'primary_key' => 'id',
    'representation' => '<<note>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-crosshairs'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        
        'performance_id' => array(
            'label' => 'trattamento',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/performance'
                        )
                )
            )
        ),
        'performancetype_id' => array(
            'label' => 'tipo_di_trattamento',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/performancetype'
                        )
                )
            )
        ),
        'note' => array(
            'label' => 'annotazione',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea'
                )
            )
        )
    )
);


$entities['payment'] =  array(
    'entityName' => 'payment',
    'singular_label' => 'pagamento',
    'plural_label' => 'pagamenti',
    'primary_key' => 'id',
    'viewParameter' => array(
        'iconClass' => 'fa fa-money'
        ),
    'representation' => '<<amount>> <<name>>',
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false,
            //'visible' => false,
            'visibleInList' => false,
        ),
        'name' => array(
            'label' => 'nome',
            'defaultValue' => 'Singola prestazione'
        ),
        'amount' => array(
            'label' => 'importo',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        ),
        'client_id' => array(
            'visible' => false
        ),
        'collection_date' => array(
            'label' => 'data_incasso',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate',
                        'otherAttribute' => array(
                            'data-template' => 'D MMM YYYY  HH:mm',
                            'data-format' => 'YYYY-MM-DD HH:mm',
                            'data-viewformat' => 'MMM D, YYYY, HH:mm'
                        )
                )
            )
        ),
        'paymentgroup_nominal_number_of_performance' => array(
            'label' => 'numero_di_trattamenti',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        ),
        'paymentgroup_nominal_start_datetime' => array(
            'label' => 'data_inizio_validita',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                )
            )
        ),
        'paymentgroup_nominal_end_datetime' => array(
            'label' => 'data_fine_validita',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                )
            )
        ),
        'paymentform_id' => array(
            'label' => 'forma_di_pagamento',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/paymentform'
                        )
                )
            )
        ),
        'paymentstate_id' => array(
            'label' => 'stato_di_pagamento',
            'defaultValue' => 2,
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/paymentstate'
                        )
                )
            )
        ),
        'paymentformula_id' => array(
            'label' => 'formula_di_pagamento',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/paymentformula'
                        )
                )
            )
        ),
        'bill_id' => array(
            'label' => 'fattura',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/bill'
                        )
                )
            )
        ),
        'bill_description' => array(
            'label' => 'notazione_per_fattura',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'textarea'
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        ),
        'update_datetime' => array(
            'label' => 'data_di_modifica',
            'editable' => false,
            'onUpdateValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'performance' => array(
            'type' => 'many-to-one', // ex. MANY performance belongs TO one payment. Oppure payment has many performance
        ),
    )
);


$entities['bill'] =  array(
    'entityName' => 'bill',
    'singular_label' => 'fattura',
    'plural_label' => 'fatture',
    'primary_key' => 'id',
    'viewParameter' => array(
        'iconClass' => 'fa fa-file-text'
        ),
    'representation' => '<<date>> <<amount>>',
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false,
            //'visible' => false,
            'visibleInList' => false,
        ),
        'date' => array(
            'label' => 'data',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                )
            )
        ),
        'amount' => array(
            'label' => 'importo_(€)',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        )
    ),
    'relations' => array(
        'billrow' => array(
            'type' => 'many-to-one', // ex. MANY billrow belongs TO one bill. Oppure bill has many billrow
        )
    )
);


$entities['billrow'] =  array(
    'entityName' => 'billrow',
    'singular_label' => 'riga_fattura',
    'plural_label' => 'righe_fattura',
    'primary_key' => 'id',
    'viewParameter' => array(
        'iconClass' => 'fa fa-stack-overflow'
        ),
    'representation' => '<<date>> <<amount>>',
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false,
            //'visible' => false,
            'visibleInList' => false,
        ),
        'value' => array(
            'label' => 'notazione',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'textarea'
                )
            )
        ),
        'bill_id' => array(
            'label' => 'fattura',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/bill'
                        )
                )
            )
        ),
        'amount' => array(
            'label' => 'importo_(€)',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/bill'
                        )//
                )
            )
        ),
        'quantity' => array(
            'label' => 'quantità',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        ),
        'tax' => array(
            'label' => 'iva_%',
            'defaultValue' => 22,
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        )
    )
);

?>