<?php

$entities['performance'] =  array(
    'entityName' => 'performance',
    'singular_label' => 'prestazione',
    'plural_label' => 'prestazioni',
    'primary_key' => 'id',
    'representation' => '<<datetime>>',
    'viewParameter' => array(
        'iconClass' => 'fa fa-user-md'
        ),
    'fields' => array( // Gli elementi verra visualizzati nell'ordine in cui compaiono nell'array
        // Tutti gli elemeni di default sono visibili (visible) ed editabili (editable)
        'id' => array(
            'editable' => false
        ),
        'client_id' => array(
            'label' => 'cliente',
            'customConfig'=> array(
                'xeditable' => array(
                        'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/client'
                        ),
                        
                        /*'type'     => 'select2',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select2/client'
                        ),
                        'jsoptions'=> array(
                            'select2' => array(
                                'minimumInputLength' => 4,
                                'allowClear' => true
                            )
                        )*/
                )
            )
        ),
        'payment_id' => array(
            'label' => 'pagamento',
            'customConfig'=> array(
                'xeditable' => array(
                        //'validationFunctionNames'     => 'required',
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/performancepaymentlist'
                        ),
                        /*'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/payment'
                        ),*/
                        /*'type'     => 'select2',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select2/payment'
                        ),
                        'jsoptions'=> array(
                            'select2' => array(
                                'minimumInputLength' => 4,
                                'allowClear' => true
                            )
                        )*/
                )
            )
        ),
        'performancelocation_id' => array(
            'label' => 'location',
            'customConfig'=> array(
                'xeditable' => array(
                        'type'     => 'select',
                        'otherAttribute' => array(
                            'data-source' => BASE_URL . '/resources/xeditable/select/performancelocation'
                        )
                )
            ),
            'defaultValue' => 1
        ),
        'datetime' => array(
            'label' => 'data',
            'customConfig'=> array(
                'xeditable' => array(
                    'validationFunctionNames'     => 'required',
                    'type'     => 'combodate'
                )
            ),
            'defaultValue' => date('Y-m-d')
        ),
        'duration' => array(
            'label' => 'durata (min)',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'number'
                )
            )
        ),
        'pre_note' => array(
            'label' => 'bilancio pre',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea'
                )
            )
        ),
        'post_note' => array(
            'label' => 'bilancio post',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'textarea'
                )
            )
        ),
        'executed' => array(
            'label' => 'Eseguita',
            'customConfig'=> array(
                'xeditable' => array(
                    'type'     => 'select',
                    'otherAttribute' => array(
                        'data-source' => array(array('text'=>'Si','value'=>1),array('text'=>'No','value'=>0)),
                    ),
                    'otherAttributeParam' => array(
                        'data-source' => array(
                            'toJson' => TRUE
                            ),
                    )
                )
            ),
            'defaultValue' => 1
        ),
        'creation_datetime' => array(
            'label' => 'data_di_creazione',
            'editable' => false,
            'onInsertValue' => 'DATETIME_NOW'
        )
    ),
    'relations' => array(
        'performance_performancetype' => array(
            'type' => 'many-to-one', // ex. MANY performance_performancetype belongs TO one performance. Oppure performance has many performance_performancetype
        ),
    )
);
