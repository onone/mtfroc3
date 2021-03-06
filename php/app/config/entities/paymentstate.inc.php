<?php

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