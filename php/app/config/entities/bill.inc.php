<?php

$entities['bill'] =  array(
    'entityName' => 'bill',
    'singular_label' => 'fattura',
    'plural_label' => 'fatture',
    'primary_key' => 'id',
    'viewParameter' => array(
        'iconClass' => 'fa fa-file-text'
        ),
    'representation' => '<<date>> <<amount>>',
    'hideLinkInMenu' => TRUE,
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