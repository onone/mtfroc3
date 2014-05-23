<?php

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