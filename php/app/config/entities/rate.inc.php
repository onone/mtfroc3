<?php

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
    ),
    'relations' => array(
        'group_rate' => array(
            'type' => 'many-to-one', // ex. MANY group_rate belongs TO one group. Oppure group has many group_rate
        ),
        'group' => array(
            'type' => 'many-to-many', // ex. MANY group_rate belongs TO one group. Oppure group has many group_rate
        )
    )
);