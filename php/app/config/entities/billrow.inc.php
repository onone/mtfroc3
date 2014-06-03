<?php

$entities['billrow'] =  array(
    'entityName' => 'billrow',
    'singular_label' => 'riga_fattura',
    'plural_label' => 'righe_fattura',
    'primary_key' => 'id',
    'hideLinkInMenu' => TRUE,
    'viewParameter' => array(
        'iconClass' => 'fa fa-stack-overflow'
        ),
    'representation' => '<<date>> <<amount>>',
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false,
            //'visible' => false,
            'visibleInList' => false,
        ),
        'value' => array(
            'label' => 'notazione',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'textarea'
                )
            )
        ),
        'bill_id' => array(
            'label' => 'fattura',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/bill'
                        )
                )
            )
        ),
        'amount' => array(
            'label' => 'importo_(€)',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/bill'
                        )//
                )
            )
        ),
        'quantity' => array(
            'label' => 'quantità',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        ),
        'tax' => array(
            'label' => 'iva_%',
            'defaultValue' => 22,
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        )
    )
);