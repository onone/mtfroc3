<?php

$entities['group_rate'] =  array(
    'entityName' => 'group_rate',
    'singular_label' => 'tariffa_pacchetto',
    'plural_label' => 'tariffe_pacchetto',
    'primary_key' => 'id',
    'representation' => '<<amount>>',
    'hideLinkInMenu' => TRUE,
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