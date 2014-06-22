<?php

$entities['performance_performancetype'] =  array(
    'entityName' => 'performance_performancetype',
    'singular_label' => 'trattamento',
    'plural_label' => 'trattamenti',
    'primary_key' => 'id',
    'representation' => '<<note>>',
    'hideLinkInMenu' => TRUE,
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
        ),
        'position' => array(
            'label' => 'posizione',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'number'
                )
            )
        )
    )
);