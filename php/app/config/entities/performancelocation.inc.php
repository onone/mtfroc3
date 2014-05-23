<?php

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