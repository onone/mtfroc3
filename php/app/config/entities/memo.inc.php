<?php

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