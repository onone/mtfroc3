<?php

$entities['payment'] =  array(
    'entityName' => 'payment',
    'singular_label' => 'pagamento',
    'plural_label' => 'pagamenti',
    'primary_key' => 'id',
    'viewParameter' => array(
        'iconClass' => 'fa fa-money'
        ),
    'representation' => '<<amount>> <<name>>',
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false,
            //'visible' => false,
            'visibleInList' => false,
        ),
        'name' => array(
            'label' => 'nome',
            'defaultValue' => 'Singola prestazione'
        ),
        'amount' => array(
            'label' => 'importo',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        ),
        'client_id' => array(
            //'visible' => false
            
            'label' => 'cliente',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/client'
                        )
                )
            ),
        ),
        'collection_date' => array(
            'label' => 'data_incasso',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate',
                        'otherAttribute' => array(
                            'data-template' => 'D MMM YYYY  HH:mm',
                            'data-format' => 'YYYY-MM-DD HH:mm',
                            'data-viewformat' => 'MMM D, YYYY, HH:mm'
                        )
                )
            ),
            'defaultValue' => date('Y-m-d')
        ),
        'paymentgroup_nominal_number_of_performance' => array(
            'label' => 'numero_di_trattamenti',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'number'
                )
            )
        ),
        'paymentgroup_nominal_start_datetime' => array(
            'label' => 'data_inizio_validita',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                )
            )
        ),
        'paymentgroup_nominal_end_datetime' => array(
            'label' => 'data_fine_validita',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'combodate'
                )
            )
        ),
        'paymentform_id' => array(
            'label' => 'forma_di_pagamento',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/paymentform'
                        )
                )
            )
        ),
        'paymentstate_id' => array(
            'label' => 'stato_di_pagamento',
            'defaultValue' => 2,
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/paymentstate'
                        )
                )
            )
        ),
        'paymentformula_id' => array(
            'label' => 'formula_di_pagamento',
            'defaultValue' => 1,
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/paymentformula'
                        )
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
        'bill_description' => array(
            'label' => 'notazione_per_fattura',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'textarea'
                )
            )
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        ),
        'update_datetime' => array(
            'label' => 'data_di_modifica',
            'editable' => false,
            'onUpdateValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'performance' => array(
            'type' => 'many-to-one', // ex. MANY performance belongs TO one payment. Oppure payment has many performance
        ),
    )
);