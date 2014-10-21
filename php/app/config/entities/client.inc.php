<?php
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
        'birthdate' => array(
            'label' => 'data_di_nascita',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                        /*,
                        'otherAttribute' => array(
                            'data-minYear' => '1900' // non prende questo parametro
                        )*/
                )
            )
        ),
        'indirizzo' => array(
            'label' => 'indirizzo',
            /*'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required' //  nomi di funzioni contenute in validation.js.  Es. 'required,required'
                )
            ),*/
        ),
        'note' => array(
            'label' => 'note'
        ),
        'piva' => array(
            'label' => 'partita_iva'
        ),
        'codicefiscale' => array(
            'label' => 'codice_fiscale'
        ),
        'provenienza' => array(
            'label' => 'provenienza'
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
