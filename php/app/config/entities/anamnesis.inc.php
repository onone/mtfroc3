<?php

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